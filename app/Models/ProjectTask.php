<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Providers\HttpServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use SoftDeletes;

    protected $table = 'project_tasks';

    protected $fillable = [
        'project_id',
        'created_by',
        'assigned_to',
        'name',
        'description',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'project_id' => 'integer',
        'created_by' => 'integer',
        'assigned_to' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'status' => 'string',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    protected $guarded = [];

    protected $appends = [
        'created_by_name',
        'assigned_to_name'
    ];

    public function getCreatedByNameAttribute()
    {
        return User::findOrFail($this->created_by)->name;
    }

    public function getAssignedToNameAttribute()
    {
        return User::findOrFail($this->assigned_to)->name;
    }

    public function list(Request $request, int $projectID)
    {
        try {
            $data = ProjectTask::where(['project_id' => $projectID])
                ->where(function ($query) use ($request) {
                    $query->when($request->q, function ($query) use ($request) {
                        $query->where("name", "like", "%$request->q%")
                            ->orWhere("description", "like", "%$request->q%")
                            ->orWhere("status", "like", "%$request->q%");
                    });
                });
            $total = $data->get()->count();
            return (object)[
                'status' => true,
                'code' => HttpServiceProvider::OK,
                'message' => $total ? 'Project task list.' : 'No record found.',
                'result' => $data->paginate($request->limit ?? 10)
            ];
        } catch (Exception $e) {
            return (object)[
                'status' => false,
                'code' => HttpServiceProvider::BAD_REQUEST,
                'message' => $e->getMessage()
            ];
        }
    }

    public function view(int $id, int $taskid)
    {
        try {
            $data = ProjectTask::where(['project_id' => $id, 'id' => $taskid])->firstOrFail();
            return (object)[
                'status' => true,
                'code' => HttpServiceProvider::OK,
                'message' => 'Project task details.',
                'result' => $data
            ];
        } catch (Exception $e) {
            return (object)[
                'status' => false,
                'code' => HttpServiceProvider::BAD_REQUEST,
                'message' => $e->getMessage()
            ];
        }
    }

    public function create(Request $request, int $userID, int $id, int $taskid = NULL)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => [
                    'required',
                    'integer'
                ],
                'assigned_to' => [
                    'required',
                    'integer'
                ],
                'name' => [
                    'required',
                    'string',
                    'min: 10',
                    'max: 150'
                ],
                'description' => [
                    'required',
                    'string',
                    'min: 10',
                    'max: 500'
                ],
                'status' => [
                    'required',
                    'string'
                ],
                'start_date' => [
                    'required',
                    'date'
                ],
                'end_date' => [
                    'required',
                    'date'
                ]
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Exception($errors->first());
            }
            $data = ProjectTask::updateOrCreate([
                'id' => $taskid,
                'project_id' => $id
            ], [
                'project_id' => $id,
                'created_by' => $userID,
                'assigned_to' => $request->assigned_to,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);
            return (object)[
                'status' => true,
                'code' => $id ? HttpServiceProvider::CREATED : HttpServiceProvider::OK,
                'message' => $id ? 'Project task updated.' : 'Project task created.',
                'result' => $data
            ];
        } catch (Exception $e) {
            return (object)[
                'status' => false,
                'code' => HttpServiceProvider::BAD_REQUEST,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteProjectTask(int $projectID, int $id)
    {
        try {
            $data = ProjectTask::where([
                'id' => $id,
                'project_id' => $projectID
            ])->firstOrFail();
            $data->delete();
            return (object)[
                'status' => true,
                'code' => HttpServiceProvider::OK,
                'message' => 'Project task deleted.',
                'result' => $data
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
