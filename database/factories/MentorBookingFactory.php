<?php

namespace Database\Factories;

use App\Models\Mentor;
use App\Models\MentorBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MentorBooking>
 */
class MentorBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mentor_id' => Mentor::factory(),
            'client_name' => fake()->name(),
            'client_email' => fake()->unique()->safeEmail(),
            'client_whatsapp' => '08'.fake()->numerify('##########'),
            'amount' => fake()->randomElement([150000, 250000, 350000, 500000]),
            'payment_method' => null,
            'xendit_id' => null,
            'external_id' => null,
            'invoice_url' => null,
            'status' => 'pending',
            'paid_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'xendit_id' => 'xnd_'.fake()->unique()->numerify('##########'),
            'external_id' => 'mentor_booking_'.fake()->unique()->numerify('####'),
            'payment_method' => 'BANK_TRANSFER',
            'paid_at' => now(),
        ]);
    }
}
