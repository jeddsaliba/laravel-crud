<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\HttpServiceProvider;

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
            return response(['status' => $user->status, 'message' => $user->message], HttpServiceProvider::BAD_REQUEST);
        }
        $accessToken = $user->data->createToken('abilities', config('abilities'));
        return response(['status' => $user->status, 'message' => $user->message, 'result' => ['user' => $user->data, 'access_token' => $accessToken->plainTextToken]], HttpServiceProvider::OK);
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
    public function testThis()
    {
        
    }
}