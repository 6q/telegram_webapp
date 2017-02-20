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
		 parent::login_check();
        parent::getTotalbot_chanel();
		
		define('PAGE_DATA_LIMIT','10');
		define('PAGE_ADJACENTS','3');
        
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
     
	 	$limit = PAGE_DATA_LIMIT; 
		$adjacents = PAGE_ADJACENTS;
		$page = 1;
		
       $bots = DB::table('bots')
            ->where('user_id', '=', $user_id)
            ->get();
		
		$total_msg = array();
		if(!empty($bots)){
			foreach($bots as $k1 => $v1){
				$total_pages_tb = 0;
				$Total_transactions = DB::table('bot_messages')->where('bot_id', '=', $v1->id)->orderby('id','DESC')->get();
				$total_pages_tb = count($Total_transactions);
				$total_msg[$k1] = $total_pages_tb;

				$msg = DB::table('bot_messages')
                        ->where('bot_id', '=', $v1->id)
						->orderby('id','DESC')
						->limit($limit)
                        ->get();
				
				if(!empty($msg)){
					foreach($msg as $k2 => $v2){
						$bot_user = DB::table('bot_users')
                        ->where('id', '=', $v2->bot_user_id)
							->orderby('id','DESC')
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

		return view('front.message.index',compact('bots','total_bots','total_chanels','search','Form_action','total_msg','limit','adjacents','page')); 
        
    }
	
	
	public function paginate_bot_message(Request $request){
		$user_id = Auth::user()->id;
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT;
		$bot_id = $request->get('bot_id');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_transactions = DB::table('bot_messages')->where('bot_id', '=', $bot_id)->orderby('id','DESC')->get();
		$total_pages_tb = count($Total_transactions);

		$bots = DB::table('bots')
            ->where('user_id', '=', $user_id)
			->where('id', '=', $bot_id)
            ->get();

		$total_msg = array();
		if(!empty($bots)){
			$total_msg[0] = $total_pages_tb;
				
			$msg = DB::table('bot_messages')
					->where('bot_id', '=', $bots[0]->id)
					->orderby('id','DESC')
					->limit($limit)
					->offset($start)
					->get();

			if(!empty($msg))
			{
				foreach($msg as $k2 => $v2){
					$bot_user = DB::table('bot_users')
									->where('id', '=', $v2->bot_user_id)
									->orderby('id','DESC')
									->get();
					
					$msg[$k2]->bot_user = '';
					if(isset($bot_user[0]) && !empty($bot_user[0])){
						$msg[$k2]->bot_user = $bot_user[0]->first_name.' '.$bot_user[0]->first_name;
					}
				}
			}
			
			$bots[0]->message = $msg;
		}
		
		return view('front.message.paginate_bot_message',compact('bots','total_msg','current_page','bot_id','limit','adjacents')); 
		
	}
   
}
