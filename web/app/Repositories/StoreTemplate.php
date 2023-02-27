<?php

namespace App\Repositories;

class StoreTemplate
{
    public static function get(string $templateName, array $array)
    {
        return view($templateName, compact('array'))->render();
    }
}
