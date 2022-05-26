<?php

namespace App\Repositories\Department;

use App\Models\Department;

interface DepartmentRepositoryInterface
{
    /**
     * @param array $data
     * @return Department
     */
    public function addDepartmentIfNotExist(array $data): Department;
}
