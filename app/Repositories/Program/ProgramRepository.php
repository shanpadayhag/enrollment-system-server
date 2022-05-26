<?php

namespace App\Repositories\Program;

use App\Exceptions\ValidatorFailedException;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class ProgramRepository implements ProgramRepositoryInterface
{
    public function addProgramIfNotExist(array $data): Program
    {
        $validated = $this->programValidation($data);

        Department::findOrFail($validated['department_id']);

        return Program::firstOrCreate($validated);
    }

    public function getAllPrograms(): Collection
    {
        return Program::all();
    }

    private function programValidation(array $data): array
    {
        $validator = Validator::make($data, [
            'department_id' => 'required',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidatorFailedException('Failed creating program', $validator->errors());
        }

        return $validator->validated();
    }
}
