<?php

namespace App\Http\Middleware\FoodOrder;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinishOrder
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = auth()->user();
        // return response($user);
        if($user->role_id != 2){
            return response('tidak punya hak akses', 403);
        }

        return $next($request);
    }
}
