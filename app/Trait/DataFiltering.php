<?php

namespace App\Trait;

use Illuminate\Http\Request;

trait DataFiltering
{
    public function loadingRelationFromRequest(
        Request $request,
        object $model,
        mixed $includes,
        mixed $relations,
        string $queryName = 'include',
        string $relationLoadingMode = 'with'
    )
    {
        $request->whenFilled($queryName,function ($query) use (
            $model, $includes, $relations, $relationLoadingMode
        ) {
            $convertQueryToArray = explode(',',$query);
            if (!is_array($relations) && !is_array($includes))
                $relationIIncludeMatch = [$includes => $relations];
            else
                $relationIIncludeMatch = array_combine($includes, $relations);

            foreach ($relationIIncludeMatch as $include => $relation){
                if (in_array($include,$convertQueryToArray)){
                    $model->{$relationLoadingMode}($relation);
                }
            }
        });
    }

}
