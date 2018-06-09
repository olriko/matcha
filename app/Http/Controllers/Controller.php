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
    public function validation(Request $request, array $property)
    {
        (new Validator($request, $property))->make();
    }

    public function db(): \PDO
    {
        return app('db')->getPdo();
    }


    private function isBlock($id, $by)
    {
        $likes = $this->db()->prepare("SELECT * FROM `blocks` WHERE user_id = :id AND blocked_by = :blocked_by ");
        $likes->bindParam(':id', $id);
        $likes->bindParam(':blocked_by', $by);
        $likes->execute();

        return $likes->rowCount() > 0;

    }

    protected function notify($id, $description, $by = null)
    {
        $created_at = (new \DateTime())->format('Y-m-d H:i:s');

        $query = $this->db()->prepare('INSERT INTO notifications (description , user_id, notify_by, created_at) VALUES (:description, :user_id, :notify_by , :created_at)');
        if ($query && !$this->isBlock($by, $id)) {
            $query->bindParam(':description', $description);
            $query->bindParam(':user_id', $id);
            $query->bindParam(':notify_by', $by);
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

        $query = $this->db()->prepare("UPDATE users SET `score` = `score` $sign $points WHERE id = :id");

        if ($query) {
            $query->bindParam(':id', $id);
            $query->execute();
            return true;
        }
        return false;
    }
}
