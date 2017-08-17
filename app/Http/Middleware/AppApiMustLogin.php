<?php

namespace App\Http\Middleware;

use Session;
use Closure;
use Cache;

class AppApiMustLogin
{

    public function handle($request, Closure $next){

        $token = $request->input('token');

        if(Cache::get($token) === null){

            $data = array(
                'code'    => "4006",
                'message' => '用户未登录',
                'result'  => [],
            );

            if (!headers_sent()) {
                header(sprintf('%s: %s', 'Content-Type', 'application/json'));
            }
            return json_encode($data);
        }

        return $next($request);
    }
}
