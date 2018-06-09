<?php

namespace App\Services;


use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Route;

class Validator
{

    private $data;

    private $properties;

    private $request;

    public $errors = [];

    public function __construct(Request $request, array $properties)
    {
        $this->data = $request->all();
        $this->properties = $properties;
        $this->request = $request;

        return $this;
    }


    public function make()
    {
        foreach ($this->properties as $field => $row) {
            $validations = explode('|', $row);

            foreach ($validations as $validation) {
                $validation_arr = explode(':', $validation);
                $validation = $validation_arr[0];

                $params = null;
                if (isset($validation_arr[1])) {
                    $params = $validation_arr[1];
                }

                $this->$validation($field, $params);
            }
        }

        if (count($this->errors)) {
            throw new ValidationException($this);
        }
    }

    private function required($field, $params)
    {
        if (!isset($this->data[$field])) {
            $this->errors[] = "$field is required.";
        } elseif (is_string($this->data[$field]) && !strlen($this->data[$field])) {
            $this->errors[] = "$field is required.";
        } elseif (is_array($this->data[$field]) && !count($this->data[$field])) {
            $this->errors[] = "$field is required.";
        }
    }

    private function in($field, $params)
    {
        $params = explode(',', $params);

        if (!in_array($this->data[$field], $params)) {
            $this->errors[] = "$field must be in : " . implode(' ,', $params);
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
        if (isset($this->data[$field]) && is_array($this->data[$field])) {
            if (count($this->data[$field]) < (int)$params) {
                $this->errors[] = "$field must have $params min elements.";
            }
        }

        if (isset($this->data[$field]) && is_string($this->data[$field])) {
            if (strlen($this->data[$field]) < (int)$params) {
                $this->errors[] = "$field must have $params min char.";
            }
        }
    }

    private function max($field, $params)
    {
        if (isset($this->data[$field]) && is_array($this->data[$field])) {
            if (count($this->data[$field]) > (int)$params) {
                $this->errors[] = "$field must have $params max elements.";
            }
        }

        if (isset($this->data[$field]) && is_string($this->data[$field])) {
            if (strlen($this->data[$field]) > (int)$params) {
                $this->errors[] = "$field must have $params max char.";
            }
        }
    }


    private function confirmation($field, $params)
    {
        if (isset($this->data['password'])) {
            if (!isset($this->data[$field . '_confirmation']) || !strlen($this->data[$field . '_confirmation'])) {
                $this->errors[] = "$field confirmation is required.";
            } else {
                if ($this->data[$field . '_confirmation'] != $this->data[$field]) {
                    $this->errors[] = "$field confirmation is invalid.";
                }
            }
        }
    }

    private function unique($field, $params)
    {
        $pdo = app('db')->getPdo();
        $params = explode(',', $params);
        $value = $this->data[$field];

        $sql = "SELECT * FROM {$params[0]} WHERE {$params[1]} = :v";

        if (isset($params[2])) {
            $sql .= " AND id <> :i";
        }
        $query = $pdo->prepare($sql);
        $query->bindParam(':v', $value);

        if (isset($params[2])) {
            $query->bindParam(':i', $params[2]);
        }

        $query->execute();

        if ($query) {
            if (count($query->fetchAll())) {
                $this->errors[] = "$field is not unique";
            };
        }

    }

    private function password($field, $params)
    {
        if (isset($this->data['password']) && !preg_match('#.*[0-9].*#', $this->data[$field])) {

            $this->errors[] = "$field must contain at least a number.";
        }
    }

    private function exists($field, $params)
    {
        $pdo = app('db')->getPdo();
        $params = explode(',', $params);
        $value = $this->data[$field];

        $sql = "SELECT * FROM {$params[0]} WHERE {$params[1]} = :v";

        $query = $pdo->prepare($sql);
        $query->bindParam(':v', $value);

        $query->execute();

        if ($query) {
            if ($query->rowCount() === 0) {
                $this->errors[] = "$field does not exists";
            };
        }

    }

    private function image($field, $params)
    {
        if (isset($this->data[$field])) {
            $value = $this->data[$field];

            if (!($value instanceof UploadedFile) || $value->getSize() > 4000000 || !starts_with($value->getClientMimeType(), 'image/')) {
                $this->errors[] = "$field is not an image.";
            }
        }
    }
}