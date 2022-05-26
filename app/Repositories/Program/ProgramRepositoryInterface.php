<?php

namespace App\Repositories\Program;

use App\Models\Program;
use Illuminate\Database\Eloquent\Collection;

interface ProgramRepositoryInterface
{
    /**
     * @param array $data
     * @return Program
     */
    public function addProgramIfNotExist(array $data): Program;

    /**
     * @return Collection
     */
    public function getAllPrograms(): Collection;
}
