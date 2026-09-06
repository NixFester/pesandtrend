<?php

namespace App\Livewire\Onboarding;

use App\Models\School;
use App\Services\ApplicationService;
use App\Services\DocumentStorageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplyWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;

    public ?int $schoolId = null;

    // Student fields
    public string $studentName = '';

    public string $studentNik = '';

    public string $studentGender = '';

    public string $studentBirthPlace = '';

    public ?string $studentBirthDate = null;

    public string $studentAddress = '';

    public string $previousSchool = '';

    public string $targetJenjang = '';

    // Parent fields
    public string $parentName = '';

    public string $parentEmail = '';

    public string $parentPhone = '';

    public string $parentWhatsapp = '';

    // Notes & Documents
    public string $notes = '';

    public $docKk = null;

    public $docAkta = null;

    public $docRapor = null;

    public $docPhoto = null;

    public array $documents = [];

    // Dropdown options
    public array $genderOptions = ['Laki-laki', 'Perempuan'];

    public array $jenjangOptions = [
        'SMPIT / Tsanawiyah',
        'SMIT / Aliyah',
        'MA / Madrasah Aliyah',
        'MTs / Madrasah Tsanawiyah',
    ];

    // Focus management
    public ?string $autofocusField = null;

    public function mount(?int $school = null): void
    {
        $this->schoolId = request()->query('school')
            ? (int) request()->query('school')
            : ($school ?? (request()->query('school_id') ? (int) request()->query('school_id') : null));

        $this->autofocusField = 'schoolId';

        // Auto-fill parent fields from authenticated user
        if (Auth::check()) {
            $user = Auth::user();
            $this->parentName = $user->name ?? '';
            $this->parentEmail = $user->email ?? '';
            $this->parentPhone = $user->phone ?? '';
            $this->parentWhatsapp = $user->whatsapp ?? '';
        }

        // Pre-fill with test data in local/dev environment
        if (config('app.debug')) {
            $this->fillTestData();
        }
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
        $this->setAutofocusField();
    }

    public function previousStep(): void
    {
        $this->step = max($this->step - 1, 1);
        $this->setAutofocusField();
    }

    private function setAutofocusField(): void
    {
        $this->autofocusField = match ($this->step) {
            1 => 'schoolId',
            2 => 'studentName',
            3 => 'parentName',
            4 => 'docKk',
            5 => null,
            default => null,
        };
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
                'studentName' => 'required|string|min:3|max:255',
                'studentGender' => 'nullable|in:Laki-laki,Perempuan',
                'studentNik' => 'nullable|digits:16',
                'studentBirthDate' => 'nullable|date',
            ]),
            3 => $this->validate([
                'parentName' => 'required|string|min:2|max:255',
                'parentEmail' => 'nullable|email|max:255',
                'parentPhone' => ['nullable', function ($attribute, $value, $fail) {
                    if ($value && ! preg_match('/^(\+62|62|0)[0-9]{9,12}$/', $value)) {
                        $fail('Format nomor telepon tidak valid (cth: 081234567890 atau +6281234567890)');
                    }
                }],
                'parentWhatsapp' => ['nullable', function ($attribute, $value, $fail) {
                    if ($value && ! preg_match('/^(\+62|62|0)[0-9]{9,12}$/', $value)) {
                        $fail('Format WhatsApp tidak valid (cth: 081234567890 atau +6281234567890)');
                    }
                }],
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

    public function saveDraft(ApplicationService $service, DocumentStorageService $documentService): void
    {
        // Basic validation for draft (at least school + student name)
        try {
            $this->validate([
                'schoolId' => 'required|exists:schools,id',
                'studentName' => 'required|string|min:3|max:255',
            ]);
        } catch (ValidationException $e) {
            // If student name is empty, go to step 2
            if (empty($this->studentName)) {
                $this->step = 2;
            }
            throw $e;
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

        session()->flash('draft_saved', 'Draft pendaftaran berhasil disimpan. Anda dapat melengkapi data nanti.');
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
