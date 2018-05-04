<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

    protected function notify($id, $description, $by = null)
    {
        $created_at = Carbon::now()->toDateTimeString();

        $query =  $this->db()->prepare('INSERT INTO notifications (description , user_id, notify_by, created_at) VALUES (:description, :user_id, :notify_by , :created_at)');
        if ($query) {
            $query->bindParam(':description',$description);
            $query->bindParam(':user_id',$id);
            $query->bindParam(':notify_by',$by);
            $query->bindParam(':created_at', $created_at);
            $query->execute();
            return true;
        }
        return false;
    }

    protected function score($id, $points)
    {
        $sign = $points > 0 ? '+' : '-';
        $points = abs($points);

        $query =  $this->db()->prepare("UPDATE users SET scores = scores {$sign} {$points} WHERE id = :id");

        if ($query) {
            $query->bindParam(':id',$id);
            $query->execute();
            return true;
        }
        return false;
    }
}
