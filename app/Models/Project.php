<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Providers\HttpServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'created_by',
        'name',
        'description'
    ];

    protected $casts = [
        'created_by' => 'integer',
        'name' => 'string',
        'description' => 'string'
    ];

    protected $guarded = [];
    protected $appends = [
        'created_by_name'
    ];

    protected function tasks()
    {
        return $this->hasMany(\App\Models\ProjectTask::class, 'project_id', 'id');
    }

    public function getCreatedByNameAttribute()
    {
        return User::findOrFail($this->created_by)->name;
    }

    public function list(Request $request)
    {
        try {
            $data = Project::when($request->q, function ($query) use ($request) {
                $query->where("name", "like", "%$request->q%")
                    ->orWhere("description", "like", "%$request->q%");
            })->when($request->sort && $request->direction, function ($query) use ($request) {
                $query->orderBy($request->sort, $request->direction);
            });
            $total = $data->get()->count();
            return (object)[
                'status' => true,
                'code' => HttpServiceProvider::OK,
                'message' => $total ? 'Project list.' : 'No record found.',
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

    public function view(int $id)
    {
        try {
            $data = Project::findOrFail($id);
            return (object)[
                'status' => true,
                'code' => HttpServiceProvider::OK,
                'message' => 'Project details.',
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

    public function create(Request $request, $userID, $id = NULL)
    {
        try {
            $validator = Validator::make($request->all(), [
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
                ]
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                throw new Exception($errors->first());
            }
            $data = Project::when($id, function ($query) use ($id) {
                $query->where(['id' => $id]);
            })->first();
            $data = Project::updateOrCreate([
                'id' => $id
            ], [
                'created_by' => $id ? $data->created_by : $userID,
                'name' => $request->name,
                'description' => $request->description
            ]);
            return (object)[
                'status' => true,
                'code' => $id ? HttpServiceProvider::CREATED : HttpServiceProvider::OK,
                'message' => $id ? 'Project updated.' : 'Project created.',
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

    public function deleteProject($id)
    {
        try {
            $data = Project::findOrFail($id);
            collect($data->tasks)->each(function ($task) {
                $task->delete();
            });
            $data->delete();
            return (object)[
                'status' => true,
                'code' => HttpServiceProvider::OK,
                'message' => 'Project deleted.',
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
    public function reportProject(Request $request)
    {
        try {
            return (object)[
                'status' => true,
                'code' => HttpServiceProvider::OK,
                'message' => 'Project deleted.',
                'result' => []
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
