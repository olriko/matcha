<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Validator;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    /**
     * @param Request $request
     * @param array $property
     */
    public function validation(Request $request, array  $property)
    {
        (new Validator($request, $property))->make();
    }

    public function db() : \PDO
    {
        return  app('db')->getPdo();
    }

    protected function notify($id, $message)
    {

    }

    protected function score($id, $points)
    {

    }
}
