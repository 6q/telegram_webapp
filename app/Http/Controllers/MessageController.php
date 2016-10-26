<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Requests\FrontUserCreateRequest;
use App\Models\FrontUser;

class MessageController extends Controller
{
     public function __construct() {
        parent::getTotalbot_chanel();
        
    }
    
    
    public function index(){
        $user_id = Auth::user()->id;
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
		
		$Form_action = 'message';
        $search = '';
        if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
        }
     
       $bots = DB::table('bots')
            ->where('user_id', '=', $user_id)
            ->get();
		
		if(!empty($bots)){
			foreach($bots as $k1 => $v1){
				$msg = DB::table('bot_messages')
                        ->where('bot_id', '=', $v1->id)
                        ->get();
				
				if(!empty($msg)){
					foreach($msg as $k2 => $v2){
						$bot_user = DB::table('bot_users')
                        ->where('id', '=', $v2->bot_user_id)
                        ->get();
						
						$msg[$k2]->bot_user = '';
						if(isset($bot_user[0]) && !empty($bot_user[0])){
							$msg[$k2]->bot_user = $bot_user[0]->first_name.' '.$bot_user[0]->first_name;
						}
					}
				}
				
				$bots[$k1]->message = $msg;
			}
		}

		return view('front.message.index',compact('bots','total_bots','total_chanels','search','Form_action')); 
        
    }
   
}
