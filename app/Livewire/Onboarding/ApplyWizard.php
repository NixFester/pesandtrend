<?php

namespace App\Livewire\Onboarding;

use App\Models\School;
use App\Services\ApplicationService;
use App\Services\DocumentStorageService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplyWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public ?int $schoolId = null;

    public string $studentName = 'Ahmad Rayhan Al-Fatih';

    public string $studentNik = '3273011508100001';

    public string $studentGender = 'Laki-laki';

    public string $studentBirthPlace = 'Bandung';

    public ?string $studentBirthDate = '2012-05-15';

    public string $studentAddress = 'Jl. Soekarno-Hatta No. 456, Bandung, Jawa Barat';

    public string $previousSchool = 'SDIT Al-Azhar Bandung';

    public string $targetJenjang = 'SMPIT / Tsanawiyah';

    public string $parentName = 'H. Ahmad Abdullah';

    public string $parentEmail = 'orangtua@pesantrends.id';

    public string $parentPhone = '081234567890';

    public string $parentWhatsapp = '+6281234567890';

    public string $notes = 'Mohon informasi mengenai pendaftaran asrama dan jadwal tes seleksi.';

    public $docKk = null;

    public $docAkta = null;

    public $docRapor = null;

    public $docPhoto = null;

    public array $documents = [];

    public function mount(?int $school = null): void
    {
        $this->schoolId = request()->query('school')
            ? (int) request()->query('school')
            : ($school ?? (request()->query('school_id') ? (int) request()->query('school_id') : School::published()->first()?->id));

        $this->fillTestData();
    }

    public function fillTestData(): void
    {
        if (! $this->schoolId) {
            $this->schoolId = School::published()->first()?->id;
        }

        $this->studentName = $this->studentName ?: 'Ahmad Fathoni';
        $this->studentNik = $this->studentNik ?: '3273012804100005';
        $this->studentGender = $this->studentGender ?: 'Laki-laki';
        $this->studentBirthPlace = $this->studentBirthPlace ?: 'Bandung';
        $this->studentBirthDate = $this->studentBirthDate ?: '2012-05-15';
        $this->studentAddress = $this->studentAddress ?: 'Jl. Soekarno Hatta No. 45, Bandung';
        $this->previousSchool = $this->previousSchool ?: 'SDIT Al-Azhar Bandung';
        $this->targetJenjang = $this->targetJenjang ?: 'SMPIT';

        if (Auth::check()) {
            $user = Auth::user();
            $this->parentName = $user->name ?: 'Bambang Sudirman';
            $this->parentEmail = $user->email ?: 'bambang.parent@example.com';
            $this->parentPhone = $user->phone ?: '081234567890';
            $this->parentWhatsapp = $user->whatsapp ?: '081234567890';
        } else {
            $this->parentName = $this->parentName ?: 'Bambang Sudirman';
            $this->parentEmail = $this->parentEmail ?: 'bambang.parent@example.com';
            $this->parentPhone = $this->parentPhone ?: '081234567890';
            $this->parentWhatsapp = $this->parentWhatsapp ?: '081234567890';
        }

        $this->notes = $this->notes ?: 'Mohon informasi pendaftaran program tahfidz.';
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

    public function submit(ApplicationService $service, DocumentStorageService $documentService): mixed
    {
        // Re-validate all required fields
        if (! $this->schoolId) {
            $this->step = 1;
            $this->validate(['schoolId' => 'required|exists:schools,id']);
            return null;
        }

        if (empty($this->studentName)) {
            $this->step = 2;
            $this->validate(['studentName' => 'required|string|max:255']);
            return null;
        }

        if (empty($this->parentName)) {
            $this->step = 3;
            $this->validate(['parentName' => 'required|string|max:255']);
            return null;
        }

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

        // Store uploaded documents
        $user = Auth::user();
        if ($this->docKk) {
            $documentService->store($this->docKk, $application->id, 'kk', $user?->id);
        }
        if ($this->docAkta) {
            $documentService->store($this->docAkta, $application->id, 'akta', $user?->id);
        }
        if ($this->docRapor) {
            $documentService->store($this->docRapor, $application->id, 'rapor', $user?->id);
        }
        if ($this->docPhoto) {
            $documentService->store($this->docPhoto, $application->id, 'photo', $user?->id);
        }

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
            4 => $this->validate([
                'docKk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'docAkta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'docRapor' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'docPhoto' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
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
