<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;  // inject User model into controller 
    }
    
    public function index() {
        $authuser = Auth::user();
        $user = $this->user->where('id','!=',$authuser->id)->get();
        return response()->json([
            'user' => $user
        ], 200);
    }

    public function me() {
        $user =  Auth::user();
        return response()->json([
            'user' => $user
        ], 200);
    }
}
