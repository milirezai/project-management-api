<?php

namespace App\Http\Controllers\Api\V1\Collaboration;

use App\Http\Requests\Api\V1\Collaboration\FileRequest;
use App\Http\Resources\Api\V1\Collaboration\FileResource;
use App\Models\Collaboration\File;
use App\Notifications\Collaboration\FileCreateNotification;
use App\Notifications\Collaboration\FileUpdateNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;

class FileController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(File::class);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/files",
     *     summary="get files",
     *     tags={"File"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="creator",
     *          in="query",
     *          description="Filter by file creator",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="1"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="fileable_type",
     *           in="query",
     *           description="Filter by fileable_type ",
     *           required=false,
     *               @OA\Schema(
     *               type="string",
     *               example="task"
     *           )
     *      ),
     *          @OA\Parameter (
     *           name="type",
     *            in="query",
     *            description="Filter by file type ",
     *            required=false,
     *                @OA\Schema(
     *                type="string",
     *                example="png"
     *            )
     *       ),
     *               @OA\Parameter (
     *            name="project",
     *             in="query",
     *             description="Filter by file project ",
     *             required=false,
     *                 @OA\Schema(
     *                 type="string",
     *                 example="1"
     *             )
     *        ),
     *                    @OA\Parameter (
     *             name="task",
     *              in="query",
     *              description="Filter by file task ",
     *              required=false,
     *                  @OA\Schema(
     *                  type="string",
     *                  example="10"
     *              )
     *         ),
     *     @OA\Response(
     *         response=200,
     *         description="get files successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="path", type="string", example="file/1766405021.png"),
     *                 @OA\Property(property="type", type="string", example="png"),
     *                 @OA\Property(property="size", type="int", example="1234"),
     *                 @OA\Property(property="status", type="int", example="1"),
     *                  @OA\Property(property="creator", type="string", example="info creator"),
     *             ),
     *         ),
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
        $files = File::query();

        $request->whenFilled('creator',function ($creator) use ($files){
            $files->creator($creator);
        });

         $request->whenFilled('fileable_type',function ($fileableType) use ($files){
             $files->FileableType($fileableType);
         });

        $request->whenFilled('type',function ($type) use ($files){
            $files->type($type);
        });

        $request->whenFilled('project',function ($project) use ($files){
            $files->project($project);
        });

        $request->whenFilled('task',function ($task) use ($files){
            $files->task($task);
        });

        return FileResource::collection($files->get());
    }

    /**
     *
     * @OA\Post  (
     *     path="/api/v1/files",
     *     summary="File create",
     *     tags={"File"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="File data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"fileable_type", "fileable_id", "file"},
     *                  @OA\Property(
     *                      property="fileable_type",
     *                      type="string",
     *                      description="Submit one of the entities: task and project.",
     *                      example="task",
     *                  ),
     *                  @OA\Property(
     *                      property="fileable_id",
     *                      type="string",
     *                      description="1",
     *                      example="1",
      *                  ),
     *                  @OA\Property(
     *                      property="file",
     *                      type="file",
     *                      description="file",
     *                      example="image.png",
      *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="create file successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                      property="data",
     *                  @OA\Property(property="path", type="string", example="file/1766405021.png"),
     *                  @OA\Property(property="type", type="string", example="png"),
     *                  @OA\Property(property="size", type="int", example="1234"),
     *                  @OA\Property(property="status", type="int", example="1"),
     *                   @OA\Property(property="creator", type="string", example="info creator"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *          @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function store(FileRequest $request)
    {
        $inputs = $request->all();

        if (in_array($inputs['fileable_type'],['task','project'])){
        $inputs['fileable_type'] = $inputs['fileable_type'] === 'project' ? 'App\Models\Project\Project' : 'App\Models\Project\Task';
        $hasFileableId = $inputs['fileable_type']::find($inputs['fileable_id']);
        if (!$hasFileableId){
            throw  ValidationException::withMessages(['commentable_id' => 'This identifier was not found in the commentable_type of the table.']);
        }
        }

        $file = $request->file('file');
        $inputs['size'] = $file->getSize();
        $fileName = time().'.'.$file->getClientOriginalExtension();
        $save = $file->move(public_path('file'),$fileName);
        $filePath = 'file/'.$fileName;
        $inputs['path'] = $filePath;
        $inputs['type'] = $file->getClientOriginalExtension();
        $inputs['status'] = 1;
        $inputs['user_id'] = $request->user()->id;


        $file = File::create($inputs);

        $file->fileable->company->owner->notify(new FileCreateNotification());
        $file->user->notify(new FileCreateNotification());

        return FileResource::make($file);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/files/{file}",
     *     summary="get one file",
     *     tags={"File"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="get file successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="path", type="string", example="file/1766405021.png"),
     *                 @OA\Property(property="type", type="string", example="png"),
     *                 @OA\Property(property="size", type="int", example="1234"),
     *                 @OA\Property(property="status", type="int", example="1"),
     *                  @OA\Property(property="creator", type="string", example="info creator"),
     *             ),
     *         ),
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
    public function show(File $file)
    {
        return FileResource::make($file);
    }

    /**
     *
     * @OA\Put   (
     *     path="/api/v1/files/{file}",
     *     summary="File update",
     *     tags={"File"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="File data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      description="update type file ",
     *                      example="jpg",
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      type="string",
     *                      description="status",
     *                      example="1",
     *                      nullable=true
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="update file successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                      property="data",
     *                  @OA\Property(property="path", type="string", example="file/1766405021.png"),
     *                  @OA\Property(property="type", type="string", example="png"),
     *                  @OA\Property(property="size", type="int", example="1234"),
     *                  @OA\Property(property="status", type="int", example="1"),
     *                   @OA\Property(property="creator", type="string", example="info creator"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *          @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *     )
     * )
     */
    public function update(FileRequest $request, File $file)
    {
        $file->update($request->all());

        $file->fileable->company->owner->notify(new FileUpdateNotification());
        $file->user->notify(new FileUpdateNotification());

        return FileResource::make($file);
    }

    /**
     *
     * @OA\Delete (
     *     path="/api/v1/files/{file}",
     *     summary="delete a file",
     *     tags={"File"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="File delete successfully",
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
    public function destroy(File $file)
    {
        $file->delete();

        return response()->noContent();
    }
}
