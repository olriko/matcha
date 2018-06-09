<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function get(Request $request)
    {
        if ($request->get('all') == 'true') {
            $query = $this->db()->prepare('SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC');
        } else {
            $query = $this->db()->prepare('SELECT * FROM notifications WHERE user_id = :user_id AND `read` = 0 ORDER BY created_at DESC');
        }
        $query->bindParam(':user_id', $request->get('user')['id']);
        $query->execute();

        $notifications = $query->fetchAll(\PDO::FETCH_ASSOC);

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function read(Request $request)
    {
        $user = $request->get('user')['id'];
        $query = $this->db()->prepare("UPDATE notifications SET `read` = 1 WHERE user_id = :user_id");
        $query->bindParam(':user_id', $user);
        $query->execute();

        return response()->json([
            'success' => true,
        ]);
    }
}