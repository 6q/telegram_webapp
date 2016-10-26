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
use App\Models\UserBilling;
use App\Models\UserSubscription;
use App\Models\UserTransaction;

use Telegram\Bot\Api;

class BotController extends Controller
{
    public function __construct() {
        parent::getTotalbot_chanel();
        
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
                        ->where('name', 'LIKE', '%'.$search.'%')
                        ->get();
        }
        else{
            $plans = DB::table('plans')->get();
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
        
        
        return view('front.bots.create',compact('plans','email','country','total_bots','total_chanels','Form_action','search','billing_details','states'));
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
		$bot->bot_description = $request->get('bot_description');
		$bot->start_message = $request->get('start_message');
		$bot->autoresponse = $request->get('autoresponse');
		$bot->contact_form = $request->get('contact_form');
		$bot->galleries = $request->get('galleries');
		$bot->channels = $request->get('channels');
		
        
        if($request->hasFile('bot_image'))
		{
			$error_img = $_FILES["bot_image"]["error"];
			$img_name = $_FILES["bot_image"]["name"];
			
            if($error_img == '0' && $img_name != '' )
			{
			   $img_path = $_FILES["bot_image"]["tmp_name"];
			   $img_name_s = time()."_".$img_name;
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
            
            
            $customer = $stripe->customers()->create([
                'source'  => $stripeToken,
                'email' => $email,
                'plan' => $stripe_plan_id
            ]);
            
            
            if(!empty($customer)){
                $customerID = $customer['id'];
                
                $bot = Bot::find($lastInsertId);
                $bot->stripe_customer_id = $customerID;
                $bot->save();
                                
                /* user_subscriptions */
                $user_subscription = new UserSubscription;

                $user_subscription->user_id = $user_id;
                $user_subscription->plan_id = $stripe_plan_id;
                $user_subscription->types = 'bot';
                $user_subscription->type_id = $lastInsertId;
                $user_subscription->price = $request->get('plan_price');
                $user_subscription->subscription_date = date('Y-m-d',$customer['subscriptions']['data'][0]['current_period_start']);
                $user_subscription->expiry_date = date('Y-m-d',$customer['subscriptions']['data'][0]['current_period_end']);
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
        
        if(!empty($botid)){
            $bots = DB::table('bots')->where('id', '=', $botid)->get();
		    //echo '<pre>';print_r($bots);die;
            
            if(!empty($search)){
                $autoResponse = DB::table('autoresponses')
                                    ->where('type_id', '=', $botid)
                                    ->where('submenu_heading_text', 'LIKE', '%'.$search.'%')
                                    ->get();
                
                $contactForm = DB::table('contact_forms')
                                    ->where('type_id', '=', $botid)
                                    ->where('submenu_heading_text', 'LIKE', '%'.$search.'%')
                                    ->get();
                
                $gallery = DB::table('galleries')
                                    ->where('type_id', '=', $botid)
                                    ->where('gallery_submenu_heading_text', 'LIKE', '%'.$search.'%')
                                    ->get();
                
                $chanels = DB::table('chanels')
                                    ->where('type_id', '=', $botid)
                                    ->where('chanel_submenu_heading_text', 'LIKE', '%'.$search.'%')
                                    ->get();
                
                $activeUser = '';
                $botMessages = '';
                
            }
            else{
                $autoResponse = DB::table('autoresponses')->where('type_id', '=', $botid)->get();
                $contactForm = DB::table('contact_forms')->where('type_id', '=', $botid)->get();
                $gallery = DB::table('galleries')->where('type_id', '=', $botid)->get();
                $chanels = DB::table('chanels')->where('type_id', '=', $botid)->get();
                
                $activeUser = DB::table('bot_users')->where('bot_id', '=', $botid)->get();
                
                $botMessages = DB::table('bot_messages')
                                ->join('bot_users', 'bot_users.id', '=', 'bot_messages.bot_user_id')
                                ->where('bot_messages.bot_id', '=', $botid)->get();
            }
            
            return view('front.bots.detail', compact('bots','autoResponse','contactForm','gallery','chanels','total_bots','total_chanels','Form_action','search','activeUser','botMessages'));	
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
        $conditions = ['id' => $id];
        $bot = DB::table('bots')->where($conditions)->get();
        
        $stripe_customer_id = $bot[0]->stripe_customer_id;
        echo $stripe_customer_id;
      
        /*
        $stripe = Stripe::make(env('STRIPE_APP_KEY'));
        
        $customer = $stripe->customers()->find($stripe_customer_id);
        
        echo '<pre>';print_r($customer);echo '</pre>';
        */
        echo '<pre>';print_r($bot);die;
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
        
        if(!empty($bot_id)){
            $bots = DB::table('bots')->where('id', '=', $bot_id)->get();
            $autoResponse = DB::table('autoresponses')->where('type_id', '=', $bot_id)->get();
            $contactForm = DB::table('contact_forms')->where('type_id', '=', $bot_id)->get();
            $gallery = DB::table('galleries')->where('type_id', '=', $bot_id)->get();
            $chanels = DB::table('chanels')->where('type_id', '=', $bot_id)->get();
            
            $messages = DB::table('bot_messages')->where('bot_id', '=', $bot_id)->get();
            $bot_uesers = DB::table('bot_users')->where('bot_id', '=', $bot_id)->get();
            
        }
        
        return view('back.bots.bot_details', compact('bots', 'autoResponse','contactForm','gallery','chanels','messages','bot_uesers'));
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
            
            $res = json_decode(json_encode($result));
            echo $res[0];
            exit;
        }
    }
    
    public function edit_bot($bot_id = NULL){
        if(!empty($bot_id)){
            $bot = Bot::find($bot_id);
            
            $total_bots = $this->botsTOTAL;
            $total_chanels = $this->chanelTOTAL;

            $user_id = Auth::user()->id;

            $Form_action = 'bot/update_bot/'.$bot_id;

            $search = '';
            if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
                $search = $_REQUEST['search'];
            }

            return view('front.bots.update_bot',compact('total_bots','total_chanels','Form_action','search','bot'));
        }
    }
    
    public function update_bot(Request $request){
        $id = $request->get('id');
        if(!empty($id)){
          $bot = Bot::find($request->get('id'));
		  $bot->id = $request->get('id');
          $bot->nick_name = $request->get('nick_name');
          $bot->bot_description = $request->get('bot_description');
          $bot->start_message = $request->get('start_message');
          $bot->autoresponse = $request->get('autoresponse');
          $bot->contact_form = $request->get('contact_form');
          $bot->galleries = $request->get('galleries');
          $bot->channels = $request->get('channels');
          
          if($bot->save()){
            return redirect('dashboard')->with('ok', trans('front/bots.updated'));
          }
        }
    }
    
}
