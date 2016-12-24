<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;
use App\Http\Requests;
use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Requests\MyChannelCreateRequest;
use App\Repositories\UserRepository;
use App\Models\MyChannel;
use App\Models\UserBilling;
use App\Models\UserSubscription;
use App\Models\UserTransaction;

class MyChannelController extends Controller {
    
    public function __construct() {
		parent::login_check();
        parent::getTotalbot_chanel();
		
		define('PAGE_DATA_LIMIT','10');
		define('PAGE_ADJACENTS','3');
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        $bots = MyChannel::latest()
                ->paginate(20);
        $links = $bots->render();

        return view('front.mychannel.index', compact('bots', 'links','total_bots','total_chanels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request) {
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        $email = Auth::user()->email;
        $country = DB::table('countries')->get();
        
        $Form_action = 'my_channel/create';
       	$search = '';
       	if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
       	}
        
        if(!empty($search)){
            $plans = DB::table('plans')
						->where('plan_type','=','2')
                        ->where('name', 'LIKE', '%'.$search.'%')
                        ->get();
        }
        else{
            $plans = DB::table('plans')->where('plan_type','=','2')->get();
        }
		
		$userId = Auth::user()->id;
		$bots = DB::table('bots')
            ->where('user_id', '=', $userId)
            ->get();
		
		/* channelName popup */
        $channelName = DB::table('pages')
                        ->where('id','=','16')
                        ->get();
        
        return view('front.mychannel.create', compact('plans', 'email', 'country','total_bots','total_chanels','Form_action','search','channelName','bots'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(MyChannelCreateRequest $request) 
	{
        $firstName = Auth::user()->first_name;
        $lastName = Auth::user()->last_name;
        $user_id = Auth::user()->id;
        $email = Auth::user()->email;

        $stripeToken = $request->get('stripeToken');


        $chanel = new MyChannel;

        $chanel->user_id = $user_id;
        $chanel->name = $request->get('name');
		$chanel->bot_id = $request->get('botID');
        $chanel->description = $request->get('description');
        $chanel->share_link = $request->get('share_link');

        $chanel->created_at = date('Y-m-d h:i:s');



        if ($chanel->save()) {
            $lastInsertId = $chanel->id;

            /* User billing */
            $user_billings = DB::table('user_billings')->where('user_id', '=', $user_id)->get();
            if (!empty($user_billings)) {
                $ubId = $user_billings[0]->id;
                $user_billings = UserBilling::find($ubId);
            } else {
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
                'source' => $stripeToken,
                'email' => $email,
                //'plan' => $stripe_plan_id
            ]);


            if (!empty($customer)) {
                $customerID = $customer['id'];
				
				$chanel_subscription = $stripe->subscriptions()->create($customerID, ['plan' => $stripe_plan_id]);

                $chanel = MyChannel::find($lastInsertId);
                $chanel->stripe_customer_id = $customerID;
				$chanel->stripe_subscription_id = $chanel_subscription['id'];
                $chanel->save();

                /* user_subscriptions */
                $user_subscription = new UserSubscription;

                $user_subscription->user_id = $user_id;
                $user_subscription->plan_id = $stripe_plan_id;
                $user_subscription->types = 'Channel';
                $user_subscription->type_id = $lastInsertId;
                $user_subscription->price = $request->get('plan_price');
                $user_subscription->subscription_date = date('Y-m-d', $chanel_subscription['current_period_start']);
                $user_subscription->expiry_date = date('Y-m-d', $chanel_subscription['current_period_end']);
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
                $user_transaction->types = 'Channel';
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
								
				//$to_email = $contactFormEmail[0]->value;//$request->get('email');
				$to_email = $request->get('email');	
				$email_template = DB::table('email_templates')
												->where('title','LIKE','channel_email')
												->get();
				$template = $email_template[0]->description;	
				
				$planDetail = DB::table('plans')
										->where('id','=',$request->get('plan_id'))
										->get();
										
				$planName = (isset($planDetail[0]->name) && !empty($planDetail[0]->name))?$planDetail[0]->name:'';				
				$NO_MESSAGE_PER_DAY = (isset($planDetail[0]->manual_message) && !empty($planDetail[0]->manual_message))?$planDetail[0]->manual_message:'';
				$PRICE = $request->get('plan_price');
				
				
				$country = DB::table('countries')
								->where('id','=',$request->get('country'))
								->get();
				$countryName = (isset($country[0]->name) && !empty($country[0]->name))?$country[0]->name:'';				
		
				
				$state = DB::table('states')
								->where('id','=',$request->get('state'))
								->get();
				$stateName = (isset($state[0]->name) && !empty($state[0]->name))?$state[0]->name:'';
				
				$emailFindReplace = array(
					'##SITE_LOGO##' => asset('/img/logo.png'),
					'##SITE_LINK##' => asset('/'),
					'##SITE_NAME##' => 'Citymes',
					'##PLAN_USERNAME##' => $planName,
					'##PRICE##' => $request->get('plan_price'),
					'##NO_MESSAGE_PER_DAY##' => $NO_MESSAGE_PER_DAY,
					'##CHANNEL_NAME##' => $request->get('name'),
					'##DESCRIPTION##' => $request->get('description'),
					'##CHANNEL_SHARE_LINK##' => $request->get('share_link'),
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
				
				\Mail::send(['html' => 'front.bots.email_bot_template'],
					array(
						'text' => $html
					), function($message) use ($to_email)
				{
					$message->from('help@citymes.com');
					$message->to($to_email, 'Citymes')->subject('[Citymes] Nou canal creat');
				});
		
            }
            return redirect('dashboard')->with('ok', trans('front/MyChannel.created'));
        } else {
            return redirect('dashboard')->with('ok', trans('front/MyChannel.error'));
        }
    }

    public function detail($botid = NULL) {
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
		
		$bots = $total_bots;
		        
        $Form_action = '';
       	$search = '';
       	if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
       	}		
		
		$limit = PAGE_DATA_LIMIT; 
        $page = 1;
		$adjacents = PAGE_ADJACENTS;
		
        if (!empty($botid)) {
            $chanels = DB::table('my_channels')->where('id', '=', $botid)->get();
            
			$Total_chanelMesg = DB::table('channel_send_message')->where('channel_id', '=', $botid)->get();
			$total_pages = count($Total_chanelMesg);
		
            $chanelMesg = DB::table('channel_send_message')->where('channel_id', '=', $botid)->orderby('id','desc')->limit($limit)->get();
           // echo '<pre>';print_r($chanels);die;

            return view('front.mychannel.detail', compact('chanels','total_bots','total_chanels','Form_action','search','chanelMesg','bots','total_pages','limit','page','adjacents'));
        }
    }
	
	public function paginate_channel_msg(Request $request){
		$adjacents = PAGE_ADJACENTS;
		$limit = PAGE_DATA_LIMIT;
		$channelId = $request->get('channelId');
		$current_page = ($request->get('pageId') && !empty($request->get('pageId')))?$request->get('pageId'):1;
		if($current_page){
			$start = ($current_page - 1) * $limit;
		}
		else
		{
			$start = 0;	
		}
		
		$Total_chanelMesg = DB::table('channel_send_message')->where('channel_id', '=', $channelId)->get();
		$total_pages = count($Total_chanelMesg);
		
		$chanelMesg = DB::table('channel_send_message')->where('channel_id', '=', $channelId)->limit($limit)->offset($start)->get();
		
		return view('front.mychannel.paginnate_channel_message', compact('chanelMesg','total_pages','limit','channelId','current_page','adjacents'));
	}
	
	
	public function getchannelcharts(Request $request){
		$userId = Auth::user()->id;
		$chart_time = ($request->get('chart_time') && !empty($request->get('chart_time')))?$request->get('chart_time'):'';
		$channelId = ($request->get('channel_id') && !empty($request->get('channel_id')))?$request->get('channel_id'):'';
		
		$channelData = DB::table('my_channels')
						->where('id','=',$channelId)
						->get();
		
		$startDate = date('Y-m-d', strtotime('today - 30 days'));
        $endDate = date('Y-m-d');
        $output_format = 'Y-m-d';
        $step = '+1 day';
		
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
		$arr[$i][1] = (isset($channelData[0]->name) && !empty($channelData[0]->name))?$channelData[0]->name:'Channel seleccionat';
		
		$i = 1;
		foreach($arr_dates as $k1 => $v1){
			 $count_bot = DB::table('channel_send_message')
							->where('channel_id','=',$channelId)
							->where('send_date','LIKE','%'.$v1.'%')
							->get();
			
			$arr[$i][0] = date('d.m',strtotime($v1));
			$arr[$i][1] = count($count_bot);	
			$i++;
		}
		
		$arr = json_encode($arr);
        echo $arr;die;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show() {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Plan $plan) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update() {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(MyChannel $MyChannel) {
        $MyChannel->delete();
        return redirect('user')->with('ok', trans('back/user.channel_destroyed'));
    }
	
	public function channel_delete($id){
		$conditions = ['id' => $id];
        $channel = DB::table('my_channels')->where($conditions)->get();
		
		$stripe_customer_id = $channel[0]->stripe_customer_id;
		$stripe_subscription_id = $channel[0]->stripe_subscription_id;
	  
		$stripe = Stripe::make(env('STRIPE_APP_KEY'));
		
		$get_subscription = $stripe->subscriptions()->all($stripe_customer_id);
		if(isset($get_subscription['data']) && !empty($get_subscription['data']))
		{
			foreach($get_subscription['data'] as $sk1 => $sv1)
			{
				if($sv1['id'] == $stripe_subscription_id)
				{
					$subscription = $stripe->subscriptions()->cancel($stripe_customer_id, $stripe_subscription_id,false);
					if(isset($subscription['id']) && !empty($subscription['id']))
					{
						DB::table('my_channels')->where('id', '=', $id)->delete();
						return redirect('front_user')->with('ok', trans('front/fornt_user.subscription_cancled'));
					}
					else{
						return redirect('front_user')->with('ok', trans('front/fornt_user.subscription_cancled'));
					}	
				}
			}
		}
		else{
			DB::table('my_channels')->where('id', '=', $id)->delete();
			return redirect('front_user')->with('ok', trans('front/fornt_user.subscription_already_cancled'));
		}
	}
 
    
    public function edit_channel($channel_id = NULL){
        if(!empty($channel_id)){
			$userId = Auth::user()->id;
			$bots = DB::table('bots')
				->where('user_id', '=', $userId)
				->get();
			
            $total_bots = $this->botsTOTAL;
            $total_chanels = $this->chanelTOTAL;

            $user_id = Auth::user()->id;

            $Form_action = 'my_channel/update_channel/'.$channel_id;

            $search = '';
            if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
                $search = $_REQUEST['search'];
            }
            
            $channel = MyChannel::find($channel_id);
            //echo '<pre>';print_r($channel);die;
            return view('front.mychannel.edit_channel',compact('total_bots','total_chanels','Form_action','search','channel','bots'));
        }
    }
    
    public function update_channel(Request $request){
        $id = $request->get('id');
        if(!empty($id)){
            $channel = MyChannel::find($id);
            $channel->id = $id;
			$channel->bot_id = $request->get('botID');
            $channel->name = $request->get('name');
            $channel->description = $request->get('description');
            
            if($channel->save()){
                return redirect('dashboard')->with('ok', trans('front/MyChannel.updated'));
              }
        }
    }
	
	
	
	
	/* in amdin section */
    public function userchannel($user_id){
        $totalChannels = MyChannel::where(['user_id' => $user_id])->count();
        $channels = MyChannel::where('user_id', '=', $user_id)->paginate(2);
		$links = $channels->render();
        return view('back.mychannel.index', compact('channels', 'links','totalChannels'));	
    }
    /***************************************/
	
	
	public function mychannel_detail($channelID){
		 if (!empty($channelID)) {
            $chanels = DB::table('my_channels')->where('id', '=', $channelID)->get();
            $chanelMesg = DB::table('channel_send_message')->where('channel_id', '=', $channelID)->orderby('id','desc')->get();
            return view('back.mychannel.detail', compact('chanels','chanelMesg'));
        }
	}
	
	
	public function channel_subscription_cancel($channelID = NULL){
		
		if(!empty($channelID)){
			$conditions = ['id' => $channelID];
			$my_channels = DB::table('my_channels')->where($conditions)->get();
			
			$stripe_customer_id = $my_channels[0]->stripe_customer_id;
			$stripe_subscription_id = $my_channels[0]->stripe_subscription_id;
		  
			$stripe = Stripe::make(env('STRIPE_APP_KEY'));
			
			$get_subscription = $stripe->subscriptions()->all($stripe_customer_id);
			if(isset($get_subscription['data']) && !empty($get_subscription['data']))
			{
				foreach($get_subscription['data'] as $sk1 => $sv1)
				{
					if($sv1['id'] == $stripe_subscription_id)
					{
						$subscription = $stripe->subscriptions()->cancel($stripe_customer_id, $stripe_subscription_id,false);
						DB::table('my_channels')->where('id', $channelID)->update(['is_subscribe' => 1]);
						
						$contactFormEmail = DB::table('site_settings')
							->where('name','=','contact_form_email')
							->get();
							
						//$to_email = $contactFormEmail[0]->value;//$request->get('email');
						$to_email = Auth::user()->email;	
						$email_template = DB::table('email_templates')
											->where('title','LIKE','subscription_cancellation')
											->get();
											
						$template = $email_template[0]->description;
						$MESSAGE = '<b>Channel "'.$my_channels[0]->name.'"</b>';
						
						$emailFindReplace = array(
							'##SITE_LOGO##' => asset('/img/front/logo.png'),
							'##SITE_LINK##' => asset('/'),
							'##SITE_NAME##' => 'Citymes',
							'##MESSAGE##' => $MESSAGE
						);
						
						$html = strtr($template, $emailFindReplace);
						\Mail::send(['html' => 'front.bots.email_bot_template'],
							array(
								'text' => $html
							), function($message) use ($to_email)
						{
							$message->from('help@citymes.com');
							$message->to($to_email, 'Admin')->subject('[Citymes] Subscripció cancelada');
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

}
