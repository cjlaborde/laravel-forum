<?php

namespace App\Filters;

use Illuminate\Http\Request;

class Filters
{
    protected $request;
    protected $builder;

    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

//        dd($this->getFilters());

//        dd($this->request->only($this->filters)); since we use by method user can't pass dangerous script http://localhost:8000/threads?by=John&badcode

        foreach ($this->getFilters() as $filter => $value) {
            // if method exist for this filter trigger the filter and pass the value
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
//
        return $this->builder;
    }

    protected function getFilters()
    {
        $filters = array_intersect(array_keys($this->request->all()), $this->filters);

        return $this->request->only($filters);
    }
}

/*

    public function apply($builder)
    {
        $this->builder = $builder;

        $this->getFilters()
            ->filter(function ($filter) {
                return method_exists($this, $filter);
            })
            ->each(function ($filter, $value) {
                $this->$filter($value);
            });

//        dd($this->getFilters());

//        dd($this->request->only($this->filters)); since we use by method user can't pass dangerous script http://localhost:8000/threads?by=John&badcode

//        foreach ($this->getFilters() as $filter => $value) {
//            # if method exist for this filter trigger the filter and pass the value
//            if (method_exists($this, $filter)) {
//                $this->$filter($value);
//            }
//        }
//
        return $this->builder;
    }

    protected function getFilters()
    {
        $filters = array_intersect(array_keys($this->request->all()), $this->filters);
        return collect($this->request->only($filters))->flip();
    }
 */
