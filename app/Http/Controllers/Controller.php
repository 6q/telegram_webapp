<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use DB;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $botsTOTAL;
    public $chanelTOTAL;

    public function getTotalbot_chanel(){
        if (Auth::check()){
            $userId = Auth::user()->id;
            $this->botsTOTAL = '';
            $this->chanelTOTAL = '';
            if(isset($userId) && !empty($userId))
            {
                $us_conditions = ['user_id' => $userId];
                $this->botsTOTAL = DB::table('bots')->where($us_conditions)->get();

                $chanel_conditions = ['user_id' => $userId];
                $this->chanelTOTAL = DB::table('my_channels')->where($us_conditions)->get();
            }
        }        
    }
	
	public function login_check(){
		if (Auth::check()){
		}
		else{
			header('Location: /');
			die();
		}
	}
}
