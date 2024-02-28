<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;

  protected $table = 'users';

  protected $fillable = [
    'name',
    'email',
    'password',
    'email_verified_at'
  ];
  
  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'name' => 'string',
    'email' => 'string',
    'password' => 'string',
    'email_verified_at' => 'datetime'
  ];

  protected $guarded = [];

  public function login($request)
  {
    $validator = Validator::make($request->all(), [
      'email' => [
        'required',
        'email',
        'min: 8',
        'max: 100'
      ],
      'password' => [
        'required',
        'string',
        'min: 8',
        'max: 100'
      ]
    ]);
    if ($validator->fails()) {
      $errors = $validator->errors();
      return (object)['status' => false, 'message' => $errors->first()];
    }
    $login = $request->validate([
        'email' => [
            'required',
            'email',
            'min: 8',
            'max: 100'
        ],
        'password' => [
            'required',
            'string',
            'min: 8',
            'max: 100'
        ]
    ]);
    if (!Auth::attempt($login)) {
      return (object)['status' => false, 'message' => 'Invalid login credentials.'];
    }
    $user = Auth::user();
    if (!$user) {
      return (object)['status' => false, 'message' => 'Invalid login credentials.'];
    }
    $user = User::find(Auth::user()->id);
    return (object)['status' => true, 'message' => 'Welcome, ' . $user->name . '!', 'data' => $user];
  }
}