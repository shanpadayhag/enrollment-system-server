<?php

namespace Database\Seeders;

use App\Models\AcademicTerm;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        AcademicTerm::insert([
            [ 'term' => 'First Semester' ],
            [ 'term' => 'Second Semester' ],
            [ 'term' => 'Summer' ],
        ]);

        $department = Department::create([
            'name' => 'College of Computer Science'
        ]);

        Program::create([
            'department_id' => $department->id,
            'name' => 'Computer Science 1'
        ]);

        Program::create([
            'department_id' => $department->id,
            'name' => 'Information Technology 1'
        ]);

        Program::create([
            'department_id' => $department->id,
            'name' => 'Information System 1'
        ]);
    }
}
