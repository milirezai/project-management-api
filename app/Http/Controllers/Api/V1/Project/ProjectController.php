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
     *              example="creator,company,tasks,files,comments"
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
            includes: ['creator','company','tasks','comments','files'], relations: ['creator','company','tasks','comments','files']
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
     *                      type="string",
     *                      description="description for project",
     *                      example="digikala is the largest online store in Iran.",
     *                      maxLength=400,
     *                      nullable=true
     *                  ),
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
        $inputs['creator_id'] = $request->user()->id;
        $inputs['company_id'] = $request->user()->userCompany->id;
        $project = Project::create($inputs)->load('creator');

        return ProjectResource::make($project);
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
     *              example="creator,company,tasks,files,comments"
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
               includes: ['creator','company','tasks','comments','files'], relations: ['creator','company','tasks','comments','files'], relationLoadingMode: 'load'
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
