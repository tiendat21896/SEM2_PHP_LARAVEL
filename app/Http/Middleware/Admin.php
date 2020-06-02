<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        //
        if (!Auth::check())
            return redirect()->to(login);
        // Nếu chưa thì trả về trang đăng nhập
        $currentUser = Auth::user();
        // lấy đối tượng user từ trang login
        if($currentUser->__get("role") != User::ADMIN_ROLE)
            return abort(404);// Nếu khác trả về 404
        return $next($request);
    }
}
