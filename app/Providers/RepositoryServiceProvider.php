<?php

namespace App\Providers;

use App\Repositories\AcademicTerm\AcademicTermRepository;
use App\Repositories\AcademicTerm\AcademicTermRepositoryInterface;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\EnrolledStudent\EnrolledStudentRepository;
use App\Repositories\EnrolledStudent\EnrolledStudentRepositoryInterface;
use App\Repositories\Program\ProgramRepository;
use App\Repositories\Program\ProgramRepositoryInterface;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\AcademicYear\AcademicYearRepository;
use App\Repositories\AcademicYear\AcademicYearRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            AcademicYearRepositoryInterface::class,
            AcademicYearRepository::class
        );

        $this->app->bind(
            StudentRepositoryInterface::class,
            StudentRepository::class
        );

        $this->app->bind(
            EnrolledStudentRepositoryInterface::class,
            EnrolledStudentRepository::class
        );

        $this->app->bind(
            AcademicTermRepositoryInterface::class,
            AcademicTermRepository::class
        );

        $this->app->bind(
            DepartmentRepositoryInterface::class,
            DepartmentRepository::class
        );

        $this->app->bind(
            ProgramRepositoryInterface::class,
            ProgramRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
