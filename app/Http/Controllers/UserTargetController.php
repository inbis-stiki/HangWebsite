<?php

namespace App\Http\Controllers;

use App\UserTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Session;

class UserTargetController extends Controller
{
    
    public function index(){      
        $data['users']      = DB::table('user_target')->join('user', 'user_target.ID_USER', 'user.ID_USER')->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')->select('*')->get();
        
        $data['title']      = "User Target";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "usertarget";

        return view('master.usertarget.usertarget', $data);
    }
}