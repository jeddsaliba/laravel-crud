<?php

namespace App\Http\Controllers\ProjectTask;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Hashids\Hashids;

class ProjectTaskController extends Controller
{
    private $_projectTask;
    public function __construct(
        ProjectTask $ProjectTask
    ) {
        $this->_projectTask = $ProjectTask;
    }
    public function list(Request $request, int $projectID)
    {
        $data = $this->_projectTask->list($request, $projectID);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function view(Request $request, int $id, int $taskid)
    {
        $data = $this->_projectTask->view($id, $taskid);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function create(Request $request, int $id)
    {
        $data = $this->_projectTask->create($request, Auth::user()->id, $id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function update(Request $request, int $id, int $taskid)
    {
        $data = $this->_projectTask->create($request, Auth::user()->id, $id, $taskid);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function delete(Request $request, int $projectID, int $id)
    {
        $data = $this->_projectTask->deleteProjectTask($projectID, $id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
}
