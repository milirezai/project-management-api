<?php

namespace App\Http\Controllers\Api\V1\Collaboration;

use App\Http\Requests\Api\V1\Collaboration\CommentRequest;
use App\Http\Resources\Api\V1\Collaboration\CommentResource;
use App\Models\Collaboration\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/comments",
     *     summary="get comments",
     *     tags={"Comment"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="creator",
     *          in="query",
     *          description="Filter by comment creator",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="1"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="commentable_type",
     *           in="query",
     *           description="Filter by commentable_type ",
     *           required=false,
     *               @OA\Schema(
     *               type="string",
     *               example="task"
     *           )
     *      ),
     *               @OA\Parameter (
     *            name="project",
     *             in="query",
     *             description="Filter by comment project ",
     *             required=false,
     *                 @OA\Schema(
     *                 type="string",
     *                 example="1"
     *             )
     *        ),
     *                    @OA\Parameter (
     *             name="task",
     *              in="query",
     *              description="Filter by comment task ",
     *              required=false,
     *                  @OA\Schema(
     *                  type="string",
     *                  example="10"
     *              )
     *         ),
     *     @OA\Response(
     *         response=200,
     *         description="get comments successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="body", type="string", example="this is a body for comment"),
     *                 @OA\Property(property="status", type="int", example="1"),
     *                  @OA\Property(property="author", type="string", example="info author"),
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
        $comments = Comment::query();

        $request->whenFilled('creator',function ($creator) use ($comments) {
            $comments->creator($creator);
        } );

        $request->whenFilled('commentable_type',function ($commentableType) use ($comments){
            $comments->commentableType($commentableType);
        });

        $request->whenFilled('project',function ($project) use ($comments){
            $comments->project($project);
        });

        $request->whenFilled('task',function ($task) use ($comments){
            $comments->task($task);
        });

        return CommentResource::collection($comments->get());
    }

    /**
     *
     * @OA\Post  (
     *     path="/api/v1/comments",
     *     summary="Comment create",
     *     tags={"Comment"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Comment data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"commentable_type", "commentable_id", "body"},
     *                  @OA\Property(
     *                      property="commentable_type",
     *                      type="string",
     *                      description="Submit one of the entities: task and project.",
     *                      example="task",
     *                  ),
     *                  @OA\Property(
     *                      property="commentable_id",
     *                      type="string",
     *                      description="1",
     *                      example="1",
     *                  ),
     *                  @OA\Property(
     *                      property="body",
     *                      type="string",
     *                      description="body",
     *                      example="this is a body for commnet",
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="create comment successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                      property="data",
     *                 @OA\Property(property="body", type="string", example="this is a body for comment"),
     *                  @OA\Property(property="status", type="int", example="1"),
     *                   @OA\Property(property="author", type="string", example="info author"),
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
    public function store(CommentRequest $request)
    {
        $inputs = $request->all();

        if (in_array($inputs['commentable_type'],['task','project'])){
            $inputs['commentable_type'] = $inputs['commentable_type'] === 'project' ? 'App\Models\Project\Project' : 'App\Models\Project\Task';
            $hasCommentableId = $inputs['commentable_type']::find($inputs['commentable_id']);
            if (!$hasCommentableId){
                throw  ValidationException::withMessages(['commentable_id' => 'This identifier was not found in the commentable_type of the table.']);
            }
        }

        $inputs['status'] = 1;
        $inputs['author_id'] = $request->user()->id;

        $comment = Comment::create($inputs);

        return CommentResource::make($comment);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/comments/{comment}",
     *     summary="get comment",
     *     tags={"Comment"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="get comment successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="body", type="string", example="this is a body for comment"),
     *                 @OA\Property(property="status", type="int", example="1"),
     *                  @OA\Property(property="author", type="string", example="info author"),
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
    public function show(Comment $comment)
    {
        return CommentResource::make($comment);
    }

    /**
     *
     * @OA\Put   (
     *     path="/api/v1/comments/{comment}",
     *     summary="Comment update",
     *     tags={"Comment"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Comment data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="body",
     *                      type="string",
     *                      description="update body comment ",
     *                      example="this is a new body for comment",
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
     *         description="update comment successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                      property="data",
     *                 @OA\Property(property="body", type="string", example="this is a body for comment"),
     *                 @OA\Property(property="status", type="int", example="1"),
     *                   @OA\Property(property="author", type="string", example="info author"),
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
    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());

        return CommentResource::make($comment);
    }

    /**
     *
     * @OA\Delete (
     *     path="/api/v1/comments/{comment}",
     *     summary="delete a comment",
     *     tags={"Comment"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="Comment delete successfully",
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
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->noContent();
    }
}
