<?php

namespace Tests\Feature;

use App\Repositories\AcademicYear\AcademicYearRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repositories\User\UserRepositoryInterface;

class AcademicYearControllerTest extends TestCase
{
    use RefreshDatabase;

    private AcademicYearRepositoryInterface $repository;

    private UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = resolve(UserRepositoryInterface::class);
        $this->repository = resolve(AcademicYearRepositoryInterface::class);
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_should_create_if_not_exist_an_academic_year(array $userData)
    {
        $this->userRepository->createUser($userData);
        $auth = $this->userRepository->authenticateUser($userData);
        $accessToken = $auth->accessToken;

        $response = $this->post('/api/v1/academic-year', [], [
            'accept' => 'application/json',
            'Authorization' => "Bearer {$accessToken}"
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "scope",
            "created_at",
            "updated_at",
            "id",
        ]);
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_should_get_if_an_academic_year_exist(array $userData)
    {
        $this->userRepository->createUser($userData);
        $auth = $this->userRepository->authenticateUser($userData);
        $accessToken = $auth->accessToken;

        $this->repository->createIfNotExistAcademicYear();

        $response = $this->post('/api/v1/academic-year', [], [
            'accept' => 'application/json',
            'Authorization' => "Bearer {$accessToken}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "id",
            "scope",
            "created_at",
            "updated_at",
            'deleted_at',
        ]);
    }
}
