<?php

namespace App\Http\Controllers\Api\V1\Collaboration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Collaboration\CompanyRequest;
use App\Http\Resources\Api\V1\Collaboration\CompanyResource;
use App\Models\Collaboration\Company;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Void_;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
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

        $this->included($request,$companies,'with');

        return CompanyResource::collection($companies->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        $inputs = $request->all();
        $inputs['owner_id'] = $request->user()->id;
        $inputs['status'] = 1;
        $company = Company::create($inputs)->load('owner');
        return CompanyResource::make($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Company $company)
    {
        $this->included($request,$company);

        return CompanyResource::make($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->all());
        return CompanyResource::make($company->load('owner'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return response()->noContent();
    }
    public function included(Request $request,$company,string $type = 'load'): void
    {
        $request->whenFilled('included',function ($included) use ($company,$type){
            $includeds = explode(',',$included);
            if (in_array('owner',$includeds)){
                $company->$type('owner');
            }
            if (in_array('users',$includeds)){
                $company->$type('users');
            }
            if (in_array('projects',$includeds)){
                $company->$type('projects');
            }
        });
    }
}
