<?php

namespace App\Repositories\User;

use App\Exceptions\InvalidCredentialException;
use App\Http\Resources\AuthenticatedUserResource as AuthenticatedUser;
use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User;

    /**
     * @param int $id
     * @return User
     */
    public function showUser($id): User;

    /**
     * @param array $data
     * @return AuthenticatedUser
     */
    public function authenticateUser(array $data): AuthenticatedUser;
}
