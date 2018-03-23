<?php

namespace App\Services\Validator;


use Illuminate\Http\Request;
use Route;

class Validator
{

    private $data;

    private $properties;

    private $request;

    private $errors = [];

    public function __construct(Request $request, array $properties)
    {
        $this->data = $request->all();
        $this->properties = $properties;
        $this->request = $request;

        return $this;
    }


    public function make()
    {
        foreach ($this->properties as $field => $row)
        {
            $validations =  explode('|' ,$row);

            foreach ($validations as $validation) {
                $validation_arr =  explode(':' , $validation);
                $validation = $validation_arr[0];

                $params = null;
                if (isset($validation_arr[1])) {
                    $params = $validation_arr[1];
                }

                $this->$validation($field, $params);
            }
        }

        if (count($this->errors)) {
            return redirect($this->request->url())
                ->withErrors($this->errors)
                ->withInput($this->data);
        }

    }

    private function required($field, $params)
    {
        if (!isset($this->data[$field])) {
            $this->errors[] = "$field is required.";
        }

        if (is_string($this->data[$field]) && !strlen($this->data[$field])) {
            $this->errors[] = "$field is required.";
        }

        if (is_array($this->data[$field]) && !count($this->data[$field])) {
            $this->errors[] = "$field is required.";
        }
    }

    private function in($field, $params)
    {
        $params = explode(',',$params);

        if (!in_array($this->data[$field], $params)) {
            $this->errors[] = "$field must be in : " . implode(' ,' ,$params);
        }
    }


    private function email($field, $params)
    {
        if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "$field is invalid.";
        }
    }

    private function min($field, $params)
    {
        if (is_array($this->data[$field])) {
            if (count($this->data[$field]) < (int) $params) {
                $this->errors[] = "$field must have $params min elements.";
            }
        }

        if (is_string($this->data[$field])) {
            if (strlen($this->data[$field]) < (int) $params) {
                $this->errors[] = "$field must have $params min char.";
            }
        }
    }

    private function max($field, $params)
    {
        if (is_array($this->data[$field])) {
            if (count($this->data[$field]) > (int) $params) {
                $this->errors[] = "$field must have $params max elements.";
            }
        }

        if (is_string($this->data[$field])) {
            if (strlen($this->data[$field]) > (int) $params) {
                $this->errors[] = "$field must have $params max char.";
            }
        }
    }


    private function confirmation($field, $params)
    {
        if (!isset($this->data[$field . '_confirmation']) || !strlen($this->data[$field . '_confirmation'])) {
            $this->errors[] = "$field confirmation is required.";
        } else {
           if ($this->data[$field . '_confirmation'] != $this->data[$field]) {
               $this->errors[] = "$field confirmation is invalid.";
           }
        }
    }
}