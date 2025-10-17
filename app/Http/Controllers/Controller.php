<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function __construct()
    {
        // Check if user is not authenticated or name is null
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            if (!$user || is_null($user->name)) {
                return redirect()->route('welcome');
            }
            
            return $next($request);
        });
    }
}
