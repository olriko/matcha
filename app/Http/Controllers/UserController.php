<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;

class UserController extends Controller
{
    private function fetch($id): Collection
    {
        /**
         * user
         */
        $user = $this->db()->prepare("SELECT * FROM users WHERE id = :id");
        $user->bindParam(':id', $id);
        $user->execute();

        /**
         * tags
         */

        $tags = $this->db()->prepare("SELECT tags.name as name, tags.id as id FROM `tags` INNER JOIN tag_user ON tags.id = tag_user.tag_id WHERE tag_user.user_id = :id");
        $tags->bindParam(':id', $id);
        $tags->execute();

        /**
         * likes
         */

        $likes = $this->db()->prepare("SELECT users.username as name, users.id as id, likes.created_at as dt FROM `users` INNER JOIN likes ON users.id = likes.liked_by WHERE likes.user_id = :id ORDER BY dt DESC LIMIT 10");
        $likes->bindParam(':id', $id);
        $likes->execute();

        /**
         * visits
         */

        $visits = $this->db()->prepare("SELECT users.username as name, users.id as user_id, visits.id as id ,visits.created_at as dt FROM `users` INNER JOIN visits ON users.id = visits.visited_by WHERE visits.user_id = :id ORDER BY dt DESC LIMIT 10");
        $visits->bindParam(':id', $id);
        $visits->execute();

        /**
         * images
         */

        $images = $this->db()->prepare("SELECT * FROM images WHERE user_id = :id");
        $images->bindParam(':id', $id);
        $images->execute();

        return collect($user->fetch(\PDO::FETCH_ASSOC))
                ->put('tags', $tags->fetchAll(\PDO::FETCH_ASSOC))
                ->put('visits', $visits->fetchAll(\PDO::FETCH_ASSOC))
                ->put('likes', $likes->fetchAll(\PDO::FETCH_ASSOC))
                ->put('images', $images->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function show(Request $request, $id)
    {
        if ($request->has('user') && $request->get('user')['id'] != $id) {
            $user = $request->get('user')['id'];
            $created_at = Carbon::now()->toDateTimeString();

            $query = $this->db()->prepare("INSERT INTO visits (user_id, visited_by, created_at) VALUES (:user_id, :visited_by, :created_at)");
            $query->bindParam(':user_id',$id);
            $query->bindParam(':visited_by',$user);
            $query->bindParam(':created_at',$created_at);
            $query->execute();
        }

        $user = $this->fetch($id)->except(['password'])->all();

        if ($request->has('user')) {

            $liked_by = $request->get('user')['id'];

            $likes = $this->db()->prepare("SELECT * FROM `likes` WHERE user_id = :id AND liked_by = :liked_by ");
            $likes->bindParam(':id', $id);
            $likes->bindParam(':liked_by', $liked_by);
            $likes->execute();

            $user['i_like'] = $likes->rowCount() > 0;
        }

        if ($request->has('user')) {

            $reported_by = $request->get('user')['id'];

            $reports = $this->db()->prepare("SELECT * FROM `reports` WHERE user_id = :id AND reported_by = :reported_by ");
            $reports->bindParam(':id', $id);
            $reports->bindParam(':reported_by', $reported_by);
            $reports->execute();

            $user['i_report'] = $reports->rowCount() > 0;
        }

        if ($request->has('user')) {

            $blocked_by = $request->get('user')['id'];

            $likes = $this->db()->prepare("SELECT * FROM `blocks` WHERE user_id = :id AND blocked_by = :blocked_by ");
            $likes->bindParam(':id', $id);
            $likes->bindParam(':blocked_by', $blocked_by);
            $likes->execute();

            $user['i_block'] = $likes->rowCount() > 0;
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);

    }

    public function edit(Request $request, $id)
    {
        return response()->json([
            'success' => true,
            'user' => $this->fetch($id)->except(['password'])->all()
        ]);

    }

    public function report(Request $request, $id)
    {
        $user = $request->get('user')['id'];

        $created_at = Carbon::now()->toDateTimeString();

        if ($user !== $id) {
            $query = $this->db()->prepare("INSERT INTO reports (user_id, reported_by, created_at) VALUES (:user_id, :reported_by, :created_at)");
            $query->bindParam(':user_id',$id);
            $query->bindParam(':reported_by',$user);
            $query->bindParam(':created_at',$created_at);
            $query->execute();
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function block(Request $request, $id)
    {
        $user = $request->get('user')['id'];

        $created_at = Carbon::now()->toDateTimeString();

        if ($user !== $id) {
            $query = $this->db()->prepare("INSERT INTO blocks (user_id, blocked_by, created_at) VALUES (:user_id, :blocked_by, :created_at)");
            $query->bindParam(':user_id',$id);
            $query->bindParam(':blocked_by',$user);
            $query->bindParam(':created_at',$created_at);
            $query->execute();
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function unblock(Request $request, $id)
    {
        $user = $request->get('user')['id'];

        $query =  $this->db()->prepare('DELETE FROM blocks WHERE user_id = :user_id AND blocked_by = :blocked_by');
        $query->bindParam(':user_id',$id);
        $query->bindParam(':blocked_by',$user);
        $query->execute();

        return response()->json([
            'success' => true,
        ]);
    }

    public function like(Request $request, $id)
    {
        $user = $request->get('user')['id'];

        $created_at = Carbon::now()->toDateTimeString();

        if ($user !== $id) {
            $query = $this->db()->prepare("INSERT INTO likes (user_id, liked_by, created_at) VALUES (:user_id, :liked_by, :created_at)");
            $query->bindParam(':user_id',$id);
            $query->bindParam(':liked_by',$user);
            $query->bindParam(':created_at',$created_at);
            $query->execute();
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function unlike(Request $request, $id) {
        $user = $request->get('user')['id'];

        $query =  $this->db()->prepare('DELETE FROM likes WHERE user_id = :user_id AND liked_by = :liked_by');
        $query->bindParam(':user_id',$id);
        $query->bindParam(':liked_by',$user);
        $query->execute();

        return response()->json([
            'success' => true,
        ]);
    }

    public function updateGeo(Request $request) {
        $currentUser = $request->get('user');
        $this->validation($request, [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $data = $request->all();

        $query = $this->db()->prepare("UPDATE users SET lat = :lat, lng = :lng  WHERE id = :id");
        if ($query) {
            $query->bindParam(':lat', $data['lat'], \PDO::PARAM_STR);
            $query->bindParam(':lng', $data['lng'], \PDO::PARAM_STR);
            $query->bindParam(':id', $currentUser['id'], \PDO::PARAM_INT);

            $query->execute();
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $currentUser = $request->get('user');
        $data = $request->all();
        $updated_at = Carbon::now()->toDateString();

        $this->validation($request, [
            'username' => 'required|min:4|max:20|unique:users,username,' . $currentUser['id'],
            'first_name' => 'required|min:4|max:20',
            'last_name' => 'required|min:4|max:20',
            'email' => 'required|email|unique:users,email,' . $currentUser['id'],
            'password' => 'confirmation|min:8',
            'gender' => 'required|in:male,female,other,unknown',
            'sexual_orientation' => 'required|in:bisexual,homosexual,heterosexual'
        ]);

        $cols = "username = :username ," .
            "first_name = :first_name ," .
            "birthday = :birthday ," .
            "last_name = :last_name ," .
            "email = :email ," .
            "description = :description ," .
            "gender = :gender ," .
            "sexual_orientation = :sexual_orientation," .
            "updated_at = :updated_at";

        if ($request->has('password')) {
            $cols .= ", password = :password";
        }

        $query = $this->db()->prepare("UPDATE users SET {$cols}  WHERE id = :id");
        if ($query) {
            $query->bindParam(':username', $data['username'], \PDO::PARAM_STR);
            $query->bindParam(':first_name', $data['first_name'], \PDO::PARAM_STR);
            $query->bindParam(':last_name', $data['last_name'], \PDO::PARAM_STR);
            $query->bindParam(':birthday', $data['birthday'], \PDO::PARAM_STR);
            $query->bindParam(':email', $data['email'], \PDO::PARAM_STR);
            if ($request->has('password')) {
                $password = password_hash($request->get('password'), PASSWORD_DEFAULT);
                $query->bindParam(':password', $password, \PDO::PARAM_STR);
            }
            $query->bindParam(':description', $data['description'], \PDO::PARAM_STR);
            $query->bindParam(':gender', $data['gender'], \PDO::PARAM_STR);
            $query->bindParam(':sexual_orientation', $data['sexual_orientation'], \PDO::PARAM_STR);
            $query->bindParam(':updated_at', $updated_at, \PDO::PARAM_STR);

            $query->bindParam(':id', $currentUser['id'], \PDO::PARAM_INT);

            $query->execute();

            return response()->json([
                'success' => true
            ]);
        }
        return abort(500);
    }
}