<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
{

    public function getUser() {
        return auth()->user();
    }

    public function getSignup()
    {
        return view('auth.signup');
    }

    public function postSignup(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email:rfc,dns',
            'password' => ['required', Password::min(6)->mixedCase()->numbers()],
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        Auth::loginUsingId($user->id);

        return redirect()
            ->route('main');
    }

    public function getSignin() {
        return view('auth.signin');
    }

    public function postSignin(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return redirect()
                ->back();
        }

        return redirect()
            ->route('main');
    }

    public function getSignout()
    {
        Auth::logout();

        return redirect()
            ->route('get.signin');
    }
}
