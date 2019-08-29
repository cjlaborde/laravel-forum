<?php

namespace App\Inspections;

class KeyHeldDown
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            // throws error and test passes
            throw new \Exception('Your reply contains spam.');
        }
    }
}
