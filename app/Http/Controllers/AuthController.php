<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Collection;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    protected $hidden = [
        'password'
    ];

    private function fetch($email) : Collection
    {
        $query = $this->db()->prepare("SELECT * FROM users WHERE email = :email");

        $query->bindParam(':email', $email);
        $query->execute();
        return collect($query->fetch(\PDO::FETCH_ASSOC));
    }

    public function login(Request $request)
    {
        $this->validation($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        $user = $this->fetch($request->get('email'));

        if ($user->isNotEmpty()) {
            if (password_verify($request->get('password'), $user->get('password')))
            {
                $jwt = JWT::encode([
                    'iat' =>  Carbon::now()->timestamp,
                    'exp' =>  Carbon::now()->addDay()->timestamp,
                    'id' => $user->get('id'),
                    'name' => $user->get('name'),
                ], env('APP_KEY'));

                return response()->json([
                    'success' => true,
                    'jwt' => $jwt,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'Wrong password'
                    ]
                ], 422);
            }
        }

        return;
    }


    public function register(Request $request)
    {
        $this->validation($request, [
            'username' => 'required|min:4|max:20|unique:users,username',
            'first_name' => 'required|min:4|max:20',
            'last_name' => 'required|min:4|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmation|min:8',
            'gender' => 'required|in:male,female,other,unknown',
            'sexual_orientation' => 'required|in:bisexual,homosexual,heterosexual'
        ]);

        $data = $request->all();
        $password = password_hash($request->get('password'), PASSWORD_DEFAULT);
        $created_at = Carbon::now()->toDateString();
        $query = $this->db()->prepare(
            'INSERT INTO users (username, first_name, last_name, email, password, gender, sexual_orientation, created_at) VALUES (:username, :first_name, :last_name, :email, :password, :gender, :sexual_orientation, :created_at)');
        if ($query) {
            $query->bindParam(':username', $data['username']);
            $query->bindParam(':first_name', $data['first_name']);
            $query->bindParam(':last_name', $data['last_name']);
            $query->bindParam(':email', $data['email']);
            $query->bindParam(':password', $password);
            $query->bindParam(':gender', $data['gender']);
            $query->bindParam(':sexual_orientation', $data['sexual_orientation']);
            $query->bindParam(':created_at', $created_at);
            $query->execute();

            return response()->json([
                'success' => true
            ]);
        }
    }

    public function recover(Request $request)
    {

    }
}
