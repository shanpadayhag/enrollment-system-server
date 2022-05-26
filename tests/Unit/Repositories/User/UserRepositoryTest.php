<?php

namespace Repositories\User;

use App\Exceptions\InvalidCredentialException;
use App\Exceptions\ValidatorFailedException;
use App\Http\Resources\AuthenticatedUserResource as AuthenticatedUser;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = resolve(UserRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function it_should_instantiate_correct_concrete_repository_instance()
    {
        $this->assertInstanceOf(UserRepository::class, $this->repository);
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_should_create_user(array $userData)
    {
        $user = $this->repository->createUser($userData);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'username' => $userData['username'],
            'first_name' => $userData['first_name'],
            'middle_name' => $userData['middle_name'] ?? null,
            'last_name' => $userData['last_name'],
        ]);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['username'], $user->username);
        $this->assertEquals($userData['first_name'], $user->first_name);
        $this->assertEquals($userData['middle_name'] ?? null, $user->middle_name);
        $this->assertEquals($userData['last_name'], $user->last_name);
        $this->assertTrue(Hash::check($userData['password'], $user->password));
    }

    /**
     * @test
     * @dataProvider invalidUserDataProvider
     */
    public function it_should_throw_an_exception_when_given_invalid_user_data(array $userData)
    {
        try {
            $this->repository->createUser($userData);
        } catch (ValidatorFailedException $exception) {
            $this->assertInstanceOf(ValidatorFailedException::class, $exception);
            $this->assertEquals('Failed creating the user', $exception->getMessage());
            $this->assertEquals(400, $exception->getStatusCode());
        }
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_should_authenticate_valid_user(array $data)
    {
        $this->repository->createUser($data);

        $auth = $this->repository->authenticateUser([
            'username' => $data['username'],
            'password' => $data['password'],
        ]);

        $userArray = $auth->toArray();

        $this->assertInstanceOf(AuthenticatedUser::class, $auth);
        $this->assertArrayHasKey('user', $userArray);
        $this->assertArrayHasKey('access_token', $userArray);

        $this->assertEquals($data['first_name'], $userArray['user']['first_name']);
        $this->assertEquals($data['middle_name'] ?? null, $userArray['user']['middle_name']);
        $this->assertEquals($data['last_name'], $userArray['user']['last_name']);
        $this->assertEquals($data['username'], $userArray['user']['username']);
    }

    /**
     * @test
     * @dataProvider invalidLoginCredentialsProvider
     */
    public function it_should_throw_invalid_credentials_exception_when_invalid_login_is_provided(
        array $userData,
        array $loginData
    ) {
        $this->repository->createUser($userData);

        try {
            $this->repository->authenticateUser($loginData);
        } catch (InvalidCredentialException $exception) {
            $this->assertInstanceOf(InvalidCredentialException::class, $exception);
            $this->assertEquals('Authentication Failed', $exception->getMessage());
            $this->assertEquals(401, $exception->getStatusCode());
        } catch (ValidatorFailedException $exception) {
            $this->assertInstanceOf(ValidatorFailedException::class, $exception);
            $this->assertEquals('Failed logging in the user', $exception->getMessage());
            $this->assertEquals(400, $exception->getStatusCode());
        }
    }

    /**
     * @test
     * @dataProvider validUserDataProvider
     */
    public function it_should_show_user_details(array $userData)
    {
        $this->repository->createUser($userData);

        $user = $this->repository->showUser(1);

        $this->assertEquals(1, $user->id);
        $this->assertEquals($userData['username'], $user->username);
        $this->assertEquals($userData['first_name'], $user->first_name);
        $this->assertEquals($userData['middle_name'] ?? null, $user->middle_name);
        $this->assertEquals($userData['last_name'], $user->last_name);
    }

    /**
     * @test
     * @dataProvider invalidUserIdProvider
     */
    public function it_should_throw_an_exception_when_user_not_found($id)
    {
        try {
            $user = $this->repository->showUser($id);
        } catch (ModelNotFoundException $exception) {
            $this->assertInstanceOf(ModelNotFoundException::class, $exception);
            $this->assertEquals(
                "No query results for model [App\\Models\\User] {$id}",
                $exception->getMessage()
            );
        }
    }
}
