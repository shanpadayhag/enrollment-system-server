<?php

namespace App\Repositories\EnrolledStudent;

use App\Models\EnrolledStudent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface EnrolledStudentRepositoryInterface
{
    /**
     * @param array $data
     * @return EnrolledStudent
     */
    public function enrollStudentIfNotEnrolled(array $data): EnrolledStudent;

    /**
     * @return Collection
     */
    public function getCurrentlyEnrolledStudents(): Collection;
}
