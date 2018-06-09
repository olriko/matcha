<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Authenticate
{
    private $db;

    public function __construct()
    {
        $this->db = app('db')->getPdo();
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('TOKEN')) {
            $data = (array) JWT::decode($request->header('TOKEN'), env('APP_KEY'), ['HS256']);
            if ($data) {
                $user = $this->fetch($data['user']);

                $id = $user['id'];
                $last = (new \DateTime())->format('Y-m-d H:i:s');

                $query = $this->db->prepare("UPDATE users SET last_connexion = :last_connexion  WHERE id = $id");
                $query->bindParam(':last_connexion', $last);
                $query->execute();

                if ($user) {
                    $request->request->add(['user' => $user->all()]);
                }
            }
        }
        return $next($request);
    }

    private function fetch($id): Collection
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");

        $query->bindParam(':id', $id);
        $query->execute();
        return collect($query->fetch(\PDO::FETCH_ASSOC));
    }
}
