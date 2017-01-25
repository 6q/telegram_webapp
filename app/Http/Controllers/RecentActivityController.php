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

class RecentActivityController extends Controller
{
     public function __construct() {
		parent::login_check();
        parent::getTotalbot_chanel();
        
    }
    
    
    public function index(){
        $user_id = Auth::user()->id;
        $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
		
		
		$Form_action = 'front_user';
        $search = '';
        if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
        }
		
		$botId = '';
		if(!empty($total_bots)){
			foreach($total_bots as $k1 => $v1){
				$botId[] = 	$v1->id;
			}
		}
				
		if(!empty($search) && !empty($botId)){
			$rec_msg = DB::table('bot_messages')
						->where('text','LIKE','%'.$search.'%')
						->whereIn('bot_id',$botId)
                        ->orderBy('id','desc')
                        ->get();
		}
		else if(!empty($botId)){
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
								->get();    
			}
		}
     
		
        return view('front.recent_activity.index',compact('rec_msg','total_bots','total_chanels','search','Form_action')); 
        
    }
   
    public function edit(FrontUser $user) {
       $country = DB::table('countries')->get();
		 $total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
       
       $front_user = Auth::user(); 
		
	   $Form_action = 'dashboard';
       $search = '';
       if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
       }	
       
       $user['id'] = $front_user->id;
       $user['first_name'] = $front_user->first_name; 
       $user['last_name'] = $front_user->last_name; 
       $user['email'] = $front_user->email; 
       $user['country_id'] = $front_user->country_id; 
       $user['zipcode'] = $front_user->zipcode; 
       $user['image'] = $front_user->image; 
       $user['mobile'] = $front_user->mobile; 
        
       return view('front.front_user.edit', compact('user','country','total_bots','total_chanels','Form_action','search')); 
    }
    
    public function update(FrontUserCreateRequest $request){
      // echo '<pre>';print_r($request->all());die;
        
       if($request->get('id') !=''){
		  $user = FrontUser::find($request->get('id'));
          $user->first_name = $request->get('first_name');
          $user->last_name = $request->get('last_name');
          $user->country_id = $request->get('country');
          $user->zipcode = $request->get('zipcode');
          $user->mobile = $request->get('mobile');
           
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

               $user->image = $img_name_s;
            }
          } 
          
          if($user->save()){
            return redirect('dashboard')->with('ok', trans('front/fornt_user.created'));
          }
           else{
            return redirect('dashboard')->with('ok', trans('front/fornt_user.error'));
           }
		}
    }
    
    
}
