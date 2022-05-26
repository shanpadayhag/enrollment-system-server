<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Repositories\AcademicTerm\AcademicTermRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AcademicTermController extends Controller
{
    private AcademicTermRepositoryInterface $repository;

    public function __construct(AcademicTermRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return [
            'academic_terms' => $this->repository->getAllAcademicTerms(),
            'current_academic_term' => Cache::get('academicTermId') ?? 1
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return AcademicTerm
     */
    public function store(Request $request): AcademicTerm
    {
        return $this->repository->createAcademicTermIfNotExist($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id
     * @return void
     */
    public function setCurrentAcademicTerm($id): void
    {
        Cache::put('academicTermId', $id);
    }
}
