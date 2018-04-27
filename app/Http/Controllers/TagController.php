<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;

class TagController extends Controller
{

    private function fetch($tag)
    {
        $query = $this->db()->prepare("SELECT * FROM tags WHERE name = :name");

        $query->bindParam(':name', $tag);
        $query->execute();

        $fetch = $query->fetch(\PDO::FETCH_ASSOC);
        if (!$fetch) {
            return null;
        } else {
            return $fetch;
        }
    }

    public function search($toSearch)
    {
        $req = $this->db()->prepare("select `name` , `id` from `tags` where `name` LIKE ?");
        $req->execute(["%{$toSearch}%"]);
        return response()->json([
            'success' => true,
            'tags' => $req->fetchAll(\PDO::FETCH_ASSOC)
        ]);
    }

    public function add(Request $request)
    {
        $tag = $request->get('tag');
        $user = $request->get('user')['id'];
        $created_at = Carbon::now()->toDateTimeString();

        $tagDb = $this->fetch($tag);

        if (!$tagDb) {
            $query =  $this->db()->prepare('INSERT INTO tags ( name , created_at) VALUES (:name ,:created_at)');
            if ($query) {
                $query->bindParam(':name',$tag);
                $query->bindParam(':created_at', $created_at);
                $query->execute();
                $tagDb = ['id' => $this->db()->lastInsertId(), 'name' => $tag];
            }
        }

        $query =  $this->db()->prepare('INSERT INTO tag_user (user_id, tag_id, created_at) VALUES (:user_id, :tag_id , :created_at)');

        if ($query) {
            $tagId = $tagDb['id'];
            $query->bindParam(':user_id',$user);
            $query->bindParam(':tag_id',$tagId);
            $query->bindParam(':created_at', $created_at);
            $query->execute();

            return response()->json([
                'success' => true,
                'tag' => [
                    'id' => $tagId,
                    'name' => $tag
                ]
            ]);
        }
    }

    public function remove(Request $request, $id)
    {
        $query =  $this->db()->prepare('DELETE FROM tag_user WHERE user_id = :user_id AND tag_id = :tag_id');

        $user = $request->get('user')['id'];

        if ($query) {
            $query->bindParam(':user_id',$user);
            $query->bindParam(':tag_id', $id);
            $query->execute();
            return response()->json([
                'success' => true
            ]);
        }
    }
}