<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public  function add(Request $request) {

        $this->validation($request, [
           'image' => 'required|image'
        ]);

        $user = $request->get('user');

        $query = $this->db()->prepare("select `user_id` , `id` from `images` where `user_id` LIKE ?");
        $query->bindParam(':user_id',$user['id']);
        $query->execute([$request->get('user')['id']]);

        if ($query->rowCount() > 5) {
            return response()->json([
               'success' => false,
               'message' => 'Too many images'
            ]);
        }

        $image = $request->file('image');

        $name = uniqid() . "." .  $image->extension();

        $created_at = Carbon::now()->toDateTimeString();

        $main = $query->rowCount() == 0;

        $image->move(storage_path("/app/avatars"), $name);

        $query2 =  $this->db()->prepare('INSERT INTO images (user_id, name, main, created_at) VALUES (:user_id, :name, :main , :created_at)');

        if ($query2) {
            $query2->bindParam(':user_id',$user['id']);
            $query2->bindParam(':name',$name);
            $query2->bindParam(':main',$main);
            $query2->bindParam(':created_at', $created_at);
            $query2->execute();

            return response()->json([
                'success' => true,
                'image' => [
                    'id' => $this->db()->lastInsertId(),
                    'name' => $name,
                    'main' => $main,
                ]
            ]);
        }

    }

    public function remove(Request $request, $id)
    {
        $query =  $this->db()->prepare('DELETE FROM images WHERE user_id = :user_id AND id = :id');

        $user = $request->get('user')['id'];

        if ($query) {
            $query->bindParam(':user_id',$user);
            $query->bindParam(':id', $id);
            $query->execute();
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $user = $request->get('user')['id'];


        $query = $this->db()->prepare("UPDATE images SET main = 0 WHERE user_id = :user_id");
        $query->bindParam(':user_id',$user);
        $query->execute();

        $query = $this->db()->prepare("UPDATE images SET main = 1 WHERE user_id = :user_id AND id = :id");
        $query->bindParam(':user_id',$user);
        $query->bindParam(':id',$id);
        $query->execute();

        return response()->json([
            'success' => true
        ]);
    }
}