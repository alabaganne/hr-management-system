<?php

namespace App\Http\Controllers\Collaborators;

use App\Http\Controllers\Controller;
use App\Models\User; // collaborator
use App\Services\CollaboratorService;

// requests validations
use App\Http\Requests\Collaborator as CollaboratorRequest;
use App\Http\Requests\Search as SearchRequest;
use App\Http\Resources\CollaboratorResource;
use App\Models\Department;
use Illuminate\Http\Request;

/**
 * @OA\PathItem(
 *      path="/api/collaborators/{user_id}",
 *      @OA\Parameter(ref="#/components/parameters/user_id"),
 * )
 * 
 * @OA\Response(
 *     response="paginatedCollaborators",
 *     description="Paginated list of collaborators",
 *     @OA\JsonContent(
 *         @OA\Property(
 *             property="data",
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/user")
 *          ),
 *         @OA\Property(
 *             property="links",
 *             type="object",
 *             @OA\Property(
 *                      property="first",
 *                 type="string",
 *                      example="http://127.0.0.1:8000/api/_ROUTE_?page=1"
 *             ),
 *             @OA\Property(
 *                 property="last",
 *                 type="string",
 *                 example="http://127.0.0.1:8000/api/_ROUTE_?page=20"
 *             ),
 *             @OA\Property(
 *                 property="prev",
 *                 type="string",
 *                 example="http://127.0.0.1:8000/api/_ROUTE_?page=11"
 *             ),
 *             @OA\Property(
 *                 property="next",
 *                 type="string",
 *                 example="http://127.0.0.1:8000/api/_ROUTE_?page=13"
 *             ),
 *         ),
 *         @OA\Property(
 *             property="meta",
 *             type="object",
 *             @OA\Property(
 *                 property="current_page",
 *                 type="integer",
 *                 example=12
 *             ),
 *             @OA\Property(
 *                 property="last_page",
 *                 type="integer",
 *                 example=20
 *             ),
 *             @OA\Property(
 *                 property="per_page",
 *                 type="integer",
 *                 example=8
 *             ),
 *             @OA\Property(
 *                 property="total",
 *                 type="integer",
 *                 example=160
 *             ),
 *         )
 *     )
 * )
 */

class CollaboratorController extends Controller
{
    protected CollaboratorService $collaboratorService;

    public function __construct(CollaboratorService $collaboratorService)
    {
        $this->collaboratorService = $collaboratorService;
    }

    /**
     * @OA\Post(
     *      path="/api/collaborators",
     *      tags={"Collaborators"},
     *      summary="Get list of collaborators",
     *      description="Get paginated list of collaborators",
     *      operationId="index",
     *      @OA\requestBody(ref="#/components/requestBodies/searchRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/paginatedCollaborators"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     *      @OA\Response(response=422, ref="#/components/responses/invalid-data")
     * )
    */
    public function index(SearchRequest $request) { // request body must include items_per_page
        $validated = $request->validated();

        $collaborators = User::doesntHave('roles')
            ->where('name', 'LIKE', '%' . $validated['search_text'] . '%')
            ->paginate($validated['items_per_page']);

        return CollaboratorResource::collection($collaborators);
    }
    
    /**
     * @OA\Get(
     *      path="/api/collaborators/{user_id}",
     *      tags={"Collaborators"},
     *      summary="Collaborator details",
     *      description="Get collaborator informations",
     *      operationId="show",
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Collaborator details response",
     *          @OA\JsonContent(
     *              @OA\Property(ref="#/components/schemas/user")
     *          )
     *      ),
     *     @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *     @OA\Response(response=403, ref="#/components/responses/unauthorized")
     * )
    */
    public function show(User $user) {
        return response()->json(new CollaboratorResource($user), 200);
    }

   /**
     * @OA\Post(
     *     path="/api/collaborators/create",
     *     tags={"Collaborators"},
     *     summary="Add a new collaborator",
     *     description="Create a new collaborator",
     *     operationId="store",
     *     @OA\RequestBody(ref="#/components/requestBodies/collaboratorRequestBody"),
     *     @OA\Response(
     *          response=201,    
     *          description="Collaborator created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="collaborator_id", type="integer", example=1)
     *          )
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *     @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     *     @OA\Response(response=422, ref="#/components/responses/invalid-data")
     * )
     */
    public function store(CollaboratorRequest $request) {
        $collaborator = $this->collaboratorService->createCollaborator($request->validated());

        return response()->json(['collaborator_id' => $collaborator->id], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/collaborators/{user_id}",
     *     tags={"Collaborators"},
     *     summary="Edit collaborator",
     *     description="Edit an existing collaborator",
     *     operationId="store",
     *     @OA\RequestBody(ref="#/components/requestBodies/collaboratorRequestBody"),
     *     @OA\Response(response=200, ref="#/components/responses/success"),
     *     @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *     @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     *     @OA\Response(response=422, ref="#/components/responses/invalid-data")
     * )
     */
    public function update(CollaboratorRequest $request, User $user) {
        $this->collaboratorService->updateCollaborator($user, $request->validated());

        return response()->json([], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/collaborators/{user_id}",
     *      tags={"Collaborators"},
     *      summary="Delete collaborator",
     *      description="Delete a collaborator",
     *      @OA\Parameter(ref="#/components/parameters/user_id"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function destroy(User $user) {
        $this->collaboratorService->deleteCollaborator($user);

        return response()->json([], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/collaborators/gender",
     *      tags={"Collaborators"},
     *      summary="Number of collaborators per gender",
     *      description="Number of collaborators for each gender",
     *      @OA\Response(
     *          response=200,
     *          description="Number of collaborators per gender",
     *          @OA\JsonContent(
     *              @OA\Property(property="male", type="integer", example=7),
     *              @OA\Property(property="female", type="integer", example=6)
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function collaboratorsNumberByGender() {
        return response()->json($this->collaboratorService->getCollaboratorsByGender());
    }

    /**
     * @OA\Get(
     *      path="/api/collaborators/department",
     *      tags={"Collaborators"},
     *      summary="Number of collaborators per department",
     *      description="Number of collaborators for each department",
     *      @OA\Response(
     *          response=200,
     *          description="Number of collaborators per department",
     *          @OA\JsonContent(
     *              @OA\Property(property="Web", type="integer", example=5),
     *              @OA\Property(property="Mobile", type="integer", example=4),
     *              @OA\Property(property="AI", type="integer", example=2),
     *              @OA\Property(property="Data Science", type="integer", example=3),
     *              @OA\Property(property="Design", type="integer", example=2)
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function collaboratorsNumberByDepartment() {
        return response()->json($this->collaboratorService->getCollaboratorsByDepartment(), 200);
    }

    /**
     * @OA\Get(
     *      path="/api/collaborators/archive",
     *      tags={"Collaborators"},
     *      summary="Collaborators from the archive",
     *      description="Collaborators from the archive",
     *      @OA\Response(response=200, ref="#/components/responses/paginatedCollaborators"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function archive(SearchRequest $request) { // req body must include: items_per_page
        $validated = $request->validated();

        $archive = $this->collaboratorService->getArchivedCollaborators(
            $validated['search_text'],
            $validated['items_per_page']
        );

        return CollaboratorResource::collection($archive);
    }

    /**
     * @OA\Get(
     *      path="/api/collaborators/{user_id}/restore",
     *      tags={"Collaborators"},
     *      summary="Restore a collaborator",
     *      description="Restore a collaborator",
     *      @OA\Parameter(ref="#/components/parameters/user_id"),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User restored.")
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized")
     * )
    */
    public function restore($user_id) {
        $this->collaboratorService->restoreCollaborator($user_id);

        return response()->json(['message' => 'User restored.'], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/collaborators/{user_id}/delete-permantly",
     *      tags={"Collaborators"},
     *      summary="Delete a collaborator permantly",
     *      description="Delete a collaborator permantly from the database",
     *      @OA\Parameter(ref="#/components/parameters/user_id"),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User permantly deleted.")
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function deletePermantly($user_id) {
        $user = User::onlyTrashed()->findOrFail($user_id);
        $this->collaboratorService->permanentlyDeleteCollaborator($user);

        return response()->json(['message' => 'User permantly deleted.'], 200);
    }
}
