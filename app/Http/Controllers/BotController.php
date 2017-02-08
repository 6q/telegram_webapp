<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use Cartalyst\Stripe\Stripe;

use App\Http\Requests;

use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Requests\BotCreateRequest;
use App\Repositories\UserRepository;

use App\Models\Bot;
use App\Models\BotCommand;
use App\Models\UserBilling;
use App\Models\UserSubscription;
use App\Models\UserTransaction;

use Telegram\Bot\Api;

use Illuminate\Support\Facades\Validator;

use Excel;
use Elibyy\TCPDF\Facades\TCPDF;


class BotController extends Controller
{
    public function __construct() {
		parent::login_check();
        parent::getTotalbot_chanel();
		
		define('PAGE_DATA_LIMIT','4');
		define('PAGE_DATA_LIMIT_MESSAGE','10');
		define('PAGE_DATA_LIMIT_USER','10');
		define('PAGE_ADJACENTS','3');
		define('PAGE_BOT_COMMAND','2');
    }
    
         
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        $Form_action = 'bot/create';
        
        $search = '';
        if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
        }
        
        $bots=Bot::latest()
		->paginate(20);
		$links = $bots->render();
		
		return view('front.bots.index', compact('bots','links','total_bots','total_chanels','Form_action','search'));	
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        $user_id = Auth::user()->id;
        
        $Form_action = 'bot/create';
        
        $search = '';
        if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
        }
        
        $email = Auth::user()->email;
        
        
        if(!empty($search)){
            $plans = DB::table('plans')
                        ->where('plan_type', '=', '1')
						->where('name', 'LIKE', '%'.$search.'%')
                        ->get();
        }
        else{
            $plans = DB::table('plans')
						->where('plan_type','=','1')
						->where('status','=','1')
						->get();
        }
        
        
        $billing_details =  DB::table('user_billings')->where('user_id','=',$user_id)->get();
        
        $states = '';
        if(isset($billing_details[0]->country_id) && !empty($billing_details[0]->country_id))
        {
            $states = DB::table('states')
                ->where('country_id', '=', $billing_details[0]->country_id)
                ->get();
        }
        
        $country = DB::table('countries')->get();
        
        /* nickName popup */
        $nickName = DB::table('pages')
                        ->where('id','=','13')
                        ->get();
						
		/* botusername popup */
        $botUserName = DB::table('pages')
                        ->where('id','=','14')
                        ->get();
						
		/* botaccesstoken popup */
        $botAccessToken = DB::table('pages')
                        ->where('id','=','15')
                        ->get();				
						
        return view('front.bots.create',compact('plans','email','country','total_bots','total_chanels','Form_action','search','billing_details','states','nickName','botUserName','botAccessToken'));
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(BotCreateRequest $request)
    {
		$firstName = Auth::user()->first_name;
        $lastName = Auth::user()->last_name;
        $user_id = Auth::user()->id;
        $email = Auth::user()->email;
        
        $stripeToken = $request->get('stripeToken');
        
       // echo '<pre>';print_r($request->all());die;
        
      	$bot = new Bot;
		
        $bot->user_id = $user_id;
		$bot->first_name = $firstName;
		$bot->last_name = $lastName;
		$bot->username = $request->get('username');
		$bot->bot_token = $request->get('bot_token');
		$bot->error_msg = $request->get('error_msg');
		$bot->comanda = $request->get('comanda');
		$bot->bot_description = $request->get('bot_description');
		$bot->start_message = $request->get('start_message');
		$bot->autoresponse = $request->get('autoresponse');
		$bot->contact_form = $request->get('contact_form');
		$bot->galleries = $request->get('galleries');
		$bot->channels = $request->get('channels');
		
		$bot->intro_autoresponses = $request->get('intro_autoresponses');
		$bot->intro_contact_form = $request->get('intro_contact_form');
		$bot->intro_galleries = $request->get('intro_galleries');
		$bot->intro_channels = $request->get('intro_channels');
		
        
        if($request->hasFile('bot_image'))
		{
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
                
		       $bot->bot_image = $img_name_s;
			}
		}
        
        $bot->created_at = date('Y-m-d h:i:s');
		$bot->updated_at = date('Y-m-d h:i:s');
        
        if($bot->save()){
            $lastInsertId = $bot->id;        
            
            /* User billing */
            $user_billings = DB::table('user_billings')->where('user_id', '=', $user_id)->get();
            if(!empty($user_billings)){
                $ubId = $user_billings[0]->id;
                $user_billings = UserBilling::find($ubId);
            }
            else{
                $user_billings = new UserBilling;
            }

            $user_billings->user_id = $user_id;
            $user_billings->address = '';
            $user_billings->street = $request->get('street');
            $user_billings->country_id = $request->get('country');
            $user_billings->state_id = $request->get('state');
            $user_billings->city = $request->get('city');
            $user_billings->zipcode = $request->get('zip');
            $user_billings->created_at = date('Y-m-d h:i:s');
            $user_billings->updated_at = date('Y-m-d h:i:s');
            
            $user_billings->save();
            /* User billing */
            
            
            
            $stripe = Stripe::make(env('STRIPE_APP_KEY'));
            
            $plans = DB::table('plans')->where('id', '=', $request->get('plan_id'))->get();
			
            $stripe_plan_id = $plans[0]->id;
            
            
			if($plans[0]->price > 0){
				$customer = $stripe->customers()->create([
					'source'  => $stripeToken,
					'email' => $email,
					//'plan' => $stripe_plan_id
				]);
				
				
			   if(!empty($customer)){
					$customerID = $customer['id'];
					
					$bot_subscription = $stripe->subscriptions()->create($customerID, ['plan' => $stripe_plan_id]);
					
					$bot = Bot::find($lastInsertId);
					$bot->stripe_customer_id = $customerID;
					$bot->stripe_subscription_id = $bot_subscription['id'];
					$bot->save();
									
					/* user_subscriptions */
					$user_subscription = new UserSubscription;
	
					$user_subscription->user_id = $user_id;
					$user_subscription->plan_id = $stripe_plan_id;
					$user_subscription->types = 'bot';
					$user_subscription->type_id = $lastInsertId;
					$user_subscription->price = $request->get('plan_price');
					$user_subscription->subscription_date = date('Y-m-d',$bot_subscription['current_period_start']);
					$user_subscription->expiry_date = date('Y-m-d',$bot_subscription['current_period_end']);
					$user_subscription->last_billed = date('Y-m-d');
					$user_subscription->status = 'Completed';
					$user_subscription->created_at = date('Y-m-d h:i:s');
					$user_subscription->updated_at = date('Y-m-d h:i:s');
	
					$user_subscription->save();
					/* user_subscriptions */
	
	
	
	
				   /* UserTransaction */
	
				   $user_transaction = new UserTransaction;
				   $user_transaction->user_id = $user_id;
				   $user_transaction->plan_id = $stripe_plan_id;
				   $user_transaction->types = 'bot';
				   $user_transaction->type_id = $lastInsertId;
				   $user_transaction->amount = $request->get('plan_price');
				   $user_transaction->Description = '';
				   $user_transaction->created_at = date('Y-m-d h:i:s');
				   $user_transaction->updated_at = date('Y-m-d h:i:s');
	
				   $user_transaction->save();
				   /* UserTransaction */
				   
				   
				   
				   
				   
				   $contactFormEmail = DB::table('site_settings')
									->where('name','=','contact_form_email')
									->get();
				   $from_email = $contactFormEmail[0]->value;				
			
					//$to_email = $contactFormEmail[0]->value;//$request->get('email');
					$to_email = $request->get('email');
					
					$email_template = DB::table('email_templates')
											->where('title','LIKE','email_bot')
											->get();
					$email_subject = $email_template[0]->subject;						
					$template = $email_template[0]->description;
					
					
					$planDetail = DB::table('plans')
									->where('id','=',$request->get('plan_id'))
									->get();
									
					$planName = (isset($planDetail[0]->name) && !empty($planDetail[0]->name))?$planDetail[0]->name:'';
					$NO_AUTORESPONSE = (isset($planDetail[0]->autoresponses) && !empty($planDetail[0]->autoresponses))?$planDetail[0]->autoresponses:'';
					$NO_CONTACT_FORM = (isset($planDetail[0]->contact_forms) && !empty($planDetail[0]->contact_forms))?$planDetail[0]->contact_forms:'';
					$image_gallery = (isset($planDetail[0]->image_gallery) && !empty($planDetail[0]->image_gallery))?$planDetail[0]->image_gallery:'';
					$gallery_images = (isset($planDetail[0]->gallery_images) && !empty($planDetail[0]->gallery_images))?$planDetail[0]->gallery_images:'';
					$custom_welcome_message = (isset($planDetail[0]->custom_welcome_message) && !empty($planDetail[0]->custom_welcome_message))?$planDetail[0]->custom_welcome_message:'';
					$custom_not_allowed_message = (isset($planDetail[0]->custom_not_allowed_message) && !empty($planDetail[0]->custom_not_allowed_message))?$planDetail[0]->custom_not_allowed_message:'';
					
					
					
					$country = DB::table('countries')
									->where('id','=',$request->get('country'))
									->get();
					$countryName = (isset($country[0]->name) && !empty($country[0]->name))?$country[0]->name:'';				
			
					
					$state = DB::table('states')
									->where('id','=',$request->get('state'))
									->get();
					$stateName = (isset($state[0]->name) && !empty($state[0]->name))?$state[0]->name:'';
					
			
					$emailFindReplace = array(
						'##PLAN_USERNAME##' => $planName,
						'##PRICE##' => $request->get('plan_price'),
						'##NO_AUTORESPONSE##' => $NO_AUTORESPONSE,
						'##NO_CONTACT_FORM##' => $NO_CONTACT_FORM,
						'##NO_IMAGE_GALLERY##' => $image_gallery,
						'##NO_MESSAGE_PER_DAY##' => $gallery_images,
						'##NO_CUSTOM_WELCOME_MSG##' => $custom_welcome_message,
						'##NO_CUSTOM_NOT_ALLOWED##' => $custom_not_allowed_message,
						'##BOT_USERNAME##' => $request->get('username'),
						'##NICK_NAME##' => $request->get('nick_name'),
						'##BOT_ACCESS_TOKEN##' => $request->get('bot_token'),
						'##START_MESSAGE##' => $request->get('start_message'),
						'##AUTORESPONSE##' => $request->get('autoresponse'),
						'##CONTACT_FORM##' => $request->get('contact_form'),
						'##GALLERY_BUTTON##' => $request->get('galleries'),
						'##CHANNELS_BUTTON##' => $request->get('channels'),
						'##STREET##' => $request->get('street'),
						'##COUNTRY##' => $countryName,
						'##STATE##' => $stateName,
						'##CITY##' => $request->get('city'),
						'##POSTAL_CODE##' => $request->get('zip'),
						'##EMAIL##' => $request->get('email'),
						'##CARD_NAME##' => $request->get('cardholdername'),
						'##NUMBER##' => $request->get('cardnumber'),
						'##EXP_DATE##' => $request->get('card_exp'),
						'##CVV##' => $request->get('cvv')
					);
					
					$html = strtr($template, $emailFindReplace);
					
					$email_arr = array();
					$email_arr['to_email'] = $to_email;
					$email_arr['subject'] = $email_subject;
					$email_arr['from'] = $from_email;
					
					\Mail::send(['html' => 'front.bots.email_bot_template'],
						array(
							'text' => $html
						), function($message) use ($email_arr)
					{
						$message->from($email_arr['to_email']);
						$message->to($email_arr['to_email'])->subject($email_arr['subject']);
					});
			
					
				}
			}	
			else
			{
				$duration = $plans[0]->duration;
				$expiry_date = date('Y-m-d',strtotime('+'.$duration.' month'));
				
				$user_subscription = new UserSubscription;
				$user_subscription->user_id = $user_id;
				$user_subscription->plan_id = $stripe_plan_id;
				$user_subscription->types = 'bot';
				$user_subscription->type_id = $lastInsertId;
				$user_subscription->price = $request->get('plan_price');
				$user_subscription->subscription_date = date('Y-m-d');
				$user_subscription->expiry_date = $expiry_date;
				$user_subscription->last_billed = date('Y-m-d');
				$user_subscription->status = 'Completed';
				$user_subscription->created_at = date('Y-m-d h:i:s');
				$user_subscription->updated_at = date('Y-m-d h:i:s');

				$user_subscription->save();
				/* user_subscriptions */
	
	
	
	
			   /* UserTransaction */

			   $user_transaction = new UserTransaction;
			   $user_transaction->user_id = $user_id;
			   $user_transaction->plan_id = $stripe_plan_id;
			   $user_transaction->types = 'bot';
			   $user_transaction->type_id = $lastInsertId;
			   $user_transaction->amount = $request->get('plan_price');
			   $user_transaction->Description = '';
			   $user_transaction->created_at = date('Y-m-d h:i:s');
			   $user_transaction->updated_at = date('Y-m-d h:i:s');

			   $user_transaction->save();
			   /* UserTransaction */
				   
				   
				   
				   
				   
			   $contactFormEmail = DB::table('site_settings')
								->where('name','=','contact_form_email')
								->get();
			   $from_email = $contactFormEmail[0]->value;				
		
				//$to_email = $contactFormEmail[0]->value;//$request->get('email');
				$to_email = $request->get('email');
				
				$email_template = DB::table('email_templates')
										->where('title','LIKE','email_bot')
										->get();
				$email_subject = $email_template[0]->subject;						
				$template = $email_template[0]->description;
				
				
				$planDetail = DB::table('plans')
								->where('id','=',$request->get('plan_id'))
								->get();
								
				$planName = (isset($planDetail[0]->name) && !empty($planDetail[0]->name))?$planDetail[0]->name:'';
				$NO_AUTORESPONSE = (isset($planDetail[0]->autoresponses) && !empty($planDetail[0]->autoresponses))?$planDetail[0]->autoresponses:'';
				$NO_CONTACT_FORM = (isset($planDetail[0]->contact_forms) && !empty($planDetail[0]->contact_forms))?$planDetail[0]->contact_forms:'';
				$image_gallery = (isset($planDetail[0]->image_gallery) && !empty($planDetail[0]->image_gallery))?$planDetail[0]->image_gallery:'';
				$gallery_images = (isset($planDetail[0]->gallery_images) && !empty($planDetail[0]->gallery_images))?$planDetail[0]->gallery_images:'';
				$custom_welcome_message = (isset($planDetail[0]->custom_welcome_message) && !empty($planDetail[0]->custom_welcome_message))?$planDetail[0]->custom_welcome_message:'';
				$custom_not_allowed_message = (isset($planDetail[0]->custom_not_allowed_message) && !empty($planDetail[0]->custom_not_allowed_message))?$planDetail[0]->custom_not_allowed_message:'';
				
				
				
				$country = DB::table('countries')
								->where('id','=',$request->get('country'))
								->get();
				$countryName = (isset($country[0]->name) && !empty($country[0]->name))?$country[0]->name:'';				
		
				
				$state = DB::table('states')
								->where('id','=',$request->get('state'))
								->get();
				$stateName = (isset($state[0]->name) && !empty($state[0]->name))?$state[0]->name:'';
				
		
				$emailFindReplace = array(
					'##PLAN_USERNAME##' => $planName,
					'##PRICE##' => $request->get('plan_price'),
					'##NO_AUTORESPONSE##' => $NO_AUTORESPONSE,
					'##NO_CONTACT_FORM##' => $NO_CONTACT_FORM,
					'##NO_IMAGE_GALLERY##' => $image_gallery,
					'##NO_MESSAGE_PER_DAY##' => $gallery_images,
					'##NO_CUSTOM_WELCOME_MSG##' => $custom_welcome_message,
					'##NO_CUSTOM_NOT_ALLOWED##' => $custom_not_allowed_message,
					'##BOT_USERNAME##' => $request->get('username'),
					'##NICK_NAME##' => $request->get('nick_name'),
					'##BOT_ACCESS_TOKEN##' => $request->get('bot_token'),
					'##START_MESSAGE##' => $request->get('start_message'),
					'##AUTORESPONSE##' => $request->get('autoresponse'),
					'##CONTACT_FORM##' => $request->get('contact_form'),
					'##GALLERY_BUTTON##' => $request->get('galleries'),
					'##CHANNELS_BUTTON##' => $request->get('channels'),
					'##STREET##' => $request->get('street'),
					'##COUNTRY##' => $countryName,
					'##STATE##' => $stateName,
					'##CITY##' => $request->get('city'),
					'##POSTAL_CODE##' => $request->get('zip'),
					'##EMAIL##' => $request->get('email'),
					'##CARD_NAME##' => $request->get('cardholdername'),
					'##NUMBER##' => $request->get('cardnumber'),
					'##EXP_DATE##' => $request->get('card_exp'),
					'##CVV##' => $request->get('cvv')
				);
				
				$html = strtr($template, $emailFindReplace);
				
				$email_arr = array();
				$email_arr['to_email'] = $to_email;
				$email_arr['subject'] = $email_subject;
				$email_arr['from'] = $from_email;
				
				\Mail::send(['html' => 'front.bots.email_bot_template'],
					array(
						'text' => $html
					), function($message) use ($email_arr)
				{
					$message->from($email_arr['to_email']);
					$message->to($email_arr['to_email'])->subject($email_arr['subject']);
				});
			}
           return redirect('dashboard')->with('ok', trans('front/bot.created'));
        }
        else{
            return redirect('dashboard')->with('ok', trans('front/bot.error'));
        }
    }
    
    
    public function detail($botid = NULL){
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
		        
        $Form_action = 'bot/detail/'.$botid;
        
        $search = '';
        if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
        }
        
		$Total_autoResponse = DB::table('autoresponses')->where('type_id', '=', $botid)->get();
		$total_pages = count($Total_autoResponse);

		$Total_contactForm = DB::table('contact_forms')->where('type_id', '=', $botid)->get();
		$total_pages_contatc_form = count($Total_contactForm);
		
		$Total_galleries = DB::table('galleries')->where('type_id', '=', $botid)->get();
		$total_pages_gallery = count($Total_galleries);
		
		$Total_chanels = DB::table('chanels')->where('type_id', '=', $botid)->get();
		$total_pages_chanels = count($Total_chanels);
		
		$Total_activeUser = DB::table('bot_users')->where('bot_id', '=', $botid)->get();
		$total_pages_activeUser = count($Total_activeUser);
		
		$Total_message = DB::table('bot_messages')
                                ->join('bot_users', 'bot_users.id', '=', 'bot_messages.bot_user_id')
                                ->where('bot_messages.bot_id', '=', $botid)
	                            ->orderby('bot_messages.id','DESC')
	                            ->get();
		$total_pages_message = count($Total_message);
		
		$botCommands = DB::table('bot_commands')->where('bot_id','=',$botid)->get();
		$total_bot_commands = count($botCommands);
		
		$limit = PAGE_DATA_LIMIT; 
		$limitMessage = PAGE_DATA_LIMIT_MESSAGE;
		$limitUser = PAGE_DATA_LIMIT_USER;
		$adjacents = PAGE_ADJACENTS;
		$bot_commands_limit = PAGE_BOT_COMMAND;
		$page = 1;
		$planDetails = '';
		
				
        if(!empty($botid)){
            $bots = DB::table('bots')->where('id', '=', $botid)->get();
		    /*echo '<pre>';print_r($bots);die;*/
			
			$subscription = DB::table('user_subscriptions')
								->where('type_id', '=',$botid)
								->where('types', '=','bot')
								->get();
								
			if(isset($subscription[0]->plan_id) && !empty($subscription[0]->plan_id)){
				$planDetails = DB::table('plans')->where('id','=',$subscription[0]->plan_id)->get();
			}					
				        
            if(!empty($search)){
                $autoResponse = DB::table('autoresponses')
                                    ->where('type_id', '=', $botid)
                                    ->where('submenu_heading_text', 'LIKE', '%'.$search.'%')
									->limit($limit)
                                    ->get();
                
                $contactForm = DB::table('contact_forms')
                                    ->where('type_id', '=', $botid)
                                    ->where('submenu_heading_text', 'LIKE', '%'.$search.'%')
									->limit($limit)
                                    ->get();
                
                $gallery = DB::table('galleries')
                                    ->where('type_id', '=', $botid)
                                    ->where('gallery_submenu_heading_text', 'LIKE', '%'.$search.'%')
									->limit($limit)
                                    ->get();
                
                $chanels = DB::table('chanels')
                                    ->where('type_id', '=', $botid)
                                    ->where('chanel_submenu_heading_text', 'LIKE', '%'.$search.'%')
									->limit($limit)
                                    ->get();
                
                $activeUser = '';
                $botMessages = '';
				
				$botCommands = '';
                
            }
            else{
                $autoResponse = DB::table('autoresponses')->where('type_id', '=', $botid)->limit($limit)->get();
                $contactForm = DB::table('contact_forms')->where('type_id', '=', $botid)->limit($limit)->get();
                $gallery = DB::table('galleries')->where('type_id', '=', $botid)->limit($limit)->get();
                $chanels = DB::table('chanels')->where('type_id', '=', $botid)->limit($limit)->get();
                
                $activeUser = DB::table('bot_users')->where('bot_id', '=', $botid)->limit($limitUser)->get();
                
                $botMessages = DB::table('bot_messages')
                                ->join('bot_users', 'bot_users.id', '=', 'bot_messages.bot_user_id')
                                ->where('bot_messages.bot_id', '=', $botid)
	                            ->orderby('bot_messages.id','DESC')
								->limit($limitMessage)
	                            ->get();
				
				$botCommands = DB::table('bot_commands')->where('bot_id','=',$botid)->limit($bot_commands_limit)->get();
								
								
            }

            return view('front.bots.detail', compact('bots','planDetails','autoResponse','contactForm','gallery','chanels','total_bots','total_chanels','Form_action','search','activeUser','botMessages','','total_pages','limit','limitMessage','adjacents','page','total_pages_contatc_form','total_pages_message','total_pages_gallery','total_pages_chanels','limitUser','total_pages_activeUser','botCommands','total_bot_commands','bot_commands_limit'));
        }
    }
	
	
	public function download_user($botid = NULL)
	{
		$botUser = DB::table('bot_users')->where('bot_id', '=', $botid)->get();
		
	
		header("Content-Type:   application/vnd.ms-excel;");
		header("Content-type:   application/x-msexcel; charset=utf-8");
		header("Content-Disposition : attachment; filename=botUsersList.xls");		
		
		echo 'S No.' . "\t" .'First Name' . "\t" . 'Last Name' . "\t" . 'Created' . "\n";    
        if(!empty($botUser))
        {
            $i = 1;
            foreach($botUser as $k1 => $v1){
                echo $i ."\t". $v1->first_name ."\t". $v1->last_name ."\t". $v1->created_at . "\n";
                $i++;
			}
		}
		
		exit();
		
		/*
		Excel::create('botUsersList', function($excel) use ($botUser) {
            $excel->setTitle('BotUsersList');
			$arr = array();		   
		   	if(!empty($botUser))
			{
				$i = 1;
				foreach($botUser as $k1 => $v1){
					$arr[$k1]['S No.'] = $i;
					$arr[$k1]['First Name'] = $v1->first_name;
					$arr[$k1]['Last Name'] = $v1->last_name;
					$arr[$k1]['Created'] = $v1->created_at;
					$i++;
				}
			}

            $excel->sheet('Sheet 1', function ($sheet) use ($arr) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray($arr, NULL, 'A3');
            });

        })->download('xlsx');
		*/
		
	}
	
	public function pdf_download_user($botid = NULL)
	{
		$botDetail = DB::table('bots')->where('id', '=', $botid)->get();
		$botUser = DB::table('bot_users')->where('bot_id', '=', $botid)->get();
		
		$html = '<table border="0">';
			$html .= '<tr><td  style="text-align:center" colspan="4"><img style="width:350px" src="'.asset('/img/front/logo.png').'" alt="Citymes" ></td></tr>';
			$html .= '<tr><td colspan="4"></td></tr>';
			$html .= '<tr><td colspan="4"><b>Name of Bot :- </b> '.$botDetail[0]->username.'</td></tr>';
			$html .= '<tr><td colspan="4"></td></tr>';
		$html .= '</table>';
		$html .= '<table border="1">';
			$html .= '<tr>';
				$html .= '<th style="text-align:center"><b>S No.</b></th>';
				$html .= '<th style="text-align:center"><b>First Name</b></th>';
				$html .= '<th style="text-align:center"><b>Last Name</b></th>';
				$html .= '<th style="text-align:center"><b>Created</b></th>';
			$html .= '</tr>';
			
			if(!empty($botUser))
			{
				$i = 1;
				foreach($botUser as $k1 => $v1){
					$html .= '<tr>';
						$html .= '<td style="text-align:center">'.$i.'</td>';
						$html .= '<td style="text-align:center">'.$v1->first_name.'</td>';
						$html .= '<td style="text-align:center">'.$v1->last_name.'</td>';
						$html .= '<td style="text-align:center">'.$v1->created_at.'</td>';
					$html .= '</tr>';
					$i++;
				}
			}
			
		$html .= '</table>';	
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);		
		$pdf::SetTitle('Bot User Log');
		$pdf::AddPage();
		$pdf::writeHTML($html, true, false, true, false, '');
		$pdf::Output('userLog.pdf','D');
		
	}
	
	
	public function download_log($botid = NULL)
	{
		$botMessages = DB::table('bot_messages')
                                ->join('bot_users', 'bot_users.id', '=', 'bot_messages.bot_user_id')
                                ->where('bot_messages.bot_id', '=', $botid)
	                            ->orderby('bot_messages.id','DESC')
	                            ->get();
		
		header( "Content-Type: application/vnd.ms-excel" );
        header( "Content-disposition: attachment; filename=botlog.xls" );
    
        
        echo 'S No.' . "\t" .'User' . "\t" . 'Message' . "\t" . 'Reply Message' . "\t". 'Date' . "\n";    
        if(!empty($botMessages))
        {
            $i = 1;
            foreach($botMessages as $bmk1 => $bmv1){
                echo $i ."\t". $bmv1->first_name.' '.$bmv1->last_name ."\t". $bmv1->text ."\t". $bmv1->reply_message ."\t". $bmv1->date . "\n";
                $i++;
			}
		}
	
		/*
		Excel::create('BotLog', function($excel) use ($botMessages) {
            $excel->setTitle('BotLog');
			$arr = array();		   
		   	if(!empty($botMessages))
			{
				$i = 1;
				foreach($botMessages as $bmk1 => $bmv1){
					$arr[$bmk1]['S No.'] = $i;
					$arr[$bmk1]['User'] = $bmv1->first_name.' '.$bmv1->last_name;
					$arr[$bmk1]['Message'] = $bmv1->text;
					$arr[$bmk1]['Reply Message'] = $bmv1->reply_message;
					$arr[$bmk1]['Date'] = $bmv1->date;
					$i++;
				}
			}

            $excel->sheet('Sheet 1', function ($sheet) use ($arr) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray($arr, NULL, 'A3');
            });

        })->download('xlsx');
		*/
	}
	
	
	public function log_pdf_download($botid = NULL){
		$botDetail = DB::table('bots')->where('id', '=', $botid)->get();
		$botMessages = DB::table('bot_messages')
							->join('bot_users', 'bot_users.id', '=', 'bot_messages.bot_user_id')
							->where('bot_messages.bot_id', '=', $botid)
							->orderby('bot_messages.id','DESC')
							->get();
							
		$html = '<table border="0" style="width: 100%; float: left;">';
			$html .= '<tr><td  style="text-align:center" colspan="5"><img style="width:350px" src="'.asset('/img/front/logo.png').'" alt="Citymes" ></td></tr>';
			$html .= '<tr><td colspan="5"></td></tr>';
			$html .= '<tr><td colspan="5"><b>Name of Bot :- </b> '.$botDetail[0]->username.'</td></tr>';
			$html .= '<tr><td colspan="5"></td></tr>';
		$html .= '</table>';
		$html .= '<table border="1" style="width: 100%; float: left;font-size:12px;">';
			$html .= '<tr>';
				$html .= '<th style="text-align:center"><b>S No.</b></th>';
				$html .= '<th style="text-align:center"><b>User</b></th>';
				$html .= '<th style="text-align:center"><b>Message</b></th>';
				$html .= '<th style="text-align:center"><b>Reply Message</b></th>';
				$html .= '<th style="text-align:center"><b>Date</b></th>';
			$html .= '</tr>';					
		
		if(!empty($botMessages))
		{
			$j = 1;
			foreach($botMessages as $bmk1 => $bmv1)
			{
				$html .= '<tr>';
					$html .= '<td style="text-align:center;font-size:12px;">'.$j.'</td>';
					$html .= '<td style="text-align:center;font-size:12px;">'.$bmv1->first_name.' '.$bmv1->last_name.'</td>';
					$html .= '<td style="text-align:center;font-size:12px;">'.$bmv1->text.'</td>';
					$html .= '<td style="text-align:center;font-size:12px;">'.$bmv1->reply_message.'</td>';
					$html .= '<td style="text-align:center;font-size:12px;">'.$bmv1->date.'</td>';
				$html .= '</tr>';
				$j++;
			}
		}	
		
		$html .= '</table>';

		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf::SetTitle('Bot Log');
		$pdf::AddPage();
		$pdf::writeHTML($html, true, false, true, false, '');
		$pdf::Output('botLog.pdf','D');	
		//$pdf::Output('botLog.pdf','I');	
		/*
		PDF::SetTitle('Bot Log');
		PDF::SetAutoPageBreak(TRUE);
		PDF::AddPage();
		PDF::writeHTML($html, true, false, true, false, '');
		PDF::Output('botLog.pdf','D');				
		*/
	}
	
	
	
	public function paginate_autoresponse(Request $request)
	{
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT;
		$botid = $request->get('botId');
		$planDetails = '';
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_autoResponse = DB::table('autoresponses')->where('type_id', '=', $botid)->get();
		$total_pages = count($Total_autoResponse);
		
		$subscription = DB::table('user_subscriptions')
							->where('type_id', '=',$botid)
							->where('types', '=','bot')
							->get();
							
		if(isset($subscription[0]->plan_id) && !empty($subscription[0]->plan_id)){
			$planDetails = DB::table('plans')->where('id','=',$subscription[0]->plan_id)->get();
		}
		
		$autoResponse = DB::table('autoresponses')->where('type_id', '=', $botid)->limit($limit)->offset($start)->get();
		
		return view('front.bots.paginnate_autoresponse', compact('planDetails','autoResponse','total_pages','limit','botid','current_page'));
	}
	
	public function paginate_contact_form(Request $request){
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT;
		$botid = $request->get('botId');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_contact_forms = DB::table('contact_forms')->where('type_id', '=', $botid)->get();
		$total_pages_contatc_form = count($Total_contact_forms);
		
		$planDetails = '';
		$subscription = DB::table('user_subscriptions')
							->where('type_id', '=',$botid)
							->where('types', '=','bot')
							->get();
							
		if(isset($subscription[0]->plan_id) && !empty($subscription[0]->plan_id)){
			$planDetails = DB::table('plans')->where('id','=',$subscription[0]->plan_id)->get();
		}
		
		$contactForm = DB::table('contact_forms')->where('type_id', '=', $botid)->limit($limit)->offset($start)->get();
		
		return view('front.bots.paginnate_contact_form', compact('contactForm','planDetails','total_pages_contatc_form','limit','botid','current_page','adjacents'));
	}
	
	public function paginate_gallery(Request $request){
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT;
		$botid = $request->get('botId');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_galleries = DB::table('galleries')->where('type_id', '=', $botid)->get();
		$total_pages_gallery = count($Total_galleries);
		
		$planDetails = '';
		$subscription = DB::table('user_subscriptions')
							->where('type_id', '=',$botid)
							->where('types', '=','bot')
							->get();
							
		if(isset($subscription[0]->plan_id) && !empty($subscription[0]->plan_id)){
			$planDetails = DB::table('plans')->where('id','=',$subscription[0]->plan_id)->get();
		}
		
		$gallery = DB::table('galleries')->where('type_id', '=', $botid)->limit($limit)->offset($start)->get();
		
		return view('front.bots.paginnate_gallery', compact('planDetails','gallery','total_pages_gallery','limit','botid','current_page','adjacents'));
	}
	
	public function paginate_channel(Request $request){
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT;
		$botid = $request->get('botId');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_chanels = DB::table('chanels')->where('type_id', '=', $botid)->get();
		$total_pages_chanels = count($Total_chanels);
		
		$chanels = DB::table('chanels')->where('type_id', '=', $botid)->limit($limit)->offset($start)->get();
		
		return view('front.bots.paginnate_chanel', compact('chanels','total_pages_chanels','limit','botid','current_page','adjacents'));
	}
	
	public function paginate_active_user(Request $request){
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT_USER;
		$botid = $request->get('botId');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_bot_users = DB::table('bot_users')->where('bot_id', '=', $botid)->get();
		$total_pages_activeUser = count($Total_bot_users);
		
		$activeUser = DB::table('bot_users')->where('bot_id', '=', $botid)->limit($limit)->offset($start)->get();
		
		return view('front.bots.paginnate_activeUser', compact('activeUser','total_pages_activeUser','limit','botid','current_page','adjacents'));
	}
	
	
	public function paginate_bot_message(Request $request){
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT_MESSAGE;
		$botid = $request->get('botId');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_message = DB::table('bot_messages')
                                ->join('bot_users', 'bot_users.id', '=', 'bot_messages.bot_user_id')
                                ->where('bot_messages.bot_id', '=', $botid)
	                            ->orderby('bot_messages.id','DESC')
	                            ->get();
		$total_pages_message = count($Total_message);
		
		$botMessages = DB::table('bot_messages')
                                ->join('bot_users', 'bot_users.id', '=', 'bot_messages.bot_user_id')
                                ->where('bot_messages.bot_id', '=', $botid)
	                            ->orderby('bot_messages.id','DESC')
								->limit($limit)
								->offset($start)
	                            ->get();
										
		return view('front.bots.paginnate_bot_message', compact('botMessages','total_pages_message','limit','botid','current_page','adjacents'));
	}
	
	
	public function paginate_bot_command(Request $request){
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_BOT_COMMAND;
		$botid = $request->get('botId');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$botCommands = DB::table('bot_commands')->where('bot_id','=',$botid)->get();
		$total_bot_commands = count($botCommands);
		
		$botCommands = DB::table('bot_commands')->where('bot_id','=',$botid)->limit($limit)->offset($start)->get();
		
		return view('front.bots.paginnate_bot_command', compact('botCommands','total_bot_commands','limit','botid','current_page','adjacents'));
	}
	
	public function bot_command_delete($botId = NULL,$commandID = NULL){
		if(!empty($botId) && !empty($commandID)){
			DB::table('bot_commands')->where('id', '=', $commandID)->delete();
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/bots.command_deleted'));
		}
		else{
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/bots.command_error'));
		}
	}
	
	public function bot_command_edit($id = NULL){
		if(!empty($id)){
			$total_bots = $this->botsTOTAL;
			$total_chanels = $this->chanelTOTAL;

			$userId = Auth::user()->id;

			$Form_action = '';
			$search = '';
			if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
				$search = $_REQUEST['search'];
			}
			

			$botCommands = DB::table('bot_commands')->where('id','=',$id)->get();					
			return view('front.bots.bot_command_edit',compact('total_bots','total_chanels','Form_action','search','botCommands'));
		}
		else{
			return redirect('dashboard')->with('ok', trans('front/dashboard.error'));
		}
	}
	
	public function bot_command_update(Request $request)
	{
		if(!empty($request->get('id'))){
			$id = $request->get('id');
			$bot_id = $request->get('bot_id');
			$rules = array(
				'title' => 'required|unique:bot_commands,title,'.$id,
			);

			$v = Validator::make($request->all(), $rules);

			if($v->passes())
			{
				$bot_command = BotCommand::find($request->get('id'));
				$bot_command->id = $request->get('id');
				$bot_command->title = $request->get('title');
				$bot_command->command_description = $request->get('command_description');
				
				$img_name_s = $request->get('old_img');
				if($request->hasFile('image'))
				{
					$error_img = $_FILES["image"]["error"];
					$img_name = $_FILES["image"]["name"];
	
					if($error_img == '0' && $img_name != '' )
					{
					   $img_path = $_FILES["image"]["tmp_name"];
					   //$img_name_s = time()."_".$img_name;
					   $ext = pathinfo($img_name);
					   $img_name_s = time().'.'.$ext['extension'];
					   $upload_img = public_path().'/uploads/'.$img_name_s;
	
					   move_uploaded_file($img_path,$upload_img);
					}
				}
	
				$bot_command->image = $img_name_s;
	
				$bot_command->save();
	
				return redirect('bot/detail/'.$bot_id)->with('ok', trans('front/command.updated'));
			}
			else{
				$messages = $v->messages();
				return redirect('bot/bot_command_edit/'.$id)->withErrors($v);
			}
		}
	}

    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Plan $plan)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update()
    {
	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
		
    }
	
	
	public function bot_delete($id){
		 $conditions = ['id' => $id];
        $bot = DB::table('bots')->where($conditions)->get();
        
        $stripe_customer_id = $bot[0]->stripe_customer_id;
		$stripe_subscription_id = $bot[0]->stripe_subscription_id;
      
        
        $stripe = Stripe::make(env('STRIPE_APP_KEY'));
        
		$get_subscription = $stripe->subscriptions()->all($stripe_customer_id);
		if(isset($get_subscription['data']) && !empty($get_subscription['data'])){
			foreach($get_subscription['data'] as $sk1 => $sv1){
				if($sv1['id'] == $stripe_subscription_id){
					$subscription = $stripe->subscriptions()->cancel($stripe_customer_id, $stripe_subscription_id,false);
					if(isset($subscription['id']) && !empty($subscription['id'])){
						DB::table('bots')->where('id', '=', $id)->delete();
						return redirect('front_user')->with('ok', trans('front/bot.deleted'));
					}
					else{
						return redirect('front_user')->with('ok', trans('front/bot.deleted'));
					}	
				}
				else{
					DB::table('bots')->where('id', '=', $id)->delete();
					return redirect('front_user')->with('ok', trans('front/bot.deleted'));
				}
			}
		}
		else{
			DB::table('bots')->where('id', '=', $id)->delete();
			return redirect('front_user')->with('ok', trans('front/bot.deleted'));
		}

		
	}
    
    
    
    /* in amdin section */
    public function userbot($user_id){
        $totalBots = Bot::where(['user_id' => $user_id])->count();
        $bots = Bot::where('user_id', '=', $user_id)->paginate(2);
		$links = $bots->render();
        return view('back.bots.index', compact('bots', 'links','totalBots'));	
    }
    /***************************************/
    
    
    public function admin_bot_detail($bot_id = NULL){
        $bots = '';
        $autoResponse = '';
        $contactForm = '';
        $gallery = '';
        $chanels = '';
        $messages = '';
        $bot_uesers = '';
		$botCommands = '';
        
        if(!empty($bot_id)){
            $bots = DB::table('bots')->where('id', '=', $bot_id)->get();
            $autoResponse = DB::table('autoresponses')->where('type_id', '=', $bot_id)->get();
            $contactForm = DB::table('contact_forms')->where('type_id', '=', $bot_id)->get();
            $gallery = DB::table('galleries')->where('type_id', '=', $bot_id)->get();
            $chanels = DB::table('chanels')->where('type_id', '=', $bot_id)->get();
            
            $messages = DB::table('bot_messages')->where('bot_id', '=', $bot_id)->get();
            $bot_uesers = DB::table('bot_users')->where('bot_id', '=', $bot_id)->get();
			
			$botCommands = DB::table('bot_commands')->where('bot_id','=',$bot_id)->get();
            
        }
        
        return view('back.bots.bot_details', compact('bots', 'autoResponse','contactForm','gallery','chanels','messages','bot_uesers','botCommands'));
    }
    
    
    public function contactform_ques($contact_form_id = NULL){
        $questions = '';
        if(!empty($contact_form_id)){
            $questions = DB::table('contact_form_questions')
                            ->where('contact_form_id','=',$contact_form_id)
                            ->get();
        }
        return view('back.bots.contact_form_ques', compact('questions'));
    }
    
    public function gallery_img($gID = NULL){
        $g_images = '';
        if(!empty($gID)){
            $g_images = DB::table('gallery_images')
                            ->where('gallery_id','=',$gID)
                            ->get();
        }
        return view('back.bots.gallery_img', compact('g_images'));
    }
    
    
    
    public function setweb_hook(Request $request){
        
        $token = $request->get('bot_access_token');
        $username = $request->get('bot_uname');
        
        if(isset($token) && !empty($token)){
            $url = 'https://app.citymes.com/'.$token.'/webhook';
           // echo $url;die;
           //https://api.telegram.org/bot288327776:AAEVhPyxdDqOjtNb6_q35mVptq1X3kjRsDA/setwebhook?url=https://app.citymes.com/288327776:AAEVhPyxdDqOjtNb6_q35mVptq1X3kjRsDA/webhook 
            $telegram = new Api($token);
            $result = $telegram->setWebhook(['url' => $url]);
			
			//$rs = $telegram->getWebhookInfo();
			//$rs = $telegram->WebhookInfo();
			//echo '<pre>';print_r($rs);die;
            
            $res = json_decode(json_encode($result));
            echo $res[0];
            exit;
        }
    }
    
    public function edit_bot($bot_id = NULL){
		if(!empty($bot_id)){
			
			/* nickName popup */
			$nickName = DB::table('pages')
							->where('id','=','13')
							->get();
							
			
            $bot = Bot::find($bot_id);
            
            $total_bots = $this->botsTOTAL;
            $total_chanels = $this->chanelTOTAL;

            $user_id = Auth::user()->id;

            $Form_action = 'bot/update_bot/'.$bot_id;

            $search = '';
            if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
                $search = $_REQUEST['search'];
            }

            return view('front.bots.update_bot',compact('total_bots','total_chanels','Form_action','search','bot','nickName'));
        }
    }
    
    public function update_bot(Request $request){
        $id = $request->get('id');
        if(!empty($id)){
          $bot = Bot::find($request->get('id'));
		  $bot->id = $request->get('id');
          $bot->nick_name = $request->get('nick_name');
		  $bot->error_msg = $request->get('error_msg');
		  $bot->comanda = $request->get('comanda');
          $bot->bot_description = $request->get('bot_description');
          $bot->start_message = $request->get('start_message');
          $bot->autoresponse = $request->get('autoresponse');
          $bot->contact_form = $request->get('contact_form');
          $bot->galleries = $request->get('galleries');
          $bot->channels = $request->get('channels');
		  
		  $bot->intro_autoresponses = $request->get('intro_autoresponses');
		  $bot->intro_contact_form = $request->get('intro_contact_form');
		  $bot->intro_galleries = $request->get('intro_galleries');
		  $bot->intro_channels = $request->get('intro_channels');
          
          if($bot->save()){
            return redirect('bot/detail/'.$id)->with('ok', trans('front/bots.updated'));
          }
        }
    }
	
	
	
	public function bot_subscription_cancel($botID = NULL)
	{
		if(!empty($botID)){
			$conditions = ['id' => $botID];
			$bot = DB::table('bots')->where($conditions)->get();
							
			$stripe_customer_id = $bot[0]->stripe_customer_id;
			$stripe_subscription_id = $bot[0]->stripe_subscription_id;
		  
			$stripe = Stripe::make(env('STRIPE_APP_KEY'));
			
			$get_subscription = $stripe->subscriptions()->all($stripe_customer_id);
			if(isset($get_subscription['data']) && !empty($get_subscription['data']))
			{
				foreach($get_subscription['data'] as $sk1 => $sv1)
				{
					if($sv1['id'] == $stripe_subscription_id)
					{
						$subscription = $stripe->subscriptions()->cancel($stripe_customer_id, $stripe_subscription_id,false);
						DB::table('bots')->where('id', $botID)->update(['is_subscribe' => 1]);
						
						$contactFormEmail = DB::table('site_settings')
							->where('name','=','contact_form_email')
							->get();
					   	$from_email = $contactFormEmail[0]->value;	
							
						//$to_email = $contactFormEmail[0]->value;//$request->get('email');
						$to_email = Auth::user()->email;	
						$email_template = DB::table('email_templates')
											->where('title','LIKE','subscription_cancellation')
											->get();
						$email_subject = $email_template[0]->subject;						
						$template = $email_template[0]->description;
						
						$MESSAGE = '<b>Bot "'.$bot[0]->username.'"</b>';
						
						$emailFindReplace = array(
							'##MESSAGE##' => $MESSAGE
						);
						
						$html = strtr($template, $emailFindReplace);
						
						$email_arr = array();
						$email_arr['to_email'] = $to_email;
						$email_arr['subject'] = $email_subject;
						$email_arr['from'] = $from_email;
				
						\Mail::send(['html' => 'front.bots.email_bot_template'],
							array(
								'text' => $html
							), function($message) use ($email_arr)
						{
							$message->from($email_arr['from']);
							$message->to($email_arr['to_email'])->subject($email_arr['subject']);
						});
			
						if(isset($subscription['id']) && !empty($subscription['id']))
						{
							return redirect('front_user')->with('ok', trans('front/fornt_user.subscription_cancled'));
						}
						else{
							return redirect('front_user')->with('ok', trans('front/fornt_user.subscription_cancled'));
						}	
					}
				}
			}
			else{
				return redirect('front_user')->with('ok', trans('front/fornt_user.subscription_already_cancled'));
			}

		}
	}
	
	
	public function delete_log(){
		$last_date = date('Y-m-d', strtotime('-3 month'));
		$botMessages = DB::table('bot_messages')
                                ->where('bot_messages.date', '<', $last_date)
	                            ->orderby('bot_messages.id','DESC')
	                            ->get();
		//echo '<pre>';print_r($botMessages);die;		
		if(!empty($botMessages)){
			foreach($botMessages as $k1 => $v1)
			{
				DB::table('bot_messages')->where('id', '=', $v1->id)->delete();
			}
		}
		exit();
	}
	
	
	public function add_bot_command($bot_id)
	{
		$total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        $Form_action = 'bot/create';
        
        $search = '';
		return view('front.bots.add_bot_command',compact('Form_action','total_bots','total_chanels','search','bot_id'));
	}
	
	public function addbot_command(Request $request)
	{		
		if(!empty($request->get('bot_id')))
		{
			$bot_id = $request->get('bot_id');
			$title = $request->get('title');
			$command_description = $request->get('command_description');
			
			$error = 'false';
			
			$chkCommand = DB::table('bot_commands')
							->where('title','LIKE','%'.$title.'%')
							->where('bot_id','=',$bot_id)
							->get();
			if($error == 'false' && isset($chkCommand[0]->title) && !empty($chkCommand[0]->title)){
				$error = 'true';
			}
			
			
			$rules = array(
				'title' => 'required',
				'command_description' => 'required',
			);
	
			$v = Validator::make($request->all(), $rules);
	
			if($error == 'false' && $v->passes()) 
			{
				$bot_command = new BotCommand;
				
				$bot_command->bot_id = $bot_id;
				$bot_command->title = $title;
				$bot_command->command_description = $command_description;
				
				$img_name_s = '';
				if($request->hasFile('image'))
				{
					$error_img = $_FILES["image"]["error"];
					$img_name = $_FILES["image"]["name"];
	
					if($error_img == '0' && $img_name != '' )
					{
					   $img_path = $_FILES["image"]["tmp_name"];
					   //$img_name_s = time()."_".$img_name;
					   $ext = pathinfo($img_name);
					   $img_name_s = time().'.'.$ext['extension'];
					   $upload_img = public_path().'/uploads/'.$img_name_s;
	
					   move_uploaded_file($img_path,$upload_img);
					}
				}
				
				$bot_command->image = $img_name_s;
				
				if($bot_command->save()){
					return redirect('bot/detail/'.$bot_id)->with('ok', trans('back/bot.command_created'));
				}
			}
			else{
				if($error == 'true'){
					$messages = trans('back/bot.command_created_err');
					return redirect('bot/bot_command/'.$bot_id)->withErrors($messages);
				}
				else{
					$messages = $v->messages();
					return redirect('bot/bot_command/'.$bot_id)->withErrors($v);
				}
			}
		
		}
	}
	
	
	public function upgradeplan($bot_id = NULL)
	{
		$subscriptions = '';
		$total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        $Form_action = 'bot/create';
        
        $search = '';
		
		$plans = DB::table('plans')->where('status','=','1')->where('plan_type','=','1')->get();
				
		if(!empty($bot_id))
		{
			$botData = DB::table('bots')->where('id','=',$bot_id)->get();
			$userID = (isset($botData[0]->user_id) && !empty($botData[0]->user_id))?$botData[0]->user_id:'';
			
			if(!empty($userID) && !empty($bot_id)){
				$subscriptions = DB::table('user_subscriptions')
								->where('types','LIKE','bot')
								->where('type_id','=',$bot_id)
								->where('user_id','=',$userID)
								->get();
			}
		}
		
		return view('front.bots.upgradeplan',compact('Form_action','total_bots','total_chanels','search','bot_id','subscriptions','plans'));
	}
	
	public function upgrade_plan(Request $request)
	{
		$stripe = Stripe::make(env('STRIPE_APP_KEY'));
		
		$free = ($request->get('plan_free') && !empty($request->get('plan_free')))?$request->get('plan_free'):'';
            
        $plans = DB::table('plans')->where('id', '=', $request->get('plan_id'))->get();

        $stripe_plan_id = $plans[0]->stripeplanid;
		$price = $plans[0]->price;
		
		$botData = DB::table('bots')->where('id','=',$request->get('bot_id'))->get();
		$user_id = $botData[0]->user_id;
		$stripe_subscription_id = $botData[0]->stripe_subscription_id;
		$stripe_customer_id = $botData[0]->stripe_customer_id;
		
		if(!empty($user_id) && !empty($request->get('bot_id'))){
				$subscriptions = DB::table('user_subscriptions')
								->where('types','LIKE','bot')
								->where('type_id','=',$request->get('bot_id'))
								->where('user_id','=',$user_id)
								->get();
			}
		
		if(empty($free))
		{
			$subscriptionsID = $subscriptions[0]->id;				
			$subscription = $stripe->subscriptions()->update($stripe_customer_id, $stripe_subscription_id, ['plan' => $stripe_plan_id]);
					
			if(isset($subscription) && !empty($subscription) && !empty($subscriptionsID))
			{
				$user_subscription = UserSubscription::find($subscriptionsID);
	
				$user_subscription->user_id = $user_id;
				$user_subscription->plan_id = $stripe_plan_id;
				$user_subscription->types = 'bot';
				$user_subscription->type_id = $request->get('bot_id');
				$user_subscription->price = $price;
				$user_subscription->subscription_date = date('Y-m-d',$subscription['current_period_start']);
				$user_subscription->expiry_date = date('Y-m-d',$subscription['current_period_end']);
				$user_subscription->last_billed = date('Y-m-d');
				$user_subscription->status = 'Completed';
				$user_subscription->created_at = date('Y-m-d h:i:s');
				$user_subscription->updated_at = date('Y-m-d h:i:s');
	
				$user_subscription->save();
				
				return redirect('bot/detail/'.$request->get('bot_id'))->with('ok', trans('back/bot.plan_update'));
			}
		}
		else
		{
			$conditions = ['id' => $request->get('bot_id')];
			$bot = DB::table('bots')->where($conditions)->get();
			
			$subscription_cancel = $stripe->subscriptions()->cancel($stripe_customer_id, $stripe_subscription_id,false);
			DB::table('bots')->where('id', $request->get('bot_id'))->update(['is_subscribe' => 1]);
			
			$contactFormEmail = DB::table('site_settings')
				->where('name','=','contact_form_email')
				->get();
			$from_email = $contactFormEmail[0]->value;	
				
			//$to_email = $contactFormEmail[0]->value;//$request->get('email');
			$to_email = Auth::user()->email;	
			$email_template = DB::table('email_templates')
								->where('title','LIKE','subscription_cancellation')
								->get();
			$email_subject = $email_template[0]->subject;						
			$template = $email_template[0]->description;
			
			$MESSAGE = '<b>Bot "'.$bot[0]->username.'"</b>';
			
			$emailFindReplace = array(
				'##MESSAGE##' => $MESSAGE
			);
			
			$html = strtr($template, $emailFindReplace);
			
			$email_arr = array();
			$email_arr['to_email'] = $to_email;
			$email_arr['subject'] = $email_subject;
			$email_arr['from'] = $from_email;
	
			\Mail::send(['html' => 'front.bots.email_bot_template'],
				array(
					'text' => $html
				), function($message) use ($email_arr)
			{
				$message->from($email_arr['from']);
				$message->to($email_arr['to_email'])->subject($email_arr['subject']);
			});
						
			
			$subscriptionsID = $subscriptions[0]->id;	
			$subscription = $stripe_plan_id;
			
			$subscription_date = date('Y-m-d');
			$duration = $plans[0]->duration;
			$expiry_date = date('Y-m-d',strtotime('+'.$duration.' month'));
			
			$user_subscription = UserSubscription::find($subscriptionsID);
			
			$user_subscription->user_id = $user_id;
			
			if(empty($stripe_plan_id)){
				$user_subscription->plan_id = $plans[0]->id;
			}
			else{
				$user_subscription->plan_id = $stripe_plan_id;
			}
			$user_subscription->types = 'bot';
			$user_subscription->type_id = $request->get('bot_id');
			$user_subscription->price = $price;
			$user_subscription->subscription_date = $subscription_date;
			$user_subscription->expiry_date = $expiry_date;
			$user_subscription->last_billed = date('Y-m-d');
			$user_subscription->status = 'Completed';
			$user_subscription->created_at = date('Y-m-d h:i:s');
			$user_subscription->updated_at = date('Y-m-d h:i:s');

			$user_subscription->save();
			
			return redirect('bot/detail/'.$request->get('bot_id'))->with('ok', trans('back/bot.plan_update'));
		}		
	}
    
}
