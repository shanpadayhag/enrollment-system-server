<?php

namespace App\Repositories\AcademicYear;

use App\Exceptions\InvalidCredentialException;
use App\Http\Resources\AuthenticatedUserResource as AuthenticatedUser;
use App\Models\AcademicYear;

interface AcademicYearRepositoryInterface
{
    /**
     * @return AcademicYear
     */
    public function createIfNotExistAcademicYear(): AcademicYear;

    /**
     * @return AcademicYear
     */
    public function getCurrentAcademicYear(): AcademicYear;
}
