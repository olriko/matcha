<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Validator\Validator;
use Request;

class Controller extends BaseController
{
    /**
     * @param Request $request
     * @param array $property
     */
    public function validate(Request $request, array  $property)
    {
        (new Validator($request, $property))->make();
    }

    public function db() : \PDO
    {
        return  app('db')->getPdo();
    }
}
