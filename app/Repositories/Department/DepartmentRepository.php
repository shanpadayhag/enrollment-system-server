<?php

namespace App\Repositories\Department;

use App\Exceptions\ValidatorFailedException;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    /**
     * @throws ValidationException|ValidatorFailedException
     */
    public function addDepartmentIfNotExist(array $data): Department
    {
        $validated = $this->departmentValidation($data);

        return Department::firstOrCreate($validated);
    }

    /**
     * @throws ValidationException|ValidatorFailedException
     */
    private function departmentValidation(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidatorFailedException('Failed creating department', $validator->errors());
        }

        return $validator->validated();
    }
}
