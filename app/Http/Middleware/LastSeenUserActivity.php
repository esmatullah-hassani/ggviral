<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LastSeenUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $expireTime = Carbon::now()->addMinute(1);
            Cache::put('is_online'.Auth::user()->id,true,$expireTime);
            $updates = array();
           
            //Last seen
            $updates['last_seen'] = Carbon::now();
            User::where('id',Auth::user()->id)->update($updates);
        }
        return $next($request);
    }
}
