<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use App\Models\{
    Project,
    ProjectTask
};
use Illuminate\Http\Request;
class ChartController extends Controller
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
    public function status()
    {
        $data = $this->_projectTask->status();
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function topPerformers()
    {
        $data = $this->_projectTask->topPerformers();
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
    public function performance(Request $request)
    {
        $data = $this->_projectTask->performance($request->year);
        return response(['status' => $data->status, 'code' => $data->code, 'message' => $data->message, 'result' => $data->result ?? NULL], $data->code);
    }
}
