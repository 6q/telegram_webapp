<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

class SettingController extends Controller
{
	public function __construct() {
		 parent::login_check();
    }
	
    public function index(){
        $setting = DB::table('site_settings')->get();
		return view('back.setting.index', compact('setting'));	
    }
    
    public function store(){
		if(isset($_POST) && !empty($_POST)){
			foreach($_POST as $k1 => $v1){
				if($k1 != $_POST['_token']){
					DB::table('site_settings')->truncate();
					
					DB::table('site_settings')->insert(
						['name' => $k1, 'value' => $v1]
					);	
				}
			}
			
			 return redirect('setting')->with('ok', trans('back/setting.created'));
		}
	}
}
