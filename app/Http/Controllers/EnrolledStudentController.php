<?php

namespace App\Http\Controllers;

use App\Models\EnrolledStudent;
use App\Repositories\AcademicTerm\AcademicTermRepositoryInterface;
use App\Repositories\AcademicYear\AcademicYearRepositoryInterface;
use App\Repositories\EnrolledStudent\EnrolledStudentRepositoryInterface;
use App\Repositories\Program\ProgramRepositoryInterface;
use App\Repositories\Student\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EnrolledStudentController extends Controller
{
    private EnrolledStudentRepositoryInterface $repository;

    private StudentRepositoryInterface $studentRepository;

    private AcademicYearRepositoryInterface $academicYearRepository;

    private AcademicTermRepositoryInterface $academicTermRepository;

    private ProgramRepositoryInterface $programRepository;

    public function __construct(
        EnrolledStudentRepositoryInterface $repository,
        StudentRepositoryInterface $studentRepository,
        AcademicYearRepositoryInterface $academicYearRepository,
        AcademicTermRepositoryInterface $academicTermRepository,
        ProgramRepositoryInterface $programRepository,
    ) {
        $this->repository = $repository;
        $this->studentRepository = $studentRepository;
        $this->academicYearRepository = $academicYearRepository;
        $this->academicTermRepository = $academicTermRepository;
        $this->programRepository = $programRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return $this->repository->getCurrentlyEnrolledStudents();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return array
     */
    public function create(Request $request): array
    {
        $data = $request->all();

        $schoolId = $data['school_id'] ?? null;
        $academicTermId = Cache::get('academicTermId') ?? 1;

        $academicYear = $this->academicYearRepository->getCurrentAcademicYear();
        $academicTerm = $this->academicTermRepository->getAcademicTerm($academicTermId);
        $programs = $this->programRepository->getAllPrograms();

        try {
            $student = $this->studentRepository->showStudentDetails($schoolId);
        } catch (ModelNotFoundException $exception) {
            $student = null;
        }

        return [
            'academic_term' => $academicTerm,
            'academic_year' => $academicYear,
            'programs' => $programs,
            'student' => $student,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return EnrolledStudent
     */
    public function store(Request $request): EnrolledStudent
    {
        $data = $request->all();

        $student = $this->studentRepository->createStudentIfNotExist($data);

        $data['student_id'] = $student->id;

        return $this->repository->enrollStudentIfNotEnrolled($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
