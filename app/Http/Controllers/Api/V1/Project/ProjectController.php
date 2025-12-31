<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Events\Project\CreateProject;
use App\Http\Requests\Api\V1\Project\ProjectRequest;
use App\Http\Resources\Api\V1\Project\ProjectResource;
use App\Models\Project\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Trait\DataFiltering;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;

class ProjectController extends  Controller
{
    use DataFiltering, AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Project::class);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/projects",
     *     summary="Get projects",
     *     tags={"Project"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="creator,company,tasks,files,comments,members"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get projects successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala-api"),
     *                  @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     *                  @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function index (Request $request)
    {
        $projects = Project::query();

        $request->whenFilled('status', function ($status) use ($projects){
            $projects->status($status);
        });

        $this->loadingRelationFromRequest(
            model: $projects, request: $request,
            includes: ['creator','company','tasks','comments','files','members'],
            relations: ['creator','company','tasks','comments','files','members']
        );

        return ProjectResource::collection($projects->get());
    }

    /**
     *
     * @OA\Post  (
     *     path="/api/v1/projects",
     *     summary="Project create",
     *     tags={"Project"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Project data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name", "start_date", "end_date"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="project name",
     *                      example="digikala-api",
     *                      maxLength=100,
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      description="description for project",
     *                      example="digikala is the largest online store in Iran.",
     *                      maxLength=400,
     *                      nullable=true
     *                  ),
     *                       @OA\Property(
     *                       property="members",
     *                       type="string",
     *                       description="members id for project",
     *                       example="[1,2,3,4,5]"
     *                   ),
     *                  @OA\Property(
     *                      property="start_date",
     *                      type="date",
     *                      description="project date start",
     *                      example="1998-07-28 00:56:01",
     *                  ),
     *                  @OA\Property(
     *                       property="end_date",
     *                       type="date",
     *                       description="project date start",
     *                       example="1998-09-28 00:56:01",
     *                   ),
     *                       @OA\Property(
     *                       property="status",
     *                       type="boolean",
     *                       example="1",
     *                   ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="create project successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala-api"),
     * *                  @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     * *                  @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     * *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function store(ProjectRequest $request)
    {
        $inputs = $request->all();
        $members = $inputs['members'];
        $companyOwner = $request->user()->ownedCompany->owner->id;
        $projectCreator = $request->user()->id;

        $inputs['creator_id'] = $projectCreator;
        $inputs['company_id'] = $companyOwner;
        $project = Project::create($inputs);

        $project ->members()->sync($members);


        $users = [$members,$companyOwner,$projectCreator];

        event(new CreateProject($users));

        return ProjectResource::make($project->load('creator'));
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/projects/{project}",
     *     summary="Get project",
     *     tags={"Project"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="creator,company,tasks,files,comments,members"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get project successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala-api"),
     *                  @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     *                  @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function show(Request $request, Project $project)
    {
           $this->loadingRelationFromRequest(
            model: $project, request: $request,
               includes: ['creator','company','tasks','comments','files','members'],
            relations: ['creator','company','tasks','comments','files','members'], relationLoadingMode: 'load'
        );

        return ProjectResource::make($project);
    }

    /**
     *
     * @OA\Put  (
     *     path="/api/v1/projects/{project}",
     *     summary="Project update",
     *     tags={"Project"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Project update",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="project name",
     *                      example="digikala-api",
     *                      maxLength=100,
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description for project",
     *                      example="digikala is the largest online store in Iran.",
     *                      maxLength=400,
     *                      nullable=true,
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="start_date",
     *                      type="date",
     *                      description="project date start",
     *                      example="1998-07-28 00:56:01",
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                       property="end_date",
     *                       type="date",
     *                       description="project date start",
     *                       example="1998-09-28 00:56:01",
     *                        nullable=true
     *                   ),
     *                     @OA\Property(
     *                        property="members",
     *                        description="update members id for project",
     *                        example="[1,2,3,4,5,6,7,8]",
     *                        nullable=true
     *                    ),
     *                       @OA\Property(
     *                       property="status",
     *                       type="boolean",
     *                       example="1",
     *                       nullable=true
     *                   ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="update project successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala-api"),
     * *                  @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     * *                  @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     * *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->all());

        $project->members()->sync($request->members);

        return ProjectResource::make($project->load('creator'));
    }

    /**
     *
     * @OA\Delete (
     *     path="/api/v1/projects/{project}",
     *     summary="delete a project",
     *     tags={"Project"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="project delete successfully",
     *     ),
     *     @OA\Response(
     *           response=401,
     *           description="Unauthenticated",
     *       ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->noContent();
    }
}
