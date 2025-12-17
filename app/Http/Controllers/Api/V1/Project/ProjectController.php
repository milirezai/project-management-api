<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Project\ProjectResource;
use App\Models\Project\Project;
use Illuminate\Http\Request;
use App\Http\Trait\DataFiltering;


class ProjectController extends Controller
{
    use DataFiltering;
    /**
     * Display a listing of the resource.
     */
    public function index (Request $request)
    {
        $projects = Project::query();

        $this->queryStringName('include')
            ->request($request)
            ->model($projects)
            ->include(['creator','company','tasks'])->relations(['creator','company','tasks'])
            ->typeLoading('with')
            ->relationLoad();

        return ProjectResource::collection($projects->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
