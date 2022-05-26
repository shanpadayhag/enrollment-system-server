<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function validUserDataProvider(): array
    {
        return [
            [
                [
                    'username' => 'Shan',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan',
                    'password' => 'testpass123!QWE',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan123',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan',
                    'password' => 'testpass',
                    'first_name' => 'Shan Second',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag Second'
                ]
            ],
            [
                [
                    'username' => 'Shan',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => 'Has Middle Name',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan123!@#',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
        ];
    }

    public function invalidUserDataProvider(): array
    {
        return [
            [
                [
                    'username' => 'Shan ',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan 1',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan',
                    'password' => 'testpass',
                    'first_name' => 'Shan1',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan ',
                    'password' => 'testpass',
                    'first_name' => 'Shan!',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan ',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '1',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan ',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '!',
                    'last_name' => 'Padayhag'
                ]
            ],
            [
                [
                    'username' => 'Shan ',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag1'
                ]
            ],
            [
                [
                    'username' => 'Shan ',
                    'password' => 'testpass',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag!'
                ]
            ],
        ];
    }

    public function invalidLoginCredentialsProvider(): array
    {
        return [
            [
                [
                    'username' => 'CorrectUsername',
                    'password' => 'correctPassword',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ],
                [
                    'username' => 'wrongUsername',
                    'password' => 'correctPassword',
                ],
                [
                    'status_code' => 401,
                    'error_message' => 'Authentication Failed',
                ]
            ],
            [
                [
                    'username' => 'CorrectUsername',
                    'password' => 'correctPassword',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ],
                [
                    'username' => 'CorrectUsername',
                    'password' => 'wrongPassword',
                ],
                [
                    'status_code' => 401,
                    'error_message' => 'Authentication Failed',
                ]
            ],
            [
                [
                    'username' => 'CorrectUsername',
                    'password' => 'strictPassword',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ],
                [
                    'username' => 'CorrectUsername',
                    'password' => 'strIctPassword',
                ],
                [
                    'status_code' => 401,
                    'error_message' => 'Authentication Failed',
                ]
            ],
            [
                [
                    'username' => 'CorrectUsername',
                    'password' => 'correctPassword',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag'
                ],
                [
                    'username' => 'Correct Username',
                    'password' => 'correctPassword',
                ],
                [
                    'status_code' => 400,
                    'error_message' => 'Failed logging in the user',
                ]
            ],
        ];
    }

    public function invalidUserIdProvider(): array
    {
        return [
            [
                2
            ],
            [
                'a'
            ],
        ];
    }

    public function validAcademicTermDataProvider(): array
    {
        return [
            [
                [
                    'term' => 'First Semester'
                ]
            ],
            [
                [
                    'term' => '1st Semester'
                ]
            ],
            [
                [
                    'term' => '1'
                ]
            ],
        ];
    }

    public function invalidAcademicTermDataProvider(): array
    {
        return [
            [
                []
            ],
        ];
    }

    public function departmentDataProvider(): array
    {
        return [
            [
                [
                    'name' => 'College of Computer Studies'
                ]
            ],
        ];
    }

    public function invalidDepartmentDataProvider(): array
    {
        return [
            [
                []
            ],
        ];
    }

    public function validProgramDataProvider(): array
    {
        return [
            [
                [
                    'department_id' => 1,
                    'name' => 'Computer Science 3'
                ]
            ],
        ];
    }

    public function invalidProgramDataProvider(): array
    {
        return [
            [
                [
                    'department_id' => 2,
                    'name' => 'Computer Science 3'
                ],
                404
            ],
            [
                [
                    'name' => 'Computer Science 3'
                ],
                400
            ],
            [
                [
                    'department_id' => 2,
                ],
                400
            ],
        ];
    }

    public function validStudentsListDataProvider(): array
    {
        return [
            [
                [
                    [
                        'school_id' => '1',
                        'first_name' => 'Shan',
                        'middle_name' => '',
                        'last_name' => 'Padayhag',
                        'address_line_1' => '',
                        'address_line_2' => '',
                        'city' => 'Cagayan de Oro City',
                        'province' => 'Misamis Oriental',
                        'sex' => 'male',
                        'nationality' => 'Filipino',
                        'guardian' => '',
                        'guardian_number' => '',
                    ],
                    [
                        'school_id' => '2',
                        'first_name' => 'Donna',
                        'middle_name' => '',
                        'last_name' => 'Dagcuta',
                        'address_line_1' => '',
                        'address_line_2' => '',
                        'city' => 'Cagayan de Oro City',
                        'province' => 'Misamis Oriental',
                        'sex' => 'female',
                        'nationality' => 'Filipino',
                        'guardian' => '',
                        'guardian_number' => '',
                    ]
                ]
            ]
        ];
    }

    public function validStudentDataProvider(): array
    {
        return [
            [
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'male',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ],
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Donna',
                    'middle_name' => '',
                    'last_name' => 'Padayhag',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'male',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ]
            ],
            [
                [
                    'school_id' => '120220074839',
                    'first_name' => 'Shan',
                    'middle_name' => 'Middle',
                    'last_name' => 'Padayhag',
                    'address_line_1' => 'Address Line 1',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'male',
                    'nationality' => 'Filipino',
                    'guardian' => 'Mother Name',
                    'guardian_number' => '09646839102',
                ],
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Donna',
                    'middle_name' => '',
                    'last_name' => 'Dagcuta',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'female',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ]
            ],
            [
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Shan',
                    'middle_name' => 'Middle',
                    'last_name' => 'Padayhag',
                    'address_line_1' => 'Address Line 1',
                    'address_line_2' => 'With Address Line 2',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'female',
                    'nationality' => 'Filipino',
                    'guardian' => 'Mother Name',
                    'guardian_number' => '09646839102',
                ],
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Donna',
                    'middle_name' => 'Chelsea',
                    'last_name' => 'Dagcuta',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'female',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ]
            ],
            [
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'MALE',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ],
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'male',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ]
            ],
            [
                [
                    'school_id' => '20220074839',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'mAlE',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ],
                [
                    'school_id' => '20220074840',
                    'first_name' => 'Shan',
                    'middle_name' => '',
                    'last_name' => 'Padayhag',
                    'address_line_1' => '',
                    'address_line_2' => '',
                    'city' => 'Cagayan de Oro City',
                    'province' => 'Misamis Oriental',
                    'sex' => 'female',
                    'nationality' => 'Filipino',
                    'guardian' => '',
                    'guardian_number' => '',
                ]
            ],
        ];
    }
}
