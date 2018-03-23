<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function login(Request $request)
    {

    }


    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|min:4|max:20',
            'last_name' => 'required|min:4|max:20',
            'email' => 'required|email',
            'password' => 'required|confirmation|min:8',
            'gender' => 'required|in:male,female,unknown',
            'sexual_orientation' => 'required|in:bisexual,homosexual,heterosexual'
        ]);

        $this->db()
            ->prepare(
                'INSERT INTO users (first_name, last_name, email, password, gender, sexual_orientation) VALUES (:first_name, :last_name, :email, :password, :gender, :sexual_orientation)')
            ->bindParam(':first_name', $request->get('first_name'))
            ->bindParam(':last_name', $request->get('last_name'))
            ->bindParam(':email', $request->get('email'))
            ->bindParam(':password', password_hash($request->get('password'), PASSWORD_BCRYPT))
            ->bindParam(':first_name', $request->get('first_name'))
            ->execute();

        return redirect()->route('home');
    }

    public function logout()
    {

    }
}
