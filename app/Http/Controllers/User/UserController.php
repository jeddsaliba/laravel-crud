<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $_user;
    public function __construct(
        User $User
    ) {
        $this->_user = $User;
    }
    public function list(Request $request)
    {
        $data = $this->_user->list($request);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function create(Request $request)
    {
        $data = $this->_user->create($request, Auth::user()->id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function update(Request $request, $id)
    {
        $data = $this->_user->create($request, Auth::user()->id, $id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function delete(Request $request, $id)
    {
        $data = $this->_user->deleteProject($id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
}
