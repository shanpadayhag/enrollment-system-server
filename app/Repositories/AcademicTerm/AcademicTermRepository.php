<?php

namespace App\Repositories\AcademicTerm;

use App\Exceptions\ValidatorFailedException;
use App\Models\AcademicTerm;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AcademicTermRepository implements AcademicTermRepositoryInterface
{
    /**
     * @throws ValidationException|ValidatorFailedException
     */
    public function createAcademicTermIfNotExist(array $data): AcademicTerm
    {
        $validated = $this->academicTermValidation($data);

        return AcademicTerm::firstOrCreate($validated);
    }

    public function getAllAcademicTerms(): Collection
    {
        return AcademicTerm::all();
    }

    public function getAcademicTerm($id): AcademicTerm
    {
        return AcademicTerm::findOrFail($id);
    }

    /**
     * @throws ValidationException|ValidatorFailedException
     */
    private function academicTermValidation(array $data): array
    {
        $validator = Validator::make($data, [
            'term' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidatorFailedException('Failed creating academic term', $validator->errors());
        }

        return $validator->validated();
    }
}
