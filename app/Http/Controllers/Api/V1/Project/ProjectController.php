<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Project\ProjectRequest;
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

        $request->whenFilled('status', function ($status) use ($projects){
            $projects->status($status);
        });

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
    public function store(ProjectRequest $request)
    {
        $inputs = $request->all();
        $inputs['creator_id'] = $request->user()->id;
        $inputs['company_id'] = $request->user()->userCompany->id;
        $project = Project::create($inputs)->load('creator');

        return ProjectResource::make($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project)
    {
        $this->queryStringName('include')
            ->request($request)
            ->model($project)
            ->include(['creator','company','tasks'])->relations(['creator','company','tasks'])
            ->typeLoading('load')
            ->relationLoad();

        return ProjectResource::make($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        return ProjectResource::make($project->load('creator'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->noContent();
    }
}
