<?php

namespace App\Http\Middleware\FoodOrder;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnProgressOrder
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = auth()->user();

        // role chef
        if($user->role_id != 2){
            return response('tidak punya hak akses', 403);
        }

        return $next($request);
    }
}
