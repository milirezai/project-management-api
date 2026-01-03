<?php

namespace App\Http\Controllers\Api\V1\Collaboration;

use App\Http\Requests\Api\V1\Collaboration\CompanyRequest;
use App\Http\Resources\Api\V1\Collaboration\CompanyResource;
use App\Models\Collaboration\Company;
use App\Notifications\Collaboration\CompanyCreateNotification;
use App\Notifications\Collaboration\CompanyDeleteNotification;
use App\Notifications\Collaboration\CompanyUpdateNotification;
use App\Notifications\User\UserSyncRoleNotification;
use App\Trait\DataFiltering;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CompanyController extends Controller
{
    use DataFiltering,AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Company::class);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/companies",
     *     summary="get companies",
     *     tags={"Company"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="owner,users,projects"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get companies successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala"),
     *                 @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     *                 @OA\Property(property="address", type="string", example="iran,thrane"),
     *                 @OA\Property(property="phone_number", type="string", example="09167516826"),
     *                 @OA\Property(property="email", type="string", example="store"),
     *                 @OA\Property(property="website", type="string", example="digikala.com"),
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
        $companies = Company::query();

        $request->whenFilled('status',function ($status) use ($companies){
            $companies->status($status);
        });
        $request->whenFilled('type',function ($type) use ($companies){
            $companies->type($type);
        });

         $this->loadingRelationFromRequest(
            model: $companies, request: $request,
            includes: ['owner','users','projects'], relations: ['owner','users','projects']
        );

        return CompanyResource::collection($companies->get());
    }

    /**
     *
     * @OA\Post  (
     *     path="/api/v1/companies",
     *     summary="Company create",
     *     tags={"Company"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Company data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name", "address", "phone_number"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="company name",
     *                      example="digikala",
     *                      maxLength=40,
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description for company",
     *                      example="digikala is the largest online store in Iran.",
     *                      maxLength=400,
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="address company",
     *                      example="iran,thrane",
     *                      minLength=50,
     *                      maxLength=5
     *                  ),
     *                       @OA\Property(
     *                       property="phone_number",
     *                       type="string",
      *                       description="phone number for company",
     *                       example="09167516826",
      *                   ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email for company",
     *                      example="digikala@gmail.com",
     *                     nullable=true,
     *                  ),
     *                  @OA\Property(
     *                      property="website",
     *                      type="url",
     *                      description="url adders for company website",
     *                       example="digikala.com",
     *                      nullable=true
     *                  ),
     *                @OA\Property(
     *                       property="type",
     *                       type="string",
     *                       description="type for company",
     *                       example="store",
     *                       nullable=true
     *                   ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="create company successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala"),
     *                 @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     *                 @OA\Property(property="address", type="string", example="iran,thrane"),
     *                 @OA\Property(property="phone_number", type="string", example="09167516826"),
     *                 @OA\Property(property="email", type="string", example="store"),
     *                 @OA\Property(property="website", type="string", example="digikala.com"),
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
    public function store(CompanyRequest $request)
    {
        $inputs = $request->all();
        $inputs['owner_id'] = $request->user()->id;
        $request->user()->roles()->syncWithoutDetaching(2);
        $inputs['status'] = 1;
        $company = Company::create($inputs)->load('owner');

        $company->owner
            ->notify(new CompanyCreateNotification());
        $company->owner
            ->notify(new UserSyncRoleNotification());

        return CompanyResource::make($company);
    }

    /**
     *
     * @OA\Get (
     *     path="/api/v1/companies/{company}",
     *     summary="get company",
     *     tags={"Company"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter (
     *         name="include",
     *          in="query",
     *          description="Load related resources (comma separated)",
     *          required=false,
     *              @OA\Schema(
     *              type="string",
     *              example="owner,users,projects"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="get company successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala"),
     *                 @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     *                 @OA\Property(property="address", type="string", example="iran,thrane"),
     *                 @OA\Property(property="phone_number", type="string", example="09167516826"),
     *                 @OA\Property(property="email", type="string", example="store"),
     *                 @OA\Property(property="website", type="string", example="digikala.com"),
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
    public function show(Request $request,Company $company)
    {
        $this->loadingRelationFromRequest(
            model: $company, request: $request,
            includes: ['owner','users','projects'], relations: ['owner','users','projects'], relationLoadingMode: 'load'
        );

        return CompanyResource::make($company);
    }

    /**
     *
     * @OA\Put  (
     *     path="/api/v1/companies/",
     *     summary="Company create",
     *     tags={"Company"},
     *     security={{"sanctum":{}}},
     *          @OA\RequestBody(
     *          required=true,
     *          description="Company data",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="company name",
     *                      example="digikala",
     *                      maxLength=40,
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description for company",
     *                      example="digikala is the largest online store in Iran.",
     *                      maxLength=400,
     *                      nullable=true
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      type="string",
     *                      description="address company",
     *                      example="iran,thrane",
     *                      minLength=50,
     *                      maxLength=5,
     *                      nullable=true
     *                  ),
     *                       @OA\Property(
     *                       property="phone_number",
     *                       type="string",
     *                       description="phone number for company",
     *                       example="09167516826",
     *                   ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="email for company",
     *                      example="digikala@gmail.com",
     *                     nullable=true,
     *                  ),
     *                  @OA\Property(
     *                      property="website",
     *                      type="url",
     *                      description="url adders for company website",
     *                       example="digikala.com",
     *                      nullable=true
     *                  ),
     *                @OA\Property(
     *                       property="type",
     *                       type="string",
     *                       description="type for company",
     *                       example="store",
     *                       nullable=true
     *                   ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="update company successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="name", type="string", example="digikala"),
     *                 @OA\Property(property="description", type="string", example="digikala is the largest online store in Iran."),
     *                 @OA\Property(property="address", type="string", example="iran,thrane"),
     *                 @OA\Property(property="phone_number", type="string", example="09167516826"),
     *                 @OA\Property(property="email", type="string", example="store"),
     *                 @OA\Property(property="website", type="string", example="digikala.com"),
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
    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->all());
        $company->owner
            ->notify(new CompanyUpdateNotification());

        return CompanyResource::make($company->load('owner'));
    }

    /**
     *
     * @OA\Delete (
     *     path="/api/v1/companies/{company}",
     *     summary="delete a company",
     *     tags={"Company"},
     *      security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="Company delete successfully",
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
    public function destroy(Company $company)
    {
        $company->owner
            ->notify(new CompanyDeleteNotification());
        $company->delete();

        return response()->noContent();
    }

}
