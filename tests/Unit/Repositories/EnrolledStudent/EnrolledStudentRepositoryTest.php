<?php

namespace Tests\Unit\Repositories\EnrolledStudent;

use App\Exceptions\ValidatorFailedException;
use App\Models\EnrolledStudent;
use App\Repositories\AcademicTerm\AcademicTermRepositoryInterface;
use App\Repositories\AcademicYear\AcademicYearRepositoryInterface;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\EnrolledStudent\EnrolledStudentRepositoryInterface;
use App\Repositories\Program\ProgramRepositoryInterface;
use App\Repositories\Student\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrolledStudentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EnrolledStudentRepositoryInterface $repository;

    private array $studentData;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repository = resolve(EnrolledStudentRepositoryInterface::class);
        $studentRepository = resolve(StudentRepositoryInterface::class);
        $academicYearRepository = resolve(AcademicYearRepositoryInterface::class);
        $academicTermRepository = resolve(AcademicTermRepositoryInterface::class);
        $departmentRepository = resolve(DepartmentRepositoryInterface::class);
        $programRepository = resolve(ProgramRepositoryInterface::class);

        $this->studentData = [
            'school_id' => '20220074839',
            'first_name' => 'Shan',
            'middle_name' => '',
            'last_name' => 'Padayhag',
            'address_line_1' => '',
            'address_line_2' => '',
            'city' => 'Cagayan de Oro City',
            'province' => 'Misamis Oriental',
            'sex' => 'male',
            'nationality' => 'Filipino',
            'guardian' => '',
            'guardian_number' => '',
        ];
        $studentRepository->createStudentIfNotExist($this->studentData);

        $academicYearRepository->createIfNotExistAcademicYear();

        $academicTermData = ['term' => 'First Semester'];
        $academicTermRepository->createAcademicTermIfNotExist($academicTermData);

        $departmentData = ['name' => 'College of Computer Studies'];
        $departmentRepository->addDepartmentIfNotExist($departmentData);

        $programData = [
            'department_id' => 1,
            'name' => 'Computer Science 3'
        ];
        $programRepository->addProgramIfNotExist($programData);
    }

    /**
     * @test
     */
    public function it_should_instantiate_correct_concrete_repository_instance()
    {
        $this->assertInstanceOf(EnrolledStudentRepositoryInterface::class, $this->repository);
    }

    /**
     * @test
     * @dataProvider validEnrolledStudentDataProvider
     */
    public function it_should_enroll_student_if_not_yet_enrolled(array $enrolledStudentData)
    {
        $enrolledStudent = $this->repository->enrollStudentIfNotEnrolled($enrolledStudentData);

        $this->assertDatabaseHas('enrolled_students', [
            'id' => 1,
            'student_id' => $enrolledStudentData['student_id'],
            'academic_year_id' => $enrolledStudentData['academic_year_id'],
            'academic_term_id' => $enrolledStudentData['academic_term_id'],
            'program_id' => $enrolledStudentData['program_id'],
        ]);
        $this->assertInstanceOf(EnrolledStudent::class, $enrolledStudent);
        $this->assertEquals(1, $enrolledStudent->id);
        $this->assertEquals($enrolledStudent['student_id'], $enrolledStudent->student_id);
        $this->assertEquals($enrolledStudent['academic_year_id'], $enrolledStudent->academic_year_id);
        $this->assertEquals($enrolledStudent['academic_term_id'], $enrolledStudent->academic_term_id);
        $this->assertEquals($enrolledStudent['program_id'], $enrolledStudent->program_id);
    }

    /**
     * @test
     * @dataProvider validEnrolledStudentDataProvider
     */
    public function it_should_retrieve_enrolled_student_details_if_student_is_enrolled(array $enrolledStudentData)
    {
        $this->repository->enrollStudentIfNotEnrolled($enrolledStudentData);

        $enrolledStudent = $this->repository->enrollStudentIfNotEnrolled($enrolledStudentData);

        $this->assertDatabaseMissing('enrolled_students', ['id' => 2]);
        $this->assertDatabaseHas('enrolled_students', [
            'id' => 1,
            'student_id' => $enrolledStudentData['student_id'],
            'academic_year_id' => $enrolledStudentData['academic_year_id'],
            'academic_term_id' => $enrolledStudentData['academic_term_id'],
            'program_id' => $enrolledStudentData['program_id'],
        ]);
        $this->assertInstanceOf(EnrolledStudent::class, $enrolledStudent);
        $this->assertEquals(1, $enrolledStudent->id);
        $this->assertEquals($enrolledStudent['student_id'], $enrolledStudent->student_id);
        $this->assertEquals($enrolledStudent['academic_year_id'], $enrolledStudent->academic_year_id);
        $this->assertEquals($enrolledStudent['academic_term_id'], $enrolledStudent->academic_term_id);
        $this->assertEquals($enrolledStudent['program_id'], $enrolledStudent->program_id);
    }

    /**
     * @test
     * @dataProvider invalidEnrolledStudentDataProvider
     */
    public function it_should_throw_exception_when_given_invalid_enrolled_student_data(array $enrolledStudentData)
    {
        $thrownException = false;

        try {
            $this->repository->enrollStudentIfNotEnrolled($enrolledStudentData);
        } catch (ValidatorFailedException $exception) {
            $this->assertInstanceOf(ValidatorFailedException::class, $exception);
            $this->assertEquals('Failed enrolling student', $exception->getMessage());
            $this->assertEquals(400, $exception->getStatusCode());

            $thrownException = true;
        } catch (ModelNotFoundException $exception) {
            $this->assertInstanceOf(ModelNotFoundException::class, $exception);

            $thrownException = true;
        }

        $this->assertTrue($thrownException);
    }

    /**
     * @test
     * @dataProvider validEnrolledStudentDataProvider
     */
    public function it_should_retrieve_currently_enrolled_students(array $enrolledStudentData)
    {
        $this->repository->enrollStudentIfNotEnrolled($enrolledStudentData);

        $enrolledStudents = $this->repository->getCurrentlyEnrolledStudents();
        $enrolledStudent = $enrolledStudents[0];
        $studentData = $this->studentData;

        $this->assertInstanceOf(Collection::class, $enrolledStudents);
        $this->assertInstanceOf(EnrolledStudent::class, $enrolledStudent);
        $this->assertEquals(1, $enrolledStudent->id);
        $this->assertEquals($enrolledStudentData['student_id'], $enrolledStudent->student_id);
        $this->assertEquals($enrolledStudentData['academic_year_id'], $enrolledStudent->academic_year_id);
        $this->assertEquals($enrolledStudentData['program_id'], $enrolledStudent->program_id);
        $this->assertEquals(1, $enrolledStudent->student->id);
        $this->assertEquals($studentData['school_id'], $enrolledStudent->student->school_id);
        $this->assertEquals($studentData['first_name'], $enrolledStudent->student->first_name);
        $this->assertEquals($studentData['middle_name'], $enrolledStudent->student->middle_name);
        $this->assertEquals($studentData['last_name'], $enrolledStudent->student->last_name);
        $this->assertEquals($studentData['address_line_1'], $enrolledStudent->student->address_line_1);
        $this->assertEquals($studentData['address_line_2'], $enrolledStudent->student->address_line_2);
        $this->assertEquals($studentData['city'], $enrolledStudent->student->city);
        $this->assertEquals($studentData['province'], $enrolledStudent->student->province);
        $this->assertEquals($studentData['sex'], $enrolledStudent->student->sex);
        $this->assertEquals($studentData['nationality'], $enrolledStudent->student->nationality);
        $this->assertEquals($studentData['guardian'], $enrolledStudent->student->guardian);
        $this->assertEquals($studentData['guardian_number'], $enrolledStudent->student->guardian_number);
    }

    public function validEnrolledStudentDataProvider(): array
    {
        return [
            [
                [
                    'student_id' => 1,
                    'academic_year_id' => 1,
                    'academic_term_id' => 1,
                    'program_id' => 1,
                ]
            ],
        ];
    }

    public function invalidEnrolledStudentDataProvider(): array
    {
        return [
            [
                [
                    'student_id' => 2,
                    'academic_year_id' => 1,
                    'academic_term_id' => 1,
                    'program_id' => 1,
                ]
            ],
            [
                [
                    'student_id' => 1,
                    'academic_year_id' => 2,
                    'academic_term_id' => 1,
                    'program_id' => 1,
                ]
            ],
            [
                [
                    'student_id' => 1,
                    'academic_year_id' => 1,
                    'academic_term_id' => 2,
                    'program_id' => 1,
                ]
            ],
            [
                [
                    'student_id' => 1,
                    'academic_year_id' => 1,
                    'academic_term_id' => 1,
                    'program_id' => 2,
                ]
            ],
            [
                [
                    'academic_year_id' => 1,
                    'academic_term_id' => 1,
                    'program_id' => 1,
                ]
            ],
            [
                [
                    'student_id' => 1,
                    'academic_term_id' => 1,
                    'program_id' => 1,
                ]
            ],
            [
                [
                    'student_id' => 1,
                    'academic_year_id' => 1,
                    'program_id' => 1,
                ]
            ],
            [
                [
                    'student_id' => 1,
                    'academic_year_id' => 1,
                    'academic_term_id' => 1,
                ]
            ],
        ];
    }
}
