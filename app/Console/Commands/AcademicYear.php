<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\AcademicYear\AcademicYearRepositoryInterface;

class AcademicYear extends Command
{
    private AcademicYearRepositoryInterface $repository;

    public function __construct(AcademicYearRepositoryInterface $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrollment:academic-year';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a new academic year';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->repository->createIfNotExistAcademicYear();
    }
}
