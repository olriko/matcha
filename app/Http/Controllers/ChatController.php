<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class ChatController extends Controller
{

    private function fetchUser($id)
    {
        $user = $this->db()->prepare("SELECT * FROM users WHERE id = :id");
        $user->bindParam(':id', $id);
        $user->execute();

        return collect($user->fetch(\PDO::FETCH_ASSOC))->except('password')->all();
    }

    private function fetchMessages($id)
    {
        $user = $this->db()->prepare("SELECT * FROM messages WHERE match_id = :match_id ORDER BY created_at");
        $user->bindParam(':match_id', $id);
        $user->execute();

        return $user->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get(Request $request)
    {

        $user =  $request->get('user')['id'];

        $query = $this->db()->prepare('SELECT * FROM matches WHERE user1_id = :user1_id OR  user2_id = :user2_id ORDER BY created_at DESC');
        $query->bindParam(':user1_id', $user);
        $query->bindParam(':user2_id', $user);
        $query->execute();

        $matches = collect($query->fetchAll(\PDO::FETCH_ASSOC));

        $matches->transform(function($item) {
            $item['user1'] = $this->fetchUser($item['user1_id']);
            $item['user2'] = $this->fetchUser($item['user2_id']);

            $item['messages'] = $this->fetchMessages($item['id']);

            return $item;
        });

        return response()->json([
            'current' => $user,
            'matches' => $matches
        ]);
    }

    public function store()
    {

    }

}