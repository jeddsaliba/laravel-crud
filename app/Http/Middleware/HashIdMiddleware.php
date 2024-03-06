<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Hashids\Hashids;

class HashIdMiddleware {
    public function handle(Request $request, Closure $next)
    {
        try {
            $payload = $request->route()->parameters();
            $hashids = new Hashids(env('HASH_SALT'), env('HASH_MIN_LENGTH'), env('HASH_ALPHABET'));
            collect((object)$payload)->each(function ($param, $key) use ($hashids, $request) {
                $decodedID = $hashids->decode($param)[0];
                $request->route()->setParameter($key, $decodedID);
            });
            return $next($request);
        } catch (\Exception $e) {
            return response(['status' => false, 'code' => 400, 'message' => $e->getMessage(), 'result' => NULL], 400);
        }
    }
}
?>