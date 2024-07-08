<?php

namespace App\Http\Middleware\FoodOrder;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdatePayOrder
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = auth()->user();

        //hanya chasier dan manager
        if($user->role_id != 3 && $user->role_id != 4){
            return response('tidak punya hak akses', 403);
        }

        return $next($request);
    }
}
