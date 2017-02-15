<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;
use Telegram\Bot\Api;

class DashboardController extends Controller {

    protected $nbrPages;

    /**
     * Create a new BlogController instance.
     *
     * @param  App\Repositories\BlogRepository $blog_gestion
     * @param  App\Repositories\UserRepository $user_gestion
     * @return void
     */
    public function __construct() {
		parent::login_check();
        $this->nbrPages = 2;

        $this->middleware('user');
        
        parent::getTotalbot_chanel();
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return Redirection
     */
    public function index() {

        //$response = $telegram->setWebhook(['url' => 'http://local.citymes/224395586:AAGQE4hkQbS1hG2_XkflPldqMBP5jyqEOho/get_updates']);
        //$response = $telegram->getMe();
        //echo $botId = $response->getId();
        /* $botId = $response->getId();
          $firstName = $response->getFirstName();
          $username = $response->getUsername();
         */
        /* $response = $telegram->sendMessage([
          'chat_id' => '203633121',
          'text' => 'this is test'
          ]); */

        /*
          $telegram = new Api('258867258:AAEClGhWQUfo72WH6ZivfgPUtOC0eGRU2sQ');
          //$response = $telegram->setWebhook(['url' => 'https://laravel-setjeetu.c9users.io/public/258867258:AAEClGhWQUfo72WH6ZivfgPUtOC0eGRU2sQ/webhook']);


          $response = $telegram->getMe();
          $botId = $response->getId();
          $keyboard = [

          ['Autoresponses', 'Contact Forms'],
          ['Galleries', 'Channels'],

          ];

          $reply_markup = $telegram->replyKeyboardMarkup([
          'keyboard' => $keyboard,
          'resize_keyboard' => true,
          'one_time_keyboard' => false
          ]);
         */

        /* $response = $telegram->sendMessage([
          'chat_id' => '203633121',
          'text' => 'Hi ',
          'reply_markup' => $reply_markup
          ]);
          echo '<pre>';
          print_r($response);
          exit;
         */

        //    echo "<pre>"; print_r($chanel); die;
        
        /*
        $token = '253093754:AAEJEqsfmfsqWJNs6o25vjqQ_20tYOzx7L0';
        //$telegram = new Api($token);
        //$response = $telegram->getMe();
        //echo '<pre>';print_r($response);die;
        $telegram = new Api($token);
        
        $data = [];
       // $data['chat_id'] = '@mychannelnew';
        $data['chat_id'] = '265459750';
        $data['text'] = 'Hello';
        $result = $telegram->sendMessage($data);
        echo '<pre>';print_r($result);die;
        if ($result->isOk()) {
            echo 'Message sent succesfully to: '.$data['chat_id'] ;
        } else {
            echo 'Sorry message not sent to: '.$data['chat_id'] ;
        }
        
        */
        
        
        $userId = Auth::user()->id;
        $Form_action = 'dashboard';
        $search = '';
        if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
        }
        
        $startDate = date('Y-m-d', strtotime('today - 30 days'));
        $endDate = date('Y-m-d');
        
        $startDateTime = date('Y-m-d h:i:s', strtotime('today - 30 days'));
        $endDateTime = date('Y-m-d h:i:s');
        
		$bots = DB::table('bots')
            ->where('user_id', '=', $userId)
            ->get();
			
        if(!empty($bots)){
            foreach($bots as $k1 => $v1){
                $msg = DB::table('bot_messages')
                        ->where('bot_id', '=', $v1->id)
                        ->whereBetween('date', array($startDateTime, $endDateTime))
                        ->get();

                $bot_total_msg = count($msg);
                $bots[$k1]->total_msg = $bot_total_msg;

	            $usrs = DB::table('bot_users')
		            ->where('bot_id', '=', $v1->id)
		            ->get();


	            $bot_total_usrs = count($usrs);
	            $bots[$k1]->total_usrs = $bot_total_usrs;

            }

        }
        
        /* Chanels */
        if(!empty($search)){
            $chanel = DB::table('my_channels')
            ->where('user_id', '=', $userId)
            ->where('name', 'LIKE', '%'.$search.'%')
            ->get();
        }
        else{
            $chanel = DB::table('my_channels')
            ->where('user_id', '=', $userId)
            ->get();
        }     
        
		$startDate_thisMonth = date('Y-m-01');
        $endDate_thisMonth = date('Y-m-t');
				
        if(!empty($chanel)){
            foreach($chanel as $ch1 => $cv1){
                $send_message = DB::table('channel_send_message')
                                    ->where('channel_id','=',$cv1->id)
									->whereBetween('send_date', array($startDate_thisMonth, $endDate_thisMonth))
                                    ->get();
                $total_msg = count($send_message);
                $chanel[$ch1]->total_msg = $total_msg;
            }
        }
        
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        /* Last 30 Days recive message */
        $botId = '';
		if(!empty($total_bots)){
			foreach($total_bots as $k1 => $v1){
				$botId[] = 	$v1->id;
			}
		}
        
        $recivedMsg = DB::table('bot_messages')
                        ->whereIn('bot_id',$botId)
                        ->whereBetween('date', array($startDateTime, $endDateTime))
                        ->get();
        /*****************************/
        
        
        /** RECENT ACTITVITY **/
        $contactForms = DB::table('contact_forms')
                            ->whereIn('type_id',$botId)
                            ->get();
        
        $contactFormId = '';
        if(!empty($contactForms)){
            foreach($contactForms as $k2 => $v2){
                $contactFormId[] = $v2->id;
            }
        }
        $ques_heading = '';
        if(!empty($contactFormId)){
            $cf_ques = DB::table('contact_form_questions')
                        ->whereIn('contact_form_id',$contactFormId)
                        ->get();
            if(!empty($cf_ques)){
                foreach($cf_ques as $k2 => $v2){
                    $ques_heading[] = $v2->ques_heading;
                }
            }
        }
        
       $rec_msg = '';
        if(!empty($ques_heading)){
            $rec_msg = DB::table('bot_messages')
                            ->whereIn('bot_id',$botId)
                            ->whereIn('reply_message',$ques_heading)
                            ->orderBy('id','desc')
	                        ->limit(10)
                            ->get();    
        }
	    
		/*
		$rec_usrs = DB::table('bot_users')
		    ->whereIn('bot_id',$botId)
		    ->leftJoin('bots', 'bot_users.bot_id', '=', 'bots.id')
		    ->orderBy('bot_users.id','desc')
		    ->limit(10)
		    ->get();
		*/
		
		/* Recent Activity */
		if(!empty($search)){
			$rec_usrs = DB::table('bot_users')
				->whereIn('bot_users.bot_id',$botId)
				->Where('bots.username', 'LIKE', '%'.$search.'%')
				->orWhere('bot_users.first_name', 'LIKE', $search)
				->join('bots', 'bot_users.bot_id', '=', 'bots.id')
				->select('bot_users.*', 'bots.id as bot_id','bots.username as bot_username')
				->orderBy('bot_users.id','desc')
				->limit(10)
				->get();
		}
		else{
			$rec_usrs = DB::table('bot_users')
				->whereIn('bot_id',$botId)
				->join('bots', 'bot_users.bot_id', '=', 'bots.id')
				->select('bot_users.*', 'bots.id as bot_id','bots.username as bot_username')
				->orderBy('bot_users.id','desc')
				->limit(10)
				->get();
		}



	    /*
		$rec_msg = DB::table('bot_messages')
						->whereIn('bot_id',$botId)
						->orderBy('id','desc')
						->limit(6)
						->get();
		*/
        /*****************************/
        
        return view('front.dashboard.index', compact('bots','chanel','total_bots', 'total_chanels','Form_action','search','startDate','endDate','recivedMsg','rec_msg','rec_usrs'));
    }
    
    public function getcharts(Request $request){
        //echo '<pre>';print_r($request->all());die;
        $userId = Auth::user()->id;
        $bot_id = ($request->get('bot_id') && !empty($request->get('bot_id')))?$request->get('bot_id'):'';
        $chart_time = ($request->get('chart_time') && !empty($request->get('chart_time')))?$request->get('chart_time'):'';
        $chart_details = ($request->get('chart_details') && !empty($request->get('chart_details')))?$request->get('chart_details'):'';
        
        $startDate = date('Y-m-d', strtotime('today - 30 days'));
        $endDate = date('Y-m-d');
        $output_format = 'Y-m-d';
        $step = '+1 day';
        
        
         $bots = DB::table('bots')
            ->where('user_id', '=', $userId)
            ->get();
        
        $bot_id_arr = '';
        $arr = '';
        foreach($bots as $k1 => $v1){
            $bot_id_arr[] = $v1->id;
        }
        
        
        if($chart_time == '10_days'){
            $day = date('w');
            $startDate = date('Y-m-d',strtotime('today - 7 days'));
            $endDate = date('Y-m-d');
        }

        if($chart_time == '30_days'){
            $month = date('m');
            $year = date('Y');

            $startDate = date('d-m-Y',strtotime('today - 30 days'));
            $endDate = date('d-m-Y');
        }

        if($chart_time == '90_days'){
	        $startDate = date('d-m-Y',strtotime('today - 90 days'));
	        $endDate = date('d-m-Y');
        }
        
        
        $arr_dates = $this->date_range($startDate,$endDate,$step,$output_format);
        $i = 0;

        $arr[$i][0] = '';
		$arr[$i][1] = 'Bot seleccionat';
		
		if(!empty($bot_id) && $bot_id == 'all_bots' && ($chart_details == 'recieved_messages' || $chart_details == 'send_messages' || $chart_details == 'active_users')){
			$j = 1;
			foreach($bots as $k1 => $v1){			
				$arr[$i][$j] = $v1->username;
				$j++;
			}
		}
				
        
		$i = 1;
		
        foreach($arr_dates as $k1 => $v1){
            if(!empty($bot_id) && $bot_id == 'all_bots' && ($chart_details == 'recieved_messages' || $chart_details == 'send_messages')){
				$j = 1;
				foreach($bots as $bk1 => $bv1){
					$count_bot = DB::table('bot_messages')
                                ->where('bot_id',$bv1->id)
                                ->where('date','LIKE','%'.$v1.'%')
                                ->get();
					
					$arr[$i][0] = date('d.m',strtotime($v1));			
					$arr[$i][$j] = count($count_bot);	
					$j++;
				}
				$i++;
            }
            else if(!empty($bot_id) && $bot_id != 'all_bots' && ($chart_details == 'recieved_messages' || $chart_details == 'send_messages')){
                $count_bot = DB::table('bot_messages')
                                ->where('bot_id','=',$bot_id)
                                ->where('date','LIKE','%'.$v1.'%')
                                ->get();
				
				$arr[$i][0] = date('d.m',strtotime($v1));
				$arr[$i][1] = count($count_bot);	
				$i++;			
            }
            else if(!empty($bot_id) && $bot_id == 'all_bots' && $chart_details == 'active_users'){
				$j = 1;
				foreach($bots as $bk1 => $bv1){					
               		$count_bot = DB::table('bot_users')
                                ->where('bot_id',$bv1->id)
                                ->where('created_at','<=',$v1)
                                ->get();			
					
					$arr[$i][0] = date('d.m',strtotime($v1));			
					$arr[$i][$j] = count($count_bot);	
					$j++;
				}
				$i++;
            }
            else if(!empty($bot_id) && $bot_id != 'all_bots' && $chart_details == 'active_users'){
                $count_bot = DB::table('bot_users')
                                ->where('bot_id','=',$bot_id)
                                ->where('created_at','<=',$v1)
                                ->get();
								
				$arr[$i][0] = date('d.m',strtotime($v1));
				$arr[$i][1] = count($count_bot);	
				$i++;				
            }
        }
        
        $arr = json_encode($arr);
        echo $arr;die;
    }

    public function get_updates() {
        $telegram = new Api('224395586:AAGQE4hkQbS1hG2_XkflPldqMBP5jyqEOho');
        $updates = $telegram->getUpdates();
        file_put_contents(public_path() . '/updates.txt', json_encode($updates));
        echo '<pre>';
        print_r($updates);
        exit;
    }
    
    
    function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while( $current <= $last ) {
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }
    
    public function sendmessage(Request $request){
        $bot_token = '';
        $channel_name = '';
        $perDaySendMesgLimit = '';
        
        $message = $request->get('channel_msg');
        
        if($request->get('botID')){
            $bot_data = DB::table('bots')
                            ->where('id','=',$request->get('botID'))
                            ->get();
            
            $bot_token = $bot_data[0]->bot_token;
        }
		
		$img_url = '';
		if($request->hasFile('channel_image')){
			$error_img = $_FILES["channel_image"]["error"];
			$img_name = $_FILES["channel_image"]["name"];

			if($error_img == '0' && $img_name != '' )
			{
			   $img_path = $_FILES["channel_image"]["tmp_name"];
			  // $img_name_s = time()."_".$img_name;
			   $ext = pathinfo($img_name);
			   $img_name_s = time().'.'.$ext['extension'];
			   $upload_img = public_path().'/uploads/'.$img_name_s;

			   move_uploaded_file($img_path,$upload_img);
			   $img_url = $upload_img;
			}
		}
		
        
        if($request->get('chat_id')){
            $channel_data = DB::table('my_channels')
                                ->where('id','=',$request->get('chat_id'))
                                ->get();
            $channel_name = $channel_data[0]->name;
            
            
            $checkData = DB::table('user_subscriptions')
                            ->where('types','=','Channel')
                            ->where('type_id','=',$request->get('chat_id'))
                            ->get();
            
            $planId = $checkData[0]->plan_id;
            
            $plan = DB::table('plans')
                        ->where('id','=',$planId)
                        ->get();
            
            $perDaySendMesgLimit = $plan[0]->manual_message;

                $chkData = DB::table('channel_send_message')
                    ->where('channel_id','=',$request->get('chat_id'))
                    ->where('send_date','=',date('Y-m-d'))
                    ->get();

            
            $totalCount = count($chkData);
            if($totalCount >= $perDaySendMesgLimit){
                echo trans('front/bots/message_limit');
                exit();
            }
        }
        
        if(!empty($bot_token) && !empty($channel_name)){
            $userId = Auth::user()->id;
			
			if(!empty($img_url)){
				$BOT_TOKEN = $bot_token;
				$chat_id = $channel_name;
				
				$telegram = new Api($bot_token);
				$data = array();
				
				if(!empty($message)){
					$data = [
						'chat_id' => '@'.$channel_name,
						'photo' => $img_url,
						'caption' => $message
					];
				}
				else{
					$data = [
						'chat_id' => '@'.$channel_name,
						'photo' => $img_url
					];
				}
				
				$result = $telegram->sendPhoto($data);
				$response = json_decode(json_encode($result));
				
				if(isset($response->message_id) && !empty($response->message_id)){
					DB::table('channel_send_message')->insertGetId(
						['user_id' => $userId, 'channel_id' => $request->get('chat_id'), 'channel_name' => '@'.$channel_name, 'send_date' => date('Y-m-d'), 'message' => $img_url]
					);
				}
					
				echo 'Image sent succesfully' ;
			}
			
			else if(!empty($message)){
				$telegram = new Api($bot_token);
            
				$data = [];
				$data['chat_id'] = '@'.$channel_name;
				$data['text'] = $message;
				
				$result = $telegram->sendMessage($data);
				
				$response = json_decode(json_encode($result));
				
				if ($response->message_id) {
					DB::table('channel_send_message')->insertGetId(
						['user_id' => $userId, 'channel_id' => $request->get('chat_id'), 'channel_name' => '@'.$channel_name, 'send_date' => date('Y-m-d'), 'message' => $message]
					);
					
					echo 'Message sent succesfully' ;
				} else {
					echo 'Sorry message not sent';
				}
			}
            
        }
		die;
    }

    /*
            $manual_message_interval = $plan[0]->manual_message_interval;

            if ($manual_message_interval == "day") {
                $chkData = DB::table('channel_send_message')
                    ->where('channel_id','=',$request->get('chat_id'))
                    ->where('send_date','=',date('Y-m-d'))
                    ->get();
            } else if ($manual_message_interval == "week") {
                $chkData = DB::table('channel_send_message')
                    ->where('channel_id','=',$request->get('chat_id'))
                    ->whereBetween('send_date',array(date('Y-m-d', strtotime("-1 week")),date('Y-m-d')))
                    ->get();
            } else if ($manual_message_interval == "month") {
                $chkData = DB::table('channel_send_message')
                    ->where('channel_id','=',$request->get('chat_id'))
                    ->whereBetween('send_date',array(date('Y-m-d', strtotime("-1 month")),date('Y-m-d')))
                    ->get();
            }
     */
    
    public function sendbotmessage(Request $request){
		$img_url = '';
		if($request->hasFile('bot_image')){
			$error_img = $_FILES["bot_image"]["error"];
			$img_name = $_FILES["bot_image"]["name"];

			if($error_img == '0' && $img_name != '' )
			{
			   $img_path = $_FILES["bot_image"]["tmp_name"];
			   //$img_name_s = time()."_".$img_name;
			   $ext = pathinfo($img_name);
			   $img_name_s = time().'.'.$ext['extension'];
			   $upload_img = public_path().'/uploads/'.$img_name_s;

			   move_uploaded_file($img_path,$upload_img);
			   $img_url = $upload_img;
			}
		}
		
				
        $bot_token = '';
        $chatIdArr = array();
        $botId = ($request->get('b_bot_id') && !empty($request->get('b_bot_id')))?$request->get('b_bot_id'):'';
        $message = ($request->get('bot_msg') && !empty($request->get('bot_msg')))?$request->get('bot_msg'):'';
        
        if(!empty($botId)){
            $bot_data = DB::table('bots')
                            ->where('id','=',$botId)
                            ->get();

            $bot_token = $bot_data[0]->bot_token;
            
            $chat_data = DB::table('bot_messages')
                            ->where('bot_id', '=', $botId)
                            ->get();
            
            if(!empty($chat_data)){
                foreach($chat_data as $k1 => $v1){
                    if(in_array($v1->forward_from,$chatIdArr)){
                    }
                    else{
                        $chatIdArr[] = $v1->forward_from;
                    }
                }
            }
        }
		
        $chk_img = false;
        if(!empty($chatIdArr) && !empty($bot_token)){
            foreach($chatIdArr as $ck2 => $cv2){
				if(!empty($img_url)){
					$BOT_TOKEN = $bot_token;
					$chat_id = $cv2;
					
					$BOTAPI = 'https://api.telegram.org/bot' . $BOT_TOKEN .'/';
					$cfile = new \CURLFile($img_url, 'image/jpg'); 
					
					if(!empty($message)){
						$data = [
							'chat_id' => $cv2, 
							'photo' => $cfile,
							'caption' => $message
						];
					}
					else{
						$data = [
							'chat_id' => $cv2, 
							'photo' => $cfile
						];
					}
			
					$ch = curl_init($BOTAPI.'sendPhoto');
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					$result = curl_exec($ch);
					curl_close($ch);
					$chk_img = true;
				}
				else if(!empty($message)){
					$telegram = new Api($bot_token);
					$data = [];
					$data['chat_id'] = $cv2;
					$data['text'] = $message;
				
					$result = $telegram->sendMessage($data);
					
					$response = json_decode(json_encode($result));
				}
            }
			
			if (isset($response->message_id) && !empty($response->message_id)) {
				echo trans('front/bots.message_sent_succesfully');
			} 
			else if($chk_img){
				echo trans('front/bots.image_sent_succesfully');
			}
			else {
				echo trans('front/bots.message_not_sent');
			}
        }
		die;
    }  
    
}
