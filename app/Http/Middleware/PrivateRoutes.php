<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateRoutes
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isAdmin() || $user->isModerator()){
            return $next($request);
        }

        return redirect()->route('account-page');
    }
}
