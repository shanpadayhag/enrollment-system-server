<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthenticatedUserResource as AuthenticatedUser;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\InvalidCredentialException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class UserController extends Controller
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $user = $this->repository->createUser($request->all());

        Auth::login($user);
        $accessToken = Auth::user()->createToken('authToken')->plainTextToken;

        return response([
            'user' => $user,
            'access_token' => $accessToken
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return User
     */
    public function show($id): User
    {
        return $this->repository->showUser($id);
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

    /**
     * @param Request $request
     * @return AuthenticatedUser
     */
    public function login(Request $request): AuthenticatedUser
    {
        return $this->repository->authenticateUser($request->all());
    }

    public function verifyToken(): Authenticatable
    {
        return Auth::user();
    }
}
