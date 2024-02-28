<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $_project;
    public function __construct(
        Project $Project
    ) {
        $this->_project = $Project;
    }
    public function list(Request $request)
    {
        $data = $this->_project->list($request);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function create(Request $request)
    {
        $data = $this->_project->create($request, Auth::user()->id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function update(Request $request, $id)
    {
        $data = $this->_project->create($request, Auth::user()->id, $id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function delete(Request $request, $id)
    {
        $data = $this->_project->deleteProject($id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
}
