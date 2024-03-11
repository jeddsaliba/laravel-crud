<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Project,
    ProjectTask
};

class ReportController extends Controller
{
    private $_project;
    private $_projectTask;
    public function __construct(
        Project $Project,
        ProjectTask $ProjectTask
    ) {
        $this->_project = $Project;
        $this->_projectTask = $ProjectTask;
    }
    public function project(Request $request)
    {
        $data = $this->_project->reportProject($request);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function view(Request $request, $id)
    {
        $data = $this->_project->view($id);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
}
