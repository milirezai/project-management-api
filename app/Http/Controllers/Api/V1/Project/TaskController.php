<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Project\TaskRequest;
use App\Http\Resources\Api\V1\Project\TaskResource;
use App\Http\Trait\DataFiltering;
use App\Models\Project\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use DataFiltering;

    /**
     *
     * @OA\Get (
     *     path="/api/v1/tasks",
     *     summary="get tasks",
     *     tags={"Task"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="project,assignee,creator,comments,files"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get tasks successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="title", type="string", example="register event "),
     *                 @OA\Property(property="description", type="string", example="fix bug in register event"),
     *                  @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     * *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *                 @OA\Property(property="priority", type="string", example="important"),
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
    public function index(Request $request)
    {
        $tasks = Task::query();

        $this->loadingRelationFromRequest(
            model: $tasks, request: $request,
            includes: ['project','assignee','creator','comments','files'], relations: ['project','assignee','creator','comments','files']
        );

        return TaskResource::collection($tasks->get());
    }

    /**
     *
     * @OA\Post  (
     *     path="/api/v1/tasks",
     *     summary="Task create",
     *     tags={"Task"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Task data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"title", "start_date", "end_date", "project_id","user_id"},
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="task title",
     *                      example="bog fix",
     *                      maxLength=100,
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description for task",
     *                      example="this is a description for task",
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
     *                       nullable=true
     *                   ),
     *                 @OA\Property(
     *                        property="project_id",
     *                        description="project id ",
     *                        type="int",
     *                        example="1",
     *                    ),
     *                      @OA\Property(
     *                         property="user_id",
     *                         description="The person who must perform the task.",
     *                         type="int",
     *                         example="1",
     *                     ),
     *              )
     *          )
     *      ),
     *          @OA\Response(
     *          response=201,
     *          description="create task successfully",
     *              @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  @OA\Property(property="title", type="string", example="register event "),
     *                  @OA\Property(property="description", type="string", example="fix bug in register event"),
     *                   @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     *  *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *                  @OA\Property(property="priority", type="string", example="important"),
     *                @OA\Property(
     *                  property="creator",
     *                  @OA\Property(property="first_name", type="string", example="milad"),
     *                  @OA\Property(property="last_name", type="string", example="rezai"),
     *                  @OA\Property(property="mobile", type="string", example="09167516826"),
     *                  @OA\Property(property="profile_photo_path", type="string", example="images/users/2025/12/01/1764590305.png"),
     *              ),
     *              ),
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function store(TaskRequest $request)
    {
        $inputs = $request->all();
        $inputs['creator_id'] = $request->user()->id;
        $task = Task::create($inputs)->load('creator');

        return TaskResource::make($task);
    }


    /**
     *
     * @OA\Get (
     *     path="/api/v1/tasks/{task}",
     *     summary="get task",
     *     tags={"Task"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="project,assignee,creator,comments,files"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get task successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="title", type="string", example="register event "),
     *                 @OA\Property(property="description", type="string", example="fix bug in register event"),
     *                  @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     * *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *                 @OA\Property(property="priority", type="string", example="important"),
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
    public function show(Request $request, Task $task)
    {
        $this->loadingRelationFromRequest(
            model: $task, request: $request,
            includes: ['project','assignee','creator','comments','files'], relations: ['project','assignee','creator','comments','files']
            ,relationLoadingMode: 'load'
        );

        return TaskResource::make($task);
    }

    /**
     *
     * @OA\Put   (
     *     path="/api/v1/tasks/{task}",
     *     summary="Task update",
     *     tags={"Task"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Task update",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="task title",
     *                      example="bog fix",
     *                      maxLength=100,
     *                       nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description for task",
     *                      example="this is a description for task",
     *                      maxLength=400,
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="start_date",
     *                      type="date",
     *                      description="project date start",
     *                      example="1998-07-28 00:56:01",
     *                       nullable=true
     *                  ),
     *                  @OA\Property(
     *                       property="end_date",
     *                       type="date",
     *                       description="project date start",
     *                       example="1998-09-28 00:56:01",
     *                       nullable=true
     *                   ),
     *                       @OA\Property(
     *                       property="status",
     *                       type="boolean",
     *                       example="1",
     *                       nullable=true
     *                   ),
     *                 @OA\Property(
     *                        property="project_id",
     *                        description="project id ",
     *                        type="int",
     *                        example="1",
     *                         nullable=true
     *                    ),
     *                      @OA\Property(
     *                         property="user_id",
     *                         description="The person who must perform the task.",
     *                         type="int",
     *                         example="1",
     *                          nullable=true
     *                     ),
     *              )
     *          )
     *      ),
     *          @OA\Response(
     *          response=200,
     *          description="create task successfully",
     *              @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  @OA\Property(property="title", type="string", example="register event "),
     *                  @OA\Property(property="description", type="string", example="fix bug in register event"),
     *                   @OA\Property(property="start_date", type="date", example="1998-07-28 00:56:01"),
     *  *                  @OA\Property(property="end_date", type="date", example="2009-08-26 23:51:20"),
     *                  @OA\Property(property="priority", type="string", example="important"),
     *                @OA\Property(
     *                  property="creator",
     *                  @OA\Property(property="first_name", type="string", example="milad"),
     *                  @OA\Property(property="last_name", type="string", example="rezai"),
     *                  @OA\Property(property="mobile", type="string", example="09167516826"),
     *                  @OA\Property(property="profile_photo_path", type="string", example="images/users/2025/12/01/1764590305.png"),
     *              ),
     *              ),
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->all());

        return TaskResource::make($task->load('creator'));
    }

    /**
     *
     * @OA\Delete (
     *     path="/api/v1/tasks/{task}",
     *     summary="delete a task",
     *     tags={"Task"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="Task delete successfully",
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
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }
}
// 1|edYMGUOvgg1A7n6DMuE22qXqhkrbv0MHqp1Kg37Xe3b50e49
