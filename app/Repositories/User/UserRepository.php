<?php

namespace App\Repositories\User;

use App\Exceptions\InvalidCredentialException;
use App\Exceptions\ValidatorFailedException;
use App\Http\Resources\AuthenticatedUserResource as AuthenticatedUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @throws ValidatorFailedException|ValidationException
     */
    public function createUser(array $data): User
    {
        $validator = Validator::make($data, [
            'username' => 'required|without_spaces|unique:users,username',
            'password' => 'required',
            'first_name' => 'required|person_name',
            'middle_name' => 'nullable|person_name',
            'last_name' => 'required|person_name',
        ]);

        if ($validator->fails()) {
            throw new ValidatorFailedException('Failed creating the user', $validator->errors());
        }

        $validated = $validator->validated();

        $validated['password'] = Hash::make($validated['password']);
        $validated['middle_name'] = $validated['middle_name'] ?? null;

        return User::create($validated);
    }

    public function showUser($id): User
    {
        return User::findOrFail($id);
    }

    /**
     * @throws InvalidCredentialException|ValidatorFailedException|ValidationException
     */
    public function authenticateUser(array $data): AuthenticatedUser
    {
        $validator = Validator::make($data, [
            'username' => 'required|without_spaces',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidatorFailedException('Failed logging in the user', $validator->errors());
        }

        $validated = $validator->validated();

        if (Auth::attempt($validated)) {
            $user = Auth::user();

            return new AuthenticatedUser($user, $user->createToken('authToken')->plainTextToken);
        }

        throw new InvalidCredentialException;
    }
}
