<?php

namespace Tests\Unit\Repositories\Program;

use App\Exceptions\ValidatorFailedException;
use App\Models\Department;
use App\Models\Program;
use App\Repositories\Program\ProgramRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProgramRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repository = resolve(ProgramRepositoryInterface::class);

        $departmentData = [
            'name' => 'College of Computer Studies'
        ];
        Department::create($departmentData);
    }

    /**
     * @test
     */
    public function it_should_instantiate_correct_concrete_repository_instance()
    {
        $this->assertInstanceOf(ProgramRepositoryInterface::class, $this->repository);
    }

    /**
     * @test
     * @dataProvider validProgramDataProvider
     */
    public function it_should_create_program_if_not_exist(array $programData)
    {
        $program = $this->repository->addProgramIfNotExist($programData);

        $this->assertDatabaseHas('programs', [
            'id' => 1,
            'department_id' => $programData['department_id'],
            'name' => $programData['name'],
        ]);
        $this->assertInstanceOf(Program::class, $program);
        $this->assertEquals(1, $program->id);
        $this->assertEquals($programData['department_id'], $program->department_id);
        $this->assertEquals($programData['name'], $program->name);
    }

    /**
     * @test
     * @dataProvider validProgramDataProvider
     */
    public function it_should_retrieve_program_if_exist(array $programData)
    {
        $this->repository->addProgramIfNotExist($programData);

        $program = $this->repository->addProgramIfNotExist($programData);

        $this->assertDatabaseMissing('programs', ['id' => 2]);
        $this->assertDatabaseHas('programs', [
            'id' => 1,
            'department_id' => $programData['department_id'],
            'name' => $programData['name'],
        ]);
        $this->assertInstanceOf(Program::class, $program);
        $this->assertEquals(1, $program->id);
        $this->assertEquals($programData['department_id'], $program->department_id);
        $this->assertEquals($programData['name'], $program->name);
    }

    /**
     * @test
     * @dataProvider invalidProgramDataProvider
     */
    public function it_should_throw_exception_if_given_invalid_program_data(array $programData)
    {
        $thrownException = false;

        try {
            $this->repository->addProgramIfNotExist($programData);
        } catch (ValidatorFailedException $exception) {
            $this->assertInstanceOf(ValidatorFailedException::class, $exception);
            $this->assertEquals('Failed creating program', $exception->getMessage());
            $this->assertEquals(400, $exception->getStatusCode());

            $thrownException = true;
        } catch (ModelNotFoundException $exception) {
            $this->assertInstanceOf(ModelNotFoundException::class, $exception);
            $this->assertEquals(
                "No query results for model [App\\Models\\Department] {$programData['department_id']}",
                $exception->getMessage()
            );

            $thrownException = true;
        }

        $this->assertTrue($thrownException);
    }

    /**
     * @test
     * @dataProvider validProgramDataProvider
     */
    public function it_should_get_all_programs(array $programData)
    {
        $this->repository->addProgramIfNotExist($programData);

        $programs = $this->repository->getAllPrograms();
        $program = $programs[0];

        $this->assertInstanceOf(Collection::class, $programs);
        $this->assertInstanceOf(Program::class, $program);
        $this->assertEquals(1, $program->id);
        $this->assertEquals($programData['department_id'], $program->department_id);
        $this->assertEquals($programData['name'], $program->name);
    }
}