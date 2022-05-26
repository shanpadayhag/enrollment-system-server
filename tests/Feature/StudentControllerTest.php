<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Repositories\Student\StudentRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    private StudentRepositoryInterface $repository;

    public function setup(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repository = resolve(StudentRepositoryInterface::class);
        $userRepository = resolve(UserRepositoryInterface::class);

        $userData = [
            'username' => 'Shan',
            'password' => 'testpass',
            'first_name' => 'Shan',
            'middle_name' => '',
            'last_name' => 'Padayhag'
        ];
        $userRepository->createUser($userData);
        $this->auth = $userRepository->authenticateUser($userData);
    }

    /**
     * @test
     * @dataProvider validStudentDataProvider
     */
    public function it_should_store_new_student(array $studentData)
    {
        $response = $this->post('/api/v1/student', $studentData, [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "school_id",
            "first_name",
            "middle_name",
            "last_name",
            "address_line_1",
            "address_line_2",
            "city",
            "province",
            "sex",
            "nationality",
            "guardian",
            "guardian_number",
            "updated_at",
            "created_at",
            "id",
        ]);
    }

     /**
     * @test
     * @dataProvider invalidStudentDataProvider
     */
    public function it_should_fail_storing_new_student(array $studentData, int $status)
    {
        $response = $this->post('/api/v1/student', $studentData, [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus($status);
    }

    /**
     * @test
     * @dataProvider validStudentDataProvider
     */
    public function it_should_get_existing_student(array $studentData)
    {
        $this->repository->createStudentIfNotExist($studentData);

        $response = $this->post('/api/v1/student/', $studentData, [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "school_id",
            "first_name",
            "middle_name",
            "last_name",
            "address_line_1",
            "address_line_2",
            "city",
            "province",
            "sex",
            "nationality",
            "guardian",
            "guardian_number",
            "updated_at",
            "created_at",
            "id",
        ]);
    }

    /**
     * @test
     * @dataProvider validStudentDataProvider
     */
    public function it_should_retrieve_student_details(array $studentData)
    {
        $student = $this->repository->createStudentIfNotExist($studentData);

        $response = $this->get("/api/v1/student/{$studentData['school_id']}", [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'school_id',
            'first_name',
            'middle_name',
            'last_name',
            'address_line_1',
            'address_line_2',
            'city',
            'province',
            'sex',
            'nationality',
            'guardian',
            'guardian_number',
            'updated_at',
            'created_at',
            'deleted_at',
        ]);
    }

   /**
    * @test
    */
   public function it_should_respond_with_error_when_invalid_student_id_is_supplied()
   {
        $response = $this->get('/api/v1/student/1', [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus(404);
   }

    /**
     * @test
     * @dataProvider validStudentsListDataProvider
     */
    public function it_should_retrieve_students_list(array $studentsListData)
    {
        foreach ($studentsListData as $studentData) {
            $this->repository->createStudentIfNotExist($studentData);
        }

        $response = $this->get('/api/v1/student', [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            0 => [
                'id',
                'school_id',
                'first_name',
                'middle_name',
                'last_name',
                'address_line_1',
                'address_line_2',
                'city',
                'province',
                'sex',
                'nationality',
                'guardian',
                'guardian_number',
                'updated_at',
                'created_at',
                'deleted_at',
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider validStudentDataProvider
     */
    public function it_can_update_student(array $studentData, $modifiedStudentData)
    {
        $this->repository->createStudentIfNotExist($studentData);

        $response = $this->put('/api/v1/student/1', $modifiedStudentData, [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     * @dataProvider validStudentDataProvider
     */
    public function it_should_delete_single_student(array $studentData)
    {
        $student = $this->repository->createStudentIfNotExist($studentData);

        $response = $this->delete('/api/v1/student/1', [
            'accept' => 'application/json',
            'authorization' => "Bearer {$this->auth->accessToken}"
        ]);

        $response->assertStatus(200);
        $this->assertSoftDeleted($student);
    }

    public function invalidStudentDataProvider(): array
    {
        return [
            [
                [
                    'school_id' => '20220074840',
                    'first_name' => 'Shan Number 3',
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
                ],
                400
            ],
            [
                [
                    'school_id' => 'abcdefg',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag_<3',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'male',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ],
                400
            ],
            [
                [
                    'school_id' => '20220074840',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'chicken',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ],
                400
            ],
        ];
    }
}
