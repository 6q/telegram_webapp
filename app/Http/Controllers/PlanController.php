<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cartalyst\Stripe\Stripe;

use App\Http\Requests;
use App\Http\Requests\PlanCreateRequest;
use App\Models\Plan;

class PlanController extends Controller
{
	public function __construct() {
		 parent::login_check();
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $plans = Plan::latest()->paginate(20);
		$links = $plans->render();
		
		return view('back.plans.index', compact('plans','links'));	
    }
    
    public function botindex(){
        die('af');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('back.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PlanCreateRequest $request)
    {
      	$plan = new Plan;
		
		$plan->name=$request->get('name');
		$plan->plan_type = $request->get('plan_type');
		$plan->description=$request->get('description');
		$plan->duration=$request->get('duration');
		$plan->price=$request->get('price');
		$plan->autoresponses=$request->get('autoresponses');
		$plan->contact_forms=$request->get('contact_forms');
		$plan->image_gallery=$request->get('image_gallery');
		$plan->gallery_images=$request->get('gallery_images');
		$plan->bot_commands = $request->get('bot_commands');
		$plan->manual_message=$request->get('manual_message');
		
		if($request->get('custom_image')!=''){
		 $plan->custom_image=$request->get('custom_image');
		}else{
		 $plan->custom_image=0;
		}
		if($request->get('custom_welcome_message')!=''){
		 $plan->custom_welcome_message=$request->get('custom_welcome_message');
		}else{
		 $plan->custom_welcome_message=0;
		}
		if($request->get('custom_not_allowed_message')!=''){
		 $plan->custom_not_allowed_message=$request->get('custom_not_allowed_message');
		}else{
		 $plan->custom_not_allowed_message=0;
		}
		if($request->get('status')!=''){
		 $plan->status=$request->get('status');
		}else{
		 $plan->status=0;
		}
		
		$plan->save();

		$stripe = Stripe::make(env('STRIPE_APP_KEY'));
		if($plan->price>0){
			$subscription_plan = $stripe->plans()->create([
				'id'                    => $plan->id,
				'name'                  => $request->get('name'),
				'amount'                => $request->get('price'),
				'currency'              => 'EUR',
				'interval'              => 'month',
				'interval_count'        => $request->get('duration')
			]);
			
			if(isset($subscription_plan['id']) && $subscription_plan['id']!=''){
			  $plan = Plan::find($plan->id);
			  $plan->stripeplanid=$subscription_plan['id'];
			  $plan->save();
			}
		}
		

		return redirect('plan')->with('ok', trans('back/plans.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Plan $plan)
    {
        return view('back.plans.show',  compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Plan $plan)
    {
       return view('back.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(PlanCreateRequest $request,Plan $plan)
    {
	
		if($plan->id!=''){
		  $plan = Plan::find($plan->id);
		}else{
		  $plan = new Plan;
		}
			
		$plan->name=$request->get('name');
		$plan->description=$request->get('description');
		$plan->duration=$request->get('duration');
		$plan->price=$request->get('price');
		$plan->autoresponses=$request->get('autoresponses');
		$plan->contact_forms=$request->get('contact_forms');
		$plan->image_gallery=$request->get('image_gallery');
		$plan->gallery_images=$request->get('gallery_images');
		$plan->bot_commands = $request->get('bot_commands');
		$plan->manual_message=$request->get('manual_message');
		
		if($request->get('custom_image')!=''){
		 $plan->custom_image=$request->get('custom_image');
		}else{
		 $plan->custom_image=0;
		}
		if($request->get('custom_welcome_message')!=''){
		 $plan->custom_welcome_message=$request->get('custom_welcome_message');
		}else{
		 $plan->custom_welcome_message=0;
		}
		if($request->get('custom_not_allowed_message')!=''){
		 $plan->custom_not_allowed_message=$request->get('custom_not_allowed_message');
		}else{
		 $plan->custom_not_allowed_message=0;
		}
		if($request->get('status')!=''){
		 $plan->status=$request->get('status');
		}else{
		 $plan->status=0;
		}
		
		
		$stripe = Stripe::make(env('STRIPE_APP_KEY'));
		
		if($plan->price>0){
			if($plan->stripeplanid==''){
			
				$subscription_plan = $stripe->plans()->create([
					'id'                    => $plan->id,
					'name'                  => $request->get('name'),
					'amount'                => $request->get('price'),
					'currency'              => 'EUR',
					'interval'              => 'month',
					'interval_count'              => $request->get('duration')
				]);
			 if(isset($subscription_plan['id']) && $subscription_plan['id']!=''){
			   $plan->stripeplanid=$subscription_plan['id'];
			 }
			}else{
				$subscription_plan = $stripe->plans()->update($plan->stripeplanid, [
					'name' => $request->get('name'),
				]);
			}
		}
       
		
		$plan->save();
		
		return redirect('plan')->with('ok', trans('back/plans.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Plan $plan)
    {
	     $stripe = Stripe::make(env('STRIPE_APP_KEY'));
		 if($plan->stripeplanid!=''){
		  $stripe->plans()->delete($plan->stripeplanid);
		 }
		
	    $plan->delete();
        return redirect('plan')->with('ok', trans('back/plans.destroyed'));
    }
}
