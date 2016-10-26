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
        parent::getTotalbot_chanel();
        
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
                        ->where('name', 'LIKE', '%'.$search.'%')
                        ->get();
        }
        else{
            $plans = DB::table('plans')->get();
        }
        
        return view('front.mychannel.create', compact('plans', 'email', 'country','total_bots','total_chanels','Form_action','search'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(MyChannelCreateRequest $request) {
        $firstName = Auth::user()->first_name;
        $lastName = Auth::user()->last_name;
        $user_id = Auth::user()->id;
        $email = Auth::user()->email;

        $stripeToken = $request->get('stripeToken');


        // echo '<pre>';print_r($request->all());die;

        $chanel = new MyChannel;

        $chanel->user_id = $user_id;
        $chanel->name = $request->get('name');
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
                'plan' => $stripe_plan_id
            ]);


            if (!empty($customer)) {
                $customerID = $customer['id'];

                $chanel = MyChannel::find($lastInsertId);
                $chanel->stripe_customer_id = $customerID;
                $chanel->save();

                /* user_subscriptions */
                $user_subscription = new UserSubscription;

                $user_subscription->user_id = $user_id;
                $user_subscription->plan_id = $stripe_plan_id;
                $user_subscription->types = 'Channel';
                $user_subscription->type_id = $lastInsertId;
                $user_subscription->price = $request->get('plan_price');
                $user_subscription->subscription_date = date('Y-m-d', $customer['subscriptions']['data'][0]['current_period_start']);
                $user_subscription->expiry_date = date('Y-m-d', $customer['subscriptions']['data'][0]['current_period_end']);
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
            }
            return redirect('dashboard')->with('ok', trans('front/MyChannel.created'));
        } else {
            return redirect('dashboard')->with('ok', trans('front/MyChannel.error'));
        }
    }

    public function detail($botid = NULL) {
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
        
        $Form_action = '';
       	$search = '';
       	if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
       	}
        
        if (!empty($botid)) {
            $chanels = DB::table('my_channels')->where('id', '=', $botid)->get();
            
            $chanelMesg = DB::table('channel_send_message')->where('channel_id', '=', $botid)->get();
           // echo '<pre>';print_r($chanels);die;

            return view('front.mychannel.detail', compact('chanels','total_bots','total_chanels','Form_action','search','chanelMesg'));
        }
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
    public function destroy(Plan $plan) {
        
    }

    /* in amdin section */

    function userbot($user_id) {
        $totalBots = Bot::where(['user_id' => $user_id])->count();
        $bots = Bot::where('user_id', '=', $user_id)->paginate(2);
        $links = $bots->render();
        return view('back.bots.index', compact('bots', 'links', 'totalBots'));
    }
    
    
    public function edit_channel($channel_id = NULL){
        if(!empty($channel_id)){
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
            return view('front.mychannel.edit_channel',compact('total_bots','total_chanels','Form_action','search','channel'));
        }
    }
    
    public function update_channel(Request $request){
        $id = $request->get('id');
        if(!empty($id)){
            $channel = MyChannel::find($id);
            $channel->id = $id;
            $channel->name = $request->get('name');
            $channel->description = $request->get('description');
            
            if($channel->save()){
                return redirect('dashboard')->with('ok', trans('front/MyChannel.updated'));
              }
        }
    }

}
