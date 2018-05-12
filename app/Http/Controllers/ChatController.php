<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
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

    private function fetchMatch($id)
    {
        $query = $this->db()->prepare('SELECT * FROM matches WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetch(\PDO::FETCH_ASSOC);
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
            'current' => (int) $user,
            'matches' => $matches
        ]);
    }

    public function getMessages(Request $request, $id)
    {
        return response()->json([
            'messages' => $this->fetchMessages($id)
        ]);
    }

    public function storeMessage(Request $request)
    {
        $user =  $request->get('user')['id'];
        $match_id = $request->get('match_id');
        $content = $request->get('content');
        $created_at = Carbon::now()->toDateTimeString();

        $match = $this->fetchMatch($match_id);

        if ($match['user1_id'] == $user || $match['user2_id'] == $user) {

            $this->notify(
                $match['user1_id'] == $user ? $match['user2_id'] : $match['user1_id'],
                $request->get('user')['username'] . ' text you',
                $user
            );

            $query = $this->db()->prepare('INSERT INTO messages (match_id, user_id, content, created_at) VALUES (:match_id, :user_id, :content, :created_at)');
            $query->bindParam(':match_id', $match_id);
            $query->bindParam(':user_id', $user);
            $query->bindParam(':content', $content);
            $query->bindParam(':created_at', $created_at);
            $query->execute();

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ], 403);
    }

}