<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\EnrolledStudentController;
use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->group(function () {
    Route::post('/signup', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::resource('/user', UserController::class);
        Route::resource('/academic-year', AcademicYearController::class);
        Route::resource('/academic-term', AcademicTermController::class);
        Route::resource('/department', DepartmentController::class);
        Route::resource('/program', ProgramController::class);
        Route::resource('/enrolled-student', EnrolledStudentController::class);
        Route::resource('/student', StudentController::class);

        Route::get('/verify-token', [UserController::class, 'verifyToken']);
        Route::get('/set-current-academic-term/{id}', [AcademicTermController::class, 'setCurrentAcademicTerm']);
    });
});
