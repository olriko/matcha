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

    private function fetch($username) : Collection
    {
        $query = $this->db()->prepare("SELECT * FROM users WHERE username = :username");

        $query->bindParam(':username', $username);
        $query->execute();
        return collect($query->fetch(\PDO::FETCH_ASSOC));
    }

    public function login(Request $request)
    {
        $this->validation($request, [
            'username' => 'required|min:4|max:20|exists:users,username',
            'password' => 'required|min:8',
        ]);

        $user = $this->fetch($request->get('username'));

        if ($user->isNotEmpty()) {

            if (!$user->get('is_activated')) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'Account not activated'
                    ]
                ], 422);
            } else if (password_verify($request->get('password'), $user->get('password')))
            {
                $jwt = JWT::encode([
                    'iat' =>  Carbon::now()->timestamp,
                    'exp' =>  Carbon::now()->addDay()->timestamp,
                    'user' => $user->get('id'),
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
            'password' => 'required|password|confirmation|min:8',
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

            $user = $this->db()->prepare("SELECT * FROM users WHERE email = :email");
            $user->bindParam(':email', $data['email']);
            $user->execute();
            $dataUser = $user->fetch(2);

            $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $token = sha1($data['email'] . env('APP_KEY'));
            $message = "Confirm <a href=\"http://localhost:8000/api/activate-account/{$dataUser['id']}/$token\">HERE</a>";


            mail($data['email'], 'Matcha : Register confirm your email !', $message, $headers);

            return response()->json([
                'success' => true
            ]);
        }
    }

    public function active($id, $token)
    {
        $user = $this->db()->prepare("SELECT * FROM users WHERE id = :id");
        $user->bindParam(':id', $id);
        $user->execute();
        $dataUser = $user->fetch(2);

        $token_tmp = sha1($dataUser['email'] . env('APP_KEY'));

        if ($token_tmp === $token) {
            $query = $this->db()->prepare('UPDATE users SET is_activated = 1 WHERE id = :id');
            $query->bindParam(':id', $id);
            $query->execute();

            return redirect('/#login');
        } else {
            return redirect('/');
        }
    }

    public function recover(Request $request)
    {

        $this->validation($request, [
            'email' => 'required|email',
        ]);
        $email = $request->get('email');

        $user = $this->db()->prepare("SELECT * FROM users WHERE email = :email");
        $user->bindParam(':email', $email);
        $user->execute();
        $userInDb = $user->fetch(2);

        if (isset($userInDb)) {
            $token = sha1($userInDb['email'] . env('APP_KEY'));
            $to = $userInDb['email'];
            $subject = 'Recover password';
            $userId = $userInDb['id'];
            $email = $userInDb['email'];
            $message = "change your password <a href=\"http://localhost:8000/#/change-password/$userId/$token\">HERE</a>";
            $headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $done = mail($email, $subject, $message, $headers);

            return response()->json([
                            'success' => true,
                            'done' => $done
             ]);
        }

        return response()->json([
                        'success' => false
                    ], 404);
    }

    public function newPassword(Request $request)
    {
        $id = $request->get('id');
        $token = $request->get('token');

        $user = $this->db()->prepare("SELECT * FROM users WHERE id = :id");
                $user->bindParam(':id', $id);
                $user->execute();
        $userInDb = $user->fetch(2);

        $this->validation($request, [
            'password' => 'required|password|confirmation|min:8',
        ]);

        if (isset($userInDb)) {
            $token2 = sha1($userInDb['email'] . env('APP_KEY'));

            if ($token === $token2) {
                $password = password_hash($request->get('password'), PASSWORD_DEFAULT);

                $req = $this->db()->prepare("UPDATE users SET password = :password WHERE id = :id");
                $req->bindParam(':password', $password);
                $req->bindParam(':id', $id);
                $req->execute();

                return response()->json([
                    'success' => true
                ]);
            }
            return response()->json([
                'success' => false,
                'error' => 'tokenError'
            ], 400);
        }

        return response()->json([
            'success' => false
        ], 400);
    }
}
