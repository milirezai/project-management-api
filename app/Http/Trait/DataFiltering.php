<?php

namespace App\Http\Trait;

use Illuminate\Http\Request;

trait DataFiltering
{
    protected Request $request;
    protected object $model;
    protected mixed $relations;
    protected string $typeLoading;
    protected mixed $includes;
    protected string $queryStringName = 'include';

    public function queryStringName(string $queryStringName): self
    {
        $this->queryStringName = $queryStringName;
        return $this;
    }
    public function request(mixed $request): self
    {
        $this->request = $request;
        return $this;
    }
    public function include(mixed $includes): self
    {
        $this->includes = $includes;
        return $this;
    }

    public function model(object $model): self
    {
        $this->model = $model;
        return  $this;
    }

    public function relations(mixed $relations): self
    {
        $this->relations = $relations;
        return $this;
    }

    public function typeLoading(string $typeLoading): self
    {
        $this->typeLoading = $typeLoading;
        return $this;
    }

    public function relationLoad(): void
    {
        $this->request->whenFilled($this->queryStringName ,function ($queryStringName) {
            $includes = explode(',',$queryStringName);
            if (!is_array($this->relations) && !is_array($this->includes))
                $relationIIncludeMatch = [$this->includes => $this->relations];
            else
                $relationIIncludeMatch = array_combine($this->includes,$this->relations);

            foreach ($relationIIncludeMatch as $include => $relation){
                if (in_array($include,$includes)){
                    $this->model->{$this->typeLoading}($relation);
                }
            }
        });
    }
}
