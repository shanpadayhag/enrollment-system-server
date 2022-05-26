<?php

namespace App\Repositories\AcademicYear;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\Log;

class AcademicYearRepository implements AcademicYearRepositoryInterface
{
    public function createIfNotExistAcademicYear(): AcademicYear
    {
        return AcademicYear::firstOrCreate([
            'scope' => date('Y') . '-' . date('Y', strtotime('+1 year'))
            ]
        );
    }

    public function getCurrentAcademicYear(): AcademicYear
    {
        return AcademicYear::orderByDesc('id')
            ->firstOrFail();
    }
}
