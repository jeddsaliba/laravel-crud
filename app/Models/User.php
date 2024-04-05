<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Providers\HttpServiceProvider;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable, SoftDeletes;

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
  public function user()
  {
    try {
      $user = auth()->user();
      return (object)[
        'status' => true,
        'code' => HttpServiceProvider::OK,
        'message' => 'Logged in user',
        'data' => $user
      ];
    } catch (Exception $e) {
      return (object)[
        'status' => false,
        'code' => HttpServiceProvider::BAD_REQUEST,
        'message' => $e->getMessage()
      ];
    }
  }
  public function list(Request $request)
  {
    try {
      $data = User::when($request->q, function ($query) use ($request) {
        $query->where("name", "like", "%$request->q%")
          ->orWhere("email", "like", "%$request->q%");
      })->when($request->sort && $request->direction, function ($query) use ($request) {
        $query->orderBy($request->sort, $request->direction);
      });
      $total = $data->get()->count();
      return (object)[
        'status' => true,
        'code' => HttpServiceProvider::OK,
        'message' => $total ? 'User list.' : 'No record found.',
        'result' => collect($request->all())->isEmpty() ? ['data' => $data->get()] : $data->paginate($request->limit ?? 10)
      ];
    } catch (Exception $e) {
      return (object)[
        'status' => false,
        'code' => HttpServiceProvider::BAD_REQUEST,
        'message' => $e->getMessage()
      ];
    }
  }
}
