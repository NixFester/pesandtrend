<?php

namespace App\Livewire\Onboarding;

use App\Models\School;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplyWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public ?int $schoolId = null;

    public string $studentName = '';

    public string $studentNik = '';

    public string $studentGender = '';

    public string $studentBirthPlace = '';

    public ?string $studentBirthDate = null;

    public string $studentAddress = '';

    public string $previousSchool = '';

    public string $targetJenjang = '';

    public string $parentName = '';

    public string $parentEmail = '';

    public string $parentPhone = '';

    public string $parentWhatsapp = '';

    public string $notes = '';

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $documents = [];

    public function mount(?int $school = null): void
    {
        $this->schoolId = $school;

        if (Auth::check()) {
            $user = Auth::user();
            $this->parentName = $user->name;
            $this->parentEmail = $user->email;
            $this->parentPhone = $user->phone ?? '';
            $this->parentWhatsapp = $user->whatsapp ?? '';
        }
    }

    public function nextStep(): void
    {
        $this->validateStep();
        $this->step = min($this->step + 1, 5);
    }

    public function previousStep(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    public function submit(ApplicationService $service): mixed
    {
        $this->validateStep();

        $application = $service->createDraft([
            'school_id' => $this->schoolId,
            'student_name' => $this->studentName,
            'student_nik' => $this->studentNik,
            'student_gender' => $this->studentGender,
            'student_birth_place' => $this->studentBirthPlace,
            'student_birth_date' => $this->studentBirthDate,
            'student_address' => $this->studentAddress,
            'previous_school' => $this->previousSchool,
            'target_jenjang' => $this->targetJenjang,
            'parent_name' => $this->parentName,
            'parent_email' => $this->parentEmail,
            'parent_phone' => $this->parentPhone,
            'parent_whatsapp' => $this->parentWhatsapp,
            'notes' => $this->notes,
        ], Auth::user());

        // Submit the draft
        $service->submit($application);

        // Create payment
        $payment = $service->createPayment($application, Auth::user());

        session()->flash('application_submitted', 'Pendaftaran berhasil diajukan!');

        return $this->redirect(route('onboarding.pay', $application), navigate: true);
    }

    private function validateStep(): void
    {
        match ($this->step) {
            1 => $this->validate(['schoolId' => 'required|exists:schools,id']),
            2 => $this->validate([
                'studentName' => 'required|string|max:255',
                'studentGender' => 'nullable|in:Laki-laki,Perempuan',
            ]),
            3 => $this->validate([
                'parentName' => 'required|string|max:255',
                'parentEmail' => 'nullable|email',
            ]),
            default => null,
        };
    }

    public function render()
    {
        $schools = School::published()->orderBy('name')->get(['id', 'name', 'city']);
        $selectedSchool = $this->schoolId ? School::find($this->schoolId) : null;

        return view('livewire.onboarding.apply-wizard', [
            'schools' => $schools,
            'selectedSchool' => $selectedSchool,
        ])->layout('layouts.app', ['title' => 'Ajukan Pendaftaran — Pesantrends']);
    }
}
