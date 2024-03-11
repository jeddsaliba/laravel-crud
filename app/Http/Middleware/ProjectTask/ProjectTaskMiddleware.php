<?php
namespace App\Http\Middleware\ProjectTask;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\HttpServiceProvider;

class ProjectTaskMiddleware {
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!Auth::user()->tokenCan('project-task')) {
                $user = auth()->user();
                $user->currentAccessToken()->delete();
                throw new \Exception(HttpServiceProvider::FORBIDDEN_ACCESS_MESSAGE);
            }
            return $next($request);
        } catch (\Exception $e) {
            return response(['status' => false, 'code' => $e->getMessage(), 'message' => HttpServiceProvider::FORBIDDEN_ACCESS_MESSAGE, 'result' => NULL], HttpServiceProvider::FORBIDDEN_ACCESS);
        }
    }
}
?>