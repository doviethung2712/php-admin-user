<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\UserOperationLog;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{

    protected array $unsets = ['otp', 'password', 'new_password', 'current_password', 'token'];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $id = 0;
        if (isset($user)) {
            $id = $user->id;
        }
        $input = $request->input();
        foreach ($this->unsets as $unset) {
            unset($input[$unset]);
            unset($input[$unset . '_confirmation']);
        }

        try {
            $log = new UserOperationLog();
            $log->user_id = $id;
            $log->path = substr($request->path(), 0, 255);
            $log->method = $request->method();
            $log->ip = $request->getClientIp();
            $log->input = json_encode($input);
            $log->save();
        } catch (\Exception $exception) {
            // pass
        }

        return $next($request);
    }
}
