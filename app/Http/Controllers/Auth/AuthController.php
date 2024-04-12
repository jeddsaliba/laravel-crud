<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\HttpServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private $_user;
    public function __construct(
        User $User
    ) {
        $this->_user = $User;
    }
    public function login(Request $request)
    {
        $user = $this->_user->login($request);
        if (!$user->status) {
            if ($request->type && $user->errors) {
                return redirect('login')->withErrors($user->errors);
            }
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        $accessToken = $user->data->createToken('abilities', config('abilities'));
        return response(['status' => $user->status, 'message' => $user->message, 'result' => ['user' => $user->data, 'access_token' => $accessToken->plainTextToken]], HttpServiceProvider::OK);
    }
    public function user(Request $request)
    {
        if (!Auth::user()->tokenCan('auth-user')) {
            return response(['status' => false, 'message' => HttpServiceProvider::FORBIDDEN_ACCESS_MESSAGE], HttpServiceProvider::FORBIDDEN_ACCESS);
        }
        $user = $this->_user->user();
        return response(['status' => $user->status, 'message' => $user->message, 'result' => ['user' => $user->data]], HttpServiceProvider::OK);
    }
    public function logout(Request $request)
    {
        if (!Auth::user()->tokenCan('auth-logout')) {
            return response(['status' => false, 'message' => HttpServiceProvider::FORBIDDEN_ACCESS_MESSAGE], HttpServiceProvider::FORBIDDEN_ACCESS);
        }
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return response(['status' => true, 'message' => 'Logout successful.'], HttpServiceProvider::OK);
    }
    public function loginPage()
    {
        return view('pages.auth.login.index');
    }
    public function loginRequest(Request $request)
    {
        return $this->login($request);
        // $user = $this->_user->login($request);
        // if (!$user->status) {
        //     if ($user->errors) {
        //         return back()->withErrors($user->errors);
        //     }
        // }
    }
}