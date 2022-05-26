<?php

namespace App\Repositories\Student;

use App\Exceptions\ValidatorFailedException;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentRepository implements StudentRepositoryInterface
{
    /**
     * @throws ValidationException|ValidatorFailedException
     */
    public function createStudentIfNotExist(array $data): Student
    {
        $validated = $this->validateStudent($data);

        return Student::firstOrCreate(['school_id' => $validated['school_id']], $validated);
    }

    public function showStudentDetails($id): Student
    {
        return Student::where('school_id', $id)->firstOrFail();
    }

    public function getAllStudents(): Collection
    {
        return Student::orderBy('school_id', 'asc')
            ->get();
    }

    public function updateStudentInfo(array $data, $id): void
    {
        $validated = self::validateStudent($data, 'Failed updating the student');

        Student::find($id)->update($validated);
    }

    public function deleteStudent($id): Void
    {
        Student::findOrFail($id)->delete();
    }

    /**
     * @throws ValidationException|ValidatorFailedException
     */
    private function validateStudent(array $data, string $message = 'Failed creating the student'): array
    {
        if (array_key_exists('sex', $data)) {
            $data['sex'] = strtolower($data['sex']);
        }

        $validator = Validator::make($data, [
            'school_id'       => '',
            'first_name'      => 'required|person_name',
            'middle_name'     => 'nullable|person_name',
            'last_name'       => 'required|person_name',
            'address_line_1'  => '',
            'address_line_2'  => '',
            'city'            => 'required',
            'province'        => 'required',
            'sex'             => 'required|in:male,female',
            'nationality'     => 'required',
            'guardian'        => '',
            'guardian_number' => '',
        ]);

        if ($validator->fails()) {
            throw new ValidatorFailedException($message, $validator->errors());
        }

        return $validator->validated();
    }
}
