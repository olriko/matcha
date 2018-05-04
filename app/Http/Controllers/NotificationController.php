<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function get(Request $request)
    {
        $query = $this->db()->prepare('SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC');
        $query->bindParam(':user_id', $request->get('user')['id']);
        $query->execute();

        $notifications = $query->fetchAll(\PDO::FETCH_ASSOC);

        $query = $this->db()->prepare('UPDATE notifications SET viewed = 1 WHERE user_id = :user_id');
        $query->bindParam(':user_id', $request->get('user')['id']);
        $query->execute();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }
}