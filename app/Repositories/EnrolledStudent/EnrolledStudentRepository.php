<?php

namespace App\Repositories\EnrolledStudent;

use App\Exceptions\ValidatorFailedException;
use App\Models\AcademicTerm;
use App\Models\AcademicYear;
use App\Models\EnrolledStudent;
use App\Models\Program;
use App\Models\Student;
use App\Repositories\AcademicTerm\AcademicTermRepositoryInterface;
use App\Repositories\AcademicYear\AcademicYearRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EnrolledStudentRepository implements EnrolledStudentRepositoryInterface
{
    private AcademicYearRepositoryInterface $academicYearRepository;

    private AcademicTermRepositoryInterface $academicTermRepository;

    public function __construct(
        AcademicYearRepositoryInterface $academicYearRepository,
        AcademicTermRepositoryInterface $academicTermRepository
    ) {
        $this->academicYearRepository = $academicYearRepository;
        $this->academicTermRepository = $academicTermRepository;
    }

    /**
     * @throws ValidationException|ValidatorFailedException
     */
    public function enrollStudentIfNotEnrolled(array $data): EnrolledStudent
    {
        $validated = $this->enrolledStudentValidation($data);

        Student::findOrFail($validated['student_id']);
        AcademicYear::findOrFail($validated['academic_year_id']);
        AcademicTerm::findOrFail($validated['academic_term_id']);
        Program::findOrFail($validated['program_id']);

        return EnrolledStudent::firstOrCreate($validated);
    }

    public function getCurrentlyEnrolledStudents(): Collection
    {
        $academicYear = $this->academicYearRepository->getCurrentAcademicYear();
        $academicTerm = Cache::get('academicTermId') ?? 1;

        return EnrolledStudent::where('academic_year_id', $academicYear->id)
            ->where('academic_term_id', $academicTerm)
            ->with(['student', 'academicYear', 'academicTerm', 'program'])
            ->get()
            ->sortBy('student.school_id')
            ->values();
    }

    /**
     * @throws ValidationException|ValidatorFailedException
     */
    private function enrolledStudentValidation(array $data): array
    {
        $validator = Validator::make($data, [
            'student_id'        => 'required',
            'academic_year_id'  => 'required',
            'academic_term_id'  => 'required',
            'program_id'        => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidatorFailedException('Failed enrolling student', $validator->errors());
        }

        return $validator->validated();
    }
}
