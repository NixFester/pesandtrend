<?php

namespace Tests\Feature;

use App\Models\Mentor;
use App\Models\MentorBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BimbelBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_lists_active_mentors_and_excludes_inactive(): void
    {
        $activeMentor = Mentor::factory()->create([
            'name' => 'Mentor Aktif',
            'is_active' => true,
        ]);

        $inactiveMentor = Mentor::factory()->inactive()->create([
            'name' => 'Mentor Tidak Aktif',
        ]);

        $response = $this->get(route('bimbel.index'));

        $response->assertOk();
        $response->assertSee('Mentor Aktif');
        $response->assertDontSee('Mentor Tidak Aktif');
    }

    public function test_show_displays_active_mentor_detail(): void
    {
        $mentor = Mentor::factory()->create([
            'name' => 'Ust. Farhan Maulana',
            'is_active' => true,
        ]);

        $response = $this->get(route('bimbel.show', $mentor->slug));

        $response->assertOk();
        $response->assertSee('Ust. Farhan Maulana');
        $response->assertSee($mentor->formatted_price);
    }

    public function test_show_returns_404_for_inactive_or_unknown_mentor(): void
    {
        $inactiveMentor = Mentor::factory()->inactive()->create();

        $this->get(route('bimbel.show', $inactiveMentor->slug))
            ->assertNotFound();

        $this->get(route('bimbel.show', 'mentor-tidak-ada'))
            ->assertNotFound();
    }

    public function test_book_creates_booking_and_redirects_to_payment(): void
    {
        $mentor = Mentor::factory()->create([
            'price' => 250000,
            'is_active' => true,
        ]);

        $response = $this->post(route('bimbel.book', $mentor->slug), [
            'client_name' => 'Ahmad Santoso',
            'client_email' => 'ahmad@example.com',
            'client_whatsapp' => '081234567890',
        ]);

        $booking = MentorBooking::first();
        $this->assertNotNull($booking);
        $this->assertEquals('Ahmad Santoso', $booking->client_name);
        $this->assertEquals('ahmad@example.com', $booking->client_email);
        $this->assertEquals('081234567890', $booking->client_whatsapp);
        $this->assertEquals(250000, $booking->amount);
        $this->assertEquals('pending', $booking->status);
        $this->assertStringStartsWith('BIM-', $booking->code);
        $this->assertEquals('mentor_booking_'.$booking->id, $booking->external_id);

        $response->assertRedirect(route('bimbel.payment', $booking));
    }

    public function test_book_validates_required_fields(): void
    {
        $mentor = Mentor::factory()->create(['is_active' => true]);

        $response = $this->post(route('bimbel.book', $mentor->slug), []);

        $response->assertSessionHasErrors([
            'client_name',
            'client_email',
            'client_whatsapp',
        ]);
        $this->assertDatabaseEmpty('mentor_bookings');
    }

    public function test_book_returns_404_for_inactive_mentor(): void
    {
        $inactiveMentor = Mentor::factory()->inactive()->create();

        $response = $this->post(route('bimbel.book', $inactiveMentor->slug), [
            'client_name' => 'Ahmad Santoso',
            'client_email' => 'ahmad@example.com',
            'client_whatsapp' => '081234567890',
        ]);

        $response->assertNotFound();
        $this->assertDatabaseEmpty('mentor_bookings');
    }

    public function test_xendit_webhook_marks_booking_as_paid(): void
    {
        config(['services.xendit.webhook_token' => 'test_webhook_token_abc']);

        $mentor = Mentor::factory()->create();
        $booking = MentorBooking::factory()->pending()->create([
            'mentor_id' => $mentor->id,
            'amount' => 300000,
        ]);

        $response = $this->postJson('/webhooks/xendit', [
            'id' => 'evt_bimbel_001',
            'external_id' => "mentor_booking_{$booking->id}",
            'status' => 'PAID',
        ], [
            'x-callback-token' => 'test_webhook_token_abc',
        ]);

        $response->assertOk();

        $booking->refresh();
        $this->assertTrue($booking->isPaid());
        $this->assertEquals('paid', $booking->status);
        $this->assertNotNull($booking->paid_at);
        $this->assertEquals('evt_bimbel_001', $booking->xendit_id);

        $this->assertDatabaseHas('xendit_webhook_events', [
            'event_id' => 'evt_bimbel_001',
        ]);
    }

    public function test_xendit_webhook_rejects_invalid_token(): void
    {
        config(['services.xendit.webhook_token' => 'correct_token']);

        $response = $this->postJson('/webhooks/xendit', [
            'id' => 'evt_bimbel_002',
            'external_id' => 'mentor_booking_1',
            'status' => 'PAID',
        ], [
            'x-callback-token' => 'wrong_token',
        ]);

        $response->assertStatus(401);
    }

    public function test_xendit_webhook_idempotency_does_not_double_process(): void
    {
        config(['services.xendit.webhook_token' => 'test_token']);

        $mentor = Mentor::factory()->create();
        $booking = MentorBooking::factory()->pending()->create([
            'mentor_id' => $mentor->id,
        ]);

        $payload = [
            'id' => 'evt_bimbel_duplicate',
            'external_id' => "mentor_booking_{$booking->id}",
            'status' => 'PAID',
        ];
        $headers = ['x-callback-token' => 'test_token'];

        // First call
        $this->postJson('/webhooks/xendit', $payload, $headers)->assertOk();
        $booking->refresh();
        $firstPaidAt = $booking->paid_at;
        $this->assertTrue($booking->isPaid());

        // Second call with identical event ID
        $this->postJson('/webhooks/xendit', $payload, $headers)->assertOk();
        $booking->refresh();
        $this->assertEquals($firstPaidAt->toDateTimeString(), $booking->paid_at->toDateTimeString());
    }

    public function test_success_page_self_heals_pending_booking_with_xendit_id(): void
    {
        $mentor = Mentor::factory()->create(['is_active' => true]);
        $booking = MentorBooking::factory()->pending()->create([
            'mentor_id' => $mentor->id,
            'xendit_id' => 'xnd_self_heal_123',
        ]);

        $this->assertFalse($booking->isPaid());

        $response = $this->get(route('bimbel.success', [
            'slug' => $mentor->slug,
            'booking' => $booking->id,
        ]));

        $response->assertOk();

        $booking->refresh();
        $this->assertTrue($booking->isPaid());
        $this->assertNotNull($booking->paid_at);
    }

    public function test_simulate_payment_marks_booking_paid_and_redirects(): void
    {
        $mentor = Mentor::factory()->create(['is_active' => true]);
        $booking = MentorBooking::factory()->pending()->create([
            'mentor_id' => $mentor->id,
        ]);

        $response = $this->get(route('bimbel.simulate-payment', $booking));

        $response->assertRedirect(route('bimbel.success', [
            'slug' => $mentor->slug,
            'booking' => $booking->id,
        ]));

        $booking->refresh();
        $this->assertTrue($booking->isPaid());
        $this->assertEquals('XENDIT_SIMULATED', $booking->payment_method);
        $this->assertNotNull($booking->paid_at);
    }

    public function test_proof_unsigned_returns_forbidden(): void
    {
        $mentor = Mentor::factory()->create();
        $booking = MentorBooking::factory()->paid()->create(['mentor_id' => $mentor->id]);

        $response = $this->get(route('bimbel.proof', ['booking' => $booking->id]));

        $response->assertForbidden();
    }

    public function test_proof_signed_but_unpaid_returns_404(): void
    {
        $mentor = Mentor::factory()->create();
        $booking = MentorBooking::factory()->pending()->create(['mentor_id' => $mentor->id]);

        $signedUrl = URL::signedRoute('bimbel.proof', ['booking' => $booking->id]);

        $response = $this->get($signedUrl);

        $response->assertNotFound();
    }

    public function test_proof_signed_and_paid_downloads_pdf(): void
    {
        $mentor = Mentor::factory()->create();
        $booking = MentorBooking::factory()->paid()->create(['mentor_id' => $mentor->id]);

        $signedUrl = URL::signedRoute('bimbel.proof', ['booking' => $booking->id]);

        $response = $this->get($signedUrl);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', "attachment; filename=\"bukti-bimbel-{$booking->code}.pdf\"");
    }
}
