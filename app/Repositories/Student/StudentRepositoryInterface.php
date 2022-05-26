<?php

namespace App\Repositories\Student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface StudentRepositoryInterface
{
    /**
     * @param array $data
     * @return Student
     */
    public function createStudentIfNotExist(array $data): Student;

    /**
     * @param $id
     * @return Student
     */
    public function showStudentDetails($id): Student;

    /**
     * @return Collection
     */
    public function getAllStudents(): Collection;

    /**
     * @param array $data
     * @param $id
     * @return void
     */
    public function updateStudentInfo(array $data, $id): void;

    /**
     * @param $id
     * @return Void
     */
    public function deleteStudent($id): Void;
}
