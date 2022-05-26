<?php

namespace App\Repositories\AcademicTerm;

use App\Models\AcademicTerm;
use Illuminate\Database\Eloquent\Collection;

interface AcademicTermRepositoryInterface
{
    /**
     * @param array $data
     * @return AcademicTerm
     */
    public function createAcademicTermIfNotExist(array $data): AcademicTerm;

    /**
     * @return Collection
     */
    public function getAllAcademicTerms(): Collection;

    /**
     * @param $id
     * @return AcademicTerm
     */
    public function getAcademicTerm($id): AcademicTerm;
}
