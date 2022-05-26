<?php

namespace Tests\Feature;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public UserRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = resolve(UserRepositoryInterface::class);
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_can_create_a_user(array $userData)
    {
        $response = $this->post('/api/v1/signup', $userData, [
            'accept' => 'application/json'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'user' => [
                'username',
                'first_name',
                'middle_name',
                'last_name',
                'updated_at',
                'created_at',
                'id',
            ],
            'access_token'
        ]);
    }

    /**
     * @test
     */
    public function it_should_login_a_user()
    {
        $this->repository->createUser([
            'username' => 'Shan',
            'password' => 'testpass',
            'first_name' => 'Shan',
            'last_name' => 'Padayhag',
        ]);

        $response = $this->post('/api/v1/login', [
            'username' => 'Shan',
            'password' => 'testpass'
        ], [
            'accept' => 'application/json'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                'username',
                'first_name',
                'middle_name',
                'last_name',
                'updated_at',
                'created_at',
                'id',
            ],
            'access_token'
        ]);
    }

    /**
     * @test
     * @dataProvider invalidLoginCredentialsProvider
     */
    public function it_should_respond_an_error_when_login_failed(
        $userData,
        $loginData,
        $responseData
    ) {
        $this->repository->createUser($userData);

        $response = $this->post('/api/v1/login', $loginData, [
            'accept' => 'application/json'
        ]);

        $response->assertStatus($responseData['status_code']);
        $response->assertJsonFragment([
            'message' => $responseData['error_message'],
        ]);
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_can_show_user(array $userData)
    {
        $this->repository->createUser($userData);
        $auth = $this->repository->authenticateUser($userData);
        $accessToken = $auth->accessToken;

        $response = $this->get('/api/v1/user/1', [
            'accept' => 'application/json',
            'Authorization' => "Bearer {$accessToken}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "id",
            "username",
            "first_name",
            "middle_name",
            "last_name",
            "created_at",
            "updated_at"
        ]);
    }

    /**
     * @test
     * @dataProvider invalidUserIdProvider
     */
    public function it_should_respond_with_error_when_user_is_not_found($id)
    {
        $userData = [
            'username' => 'Shan',
            'password' => 'testpass',
            'first_name' => 'Shan',
            'middle_name' => '',
            'last_name' => 'Padayhag'
        ];

        $this->repository->createUser($userData);
        $auth = $this->repository->authenticateUser($userData);
        $accessToken = $auth->accessToken;

        $response = $this->get('/api/v1/user/$id', [
            'accept' => 'application/json',
            'Authorization' => "Bearer {$accessToken}"
        ]);

        $response->assertStatus(404);
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_should_return_user_when_validating_token(array $userData)
    {
        $this->repository->createUser($userData);
        $auth = $this->repository->authenticateUser($userData);

        $response = $this->get('/api/v1/verify-token', [
            'accept' => 'application/json',
            'authorization' => "Bearer {$auth->accessToken}"
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'username',
            'first_name',
            'middle_name',
            'last_name',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }
}
