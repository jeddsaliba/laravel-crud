<?php
namespace App\Http\Middleware\HashId;

use Closure;
use Illuminate\Http\Request;
use Hashids\Hashids;
use App\Providers\HttpServiceProvider;

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
            return response(['status' => false, 'code' => HttpServiceProvider::NOT_FOUND, 'message' => HttpServiceProvider::NOT_FOUND_MESSAGE, 'result' => NULL], HttpServiceProvider::NOT_FOUND);
        }
    }
}
?>