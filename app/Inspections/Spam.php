<?php

namespace App\Inspections;

class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($body)
    {
        # for each of the classes above
        foreach ($this->inspections as $inspection) {
            # use laravel container
            app($inspection)->detect($body);
        }

        # no spam found
        return false;
    }
}
