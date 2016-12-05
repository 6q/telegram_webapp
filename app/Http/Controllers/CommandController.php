<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

use App\Models\Command;
use App\Models\Autoresponse;
use App\Models\ContactForm;
use App\Models\ContactFormQuestion;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\Chanel;

use Illuminate\Support\Facades\Validator;

class CommandController extends Controller
{
	public function __construct() {
		parent::login_check();
        parent::getTotalbot_chanel();
        
    }
	
    public function create($bot_id){
		$total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
		
        $botId = $bot_id;
        $userId = Auth::user()->id;
		
		$Form_action = 'command/create/'.$bot_id;
       	$search = '';
       	if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
       	}
        
        $conditions = ['user_id' => $userId,'types' => 'bot','type_id' => $botId];
        $totalAutoresponses = DB::table('autoresponses')->where($conditions)->get();
        $totalContact_forms = DB::table('contact_forms')->where($conditions)->get();
        $totalGallery = DB::table('galleries')->where($conditions)->get();
        
        $subscription = DB::table('user_subscriptions')->where($conditions)->get();
        
        $planId = (isset($subscription[0]->plan_id) && !empty($subscription[0]->plan_id)?$subscription[0]->plan_id:'');
        
        if(!empty($planId))
        {
            $p_conditions = ['id' => $planId];
            $plan = DB::table('plans')->where($p_conditions)->get();
            
            return view('front.command.create',compact('botId','plan','totalAutoresponses','totalContact_forms','totalGallery','total_bots','total_chanels','Form_action','search'));
        }
        else{
            return redirect('dashboard')->with('ok', trans('front/command.error'));
        }
    }
    
    public function store(Request $request){
        //echo '<pre>';print_r($request->all());die;

        $userId = Auth::user()->id;
		$botId = $request->get('bot_id');
		
        if(!empty($request->get('autoresponse')) && $request->get('autoresponse') == 1)
        {
			$rules = array(
				'submenu_heading_text' => 'required|unique:autoresponses',
				'autoresponse_msg' => 'required:autoresponses'
			);

			$v = Validator::make($request->all(), $rules);

			if( $v->passes() ) 
			{
				$autoresponse = new Autoresponse;    
				$autoresponse->types = 'bot';
				$autoresponse->type_id = $request->get('bot_id');
				$autoresponse->user_id = $userId;
				$autoresponse->submenu_heading_text = $request->get('submenu_heading_text');
				
				$autoresponse->autoresponse_msg = '';
				$img_name_s = '';
				if(!empty($request->get('autoresponse_msg'))){
					$autoresponse->autoresponse_msg = $request->get('autoresponse_msg');
				}
				
				if($request->hasFile('image')){
					$error_img = $_FILES["image"]["error"];
					$img_name = $_FILES["image"]["name"];
	
					if($error_img == '0' && $img_name != '' )
					{
					   $img_path = $_FILES["image"]["tmp_name"];
					   $img_name_s = time()."_".$img_name;
					   $upload_img = public_path().'/uploads/'.$img_name_s;
	
					   move_uploaded_file($img_path,$upload_img);
					}
				}
				
				$autoresponse->image = $img_name_s;
				$autoresponse->save();
				
				//return redirect('front_user')->with('ok', trans('front/command.created'));
				return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.created'));
			} else { 
				$messages = $v->messages();
				return redirect('command/create/'.$botId)->withErrors($v);
			}
        }
        
        if(!empty($request->get('contact_form')) && $request->get('contact_form') == 1)
        {
			$rules = array(
				'submenu_heading_text' => 'required|unique:contact_forms',
			);

			$v = Validator::make($request->all(), $rules);

			if($v->passes())
			{
				$contact_form = new ContactForm;
            
				$contact_form->types = 'bot';
				$contact_form->email = $request->get('email');
				$contact_form->type_id = $request->get('bot_id');
				$contact_form->submenu_heading_text = $request->get('submenu_heading_text');
				$contact_form->user_id = $userId;
				$contact_form->headline = $request->get('headline');
				
				$contact_form->save();
				
				$contact_form_id = $contact_form->id;
				
				if(!empty($contact_form_id) && count($request->get('ques_heading'))>0){
					$chk = 0;
					foreach($request->get('ques_heading') as $k1 => $v1){
						
						$contact_form_ques = new ContactFormQuestion;
						$contact_form_ques->contact_form_id = $contact_form_id;
						$contact_form_ques->ques_heading = $v1;
						$contact_form_ques->response_type = $request->get('type_response')[$k1];
						
						$contact_form_ques->save();
						$chk = 1;
					}
					
					if($chk == 1){
						//return redirect('front_user')->with('ok', trans('front/command.created'));
						return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.created'));
					}
				}
				
				//return redirect('front_user')->with('ok', trans('front/command.created'));
				return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.created'));	
			}else { 
				$messages = $v->messages();
				return redirect('command/create/'.$botId)->withErrors($v);
			}
        }
        
        if(!empty($request->get('gallery_form')) && $request->get('gallery_form') == 1)
        {
            //echo '<pre>';print_r($request->all());die;
            $rules = array(
				'gallery_submenu_heading_text' => 'required|unique:galleries',
			);

			$v = Validator::make($request->all(), $rules);
			
			if($v->passes())
			{
				$gallery = new Gallery; 
				$gallery->types = 'bot';
				$gallery->type_id = $request->get('bot_id');
				$gallery->user_id = $userId;
				$gallery->gallery_submenu_heading_text = $request->get('gallery_submenu_heading_text');
				$gallery->introduction_headline = $request->get('introduction_headline');
				$gallery->created_at = date('Y-m-d h:i:s');
				$gallery->updated_at = date('Y-m-d h:i:s');
				
				if($gallery->save()){
					$galleryId = $gallery->id;
					
					if(!empty($request->get('title'))){
						foreach($request->get('title') as $k1 => $v1){
							$data = explode('_',$k1);
							
							$gallery_img = new GalleryImage;
							$gallery_img->gallery_id = $galleryId;
							$gallery_img->title = $v1;
							$gallery_img->image = $data[0];
							$gallery_img->sort_order = $data[1];
							$gallery_img->created_at = date('Y-m-d h:i:s');
							$gallery_img->updated_at = date('Y-m-d h:i:s');
							
							$gallery_img->save();
						}
					}
				}
				
				//return redirect('front_user')->with('ok', trans('front/command.created'));
				return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.created'));
			}
			else{
				$messages = $v->messages();
				return redirect('command/create/'.$botId)->withErrors($v);
			}
        }
        
        
        if(!empty($request->get('chanel')) && $request->get('chanel') == 1)
        {
			$rules = array(
				'chanel_submenu_heading_text' => 'required|unique:chanels',
			);

			$v = Validator::make($request->all(), $rules);

			if($v->passes())
			{
				//echo '<pre>';print_r($request->all());die;
				$chanel = new Chanel; 
				
				$chanel->types = 'bot';
				$chanel->type_id = $request->get('bot_id');
				$chanel->user_id = $userId;
				$chanel->chanel_submenu_heading_text = $request->get('chanel_submenu_heading_text');
				
				$chanel->chanel_msg = '';
				$img_name_s = '';
				if(!empty($request->get('chanel_msg'))){
					$chanel->chanel_msg = $request->get('chanel_msg');
				}
				
				if($request->hasFile('chanel_image')){
						$error_img = $_FILES["chanel_image"]["error"];
						$img_name = $_FILES["chanel_image"]["name"];
	
						if($error_img == '0' && $img_name != '' )
						{
						   $img_path = $_FILES["chanel_image"]["tmp_name"];
						   $img_name_s = time()."_".$img_name;
						   $upload_img = public_path().'/uploads/'.$img_name_s;
	
						   move_uploaded_file($img_path,$upload_img);
						}
					}
								
				$chanel->image = $img_name_s;
				$chanel->created_at = date('Y-m-d h:i:s');
				$chanel->updated_at = date('Y-m-d h:i:s');
				$chanel->save();
				
			   // return redirect('front_user')->with('ok', trans('front/command.created'));
			   return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.created'));
			}
			else{
				$messages = $v->messages();
				return redirect('command/create/'.$botId)->withErrors($v);	
			}
        }
    }
    
    
    public function imgupload(Request $request){
      //  echo '<pre>';print_r($request->all());die;
        
        if($request->hasFile('myfile')){
            $error_img = $_FILES["myfile"]["error"];
            $img_name = $_FILES["myfile"]["name"];

            if($error_img == '0' && $img_name != '' )
            {
               $img_path = $_FILES["myfile"]["tmp_name"];
               $img_name_s = time()."-".$img_name;
               $upload_img = public_path().'/uploads/'.$img_name_s;

               if(move_uploaded_file($img_path,$upload_img)){
                echo json_encode($img_name_s);
               }
            }
        }
    }
	
	
	public function autoresponse_edit($id = NULL){
		$total_bots = $this->botsTOTAL;
        $total_chanels = $this->chanelTOTAL;
		
        $userId = Auth::user()->id;
		
		$Form_action = 'command/autoresponse_edit/'.$id;
       	$search = '';
       	if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
            $search = $_REQUEST['search'];
       	}
		
		if(!empty($id)){
			$autoresponses = DB::table('autoresponses')
								->where('id','=',$id)
								->get();

			return view('front.command.autoresponse_edit',compact('total_bots','total_chanels','Form_action','search','autoresponses'));
		}
		else{
			return redirect('dashboard')->with('ok', trans('front/dashboard.error'));
		}
	}
	
	
	public function autoresponse_update(Request $request){
		if(!empty($request->get('id'))){
			$id = $request->get('id');
			$bot_id = $request->get('bot_id');
			
			$rules = array(
				'submenu_heading_text' => 'required|unique:autoresponses,submenu_heading_text,'.$id,
				'autoresponse_msg' => 'required:autoresponses'
			);

			$v = Validator::make($request->all(), $rules);

			if( $v->passes() ) 
			{
				$autoresponse = Autoresponse::find($request->get('id'));
				$autoresponse->id = $request->get('id');
				$autoresponse->submenu_heading_text = $request->get('submenu_heading_text');
				$autoresponse->autoresponse_msg = '';
	
				
				if(!empty($request->get('autoresponse_msg'))){
					$autoresponse->autoresponse_msg = $request->get('autoresponse_msg');
				}
				
				$img_name_s = $request->get('old_img');
				if($request->hasFile('image')){
					$error_img = $_FILES["image"]["error"];
					$img_name = $_FILES["image"]["name"];
	
					if($error_img == '0' && $img_name != '' )
					{
					   $img_path = $_FILES["image"]["tmp_name"];
					   $img_name_s = time()."_".$img_name;
					   $upload_img = public_path().'/uploads/'.$img_name_s;
	
					   move_uploaded_file($img_path,$upload_img);
					}
				}
	
				$autoresponse->image = $img_name_s;
				$autoresponse->save();
	
				//return redirect('bot/detail/'.$bot_id)->with('ok', trans('front/command.updated'));
				return redirect('bot/detail/'.$bot_id)->with('ok', trans('front/command.created'));
			}
			else{
				$messages = $v->messages();
				return redirect('command/autoresponse_edit/'.$id)->withErrors($v);
			}
		}
	}
	
	public function autoresponse_delete($botId = NULL,$autoId = NULL){
		if(!empty($botId) && !empty($autoId)){
			DB::table('autoresponses')->where('id', '=', $autoId)->delete();
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.deleted'));
		}
		else{
			//return redirect('front_user')->with('ok', trans('front/command.deleted'));
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.created'));
		}
	}
	
	
	public function chanel_edit($id = NULL){
		if(!empty($id)){
			$total_bots = $this->botsTOTAL;
			$total_chanels = $this->chanelTOTAL;

			$userId = Auth::user()->id;

			$Form_action = 'command/chanel_edit/'.$id;
			$search = '';
			if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
				$search = $_REQUEST['search'];
			}
			
			$chanel = DB::table('chanels')
								->where('id','=',$id)
								->get();
			return view('front.command.chanel_edit',compact('total_bots','total_chanels','Form_action','search','chanel'));
		}
		else{
			return redirect('dashboard')->with('ok', trans('front/dashboard.error'));
		}
	}
	
	public function chanel_update(Request $request){
		if(!empty($request->get('id'))){
			$bot_id = $request->get('bot_id');
			$id = $request->get('id');
			$rules = array(
				'chanel_submenu_heading_text' => 'required|unique:chanels,chanel_submenu_heading_text,'.$id,
			);

			$v = Validator::make($request->all(), $rules);

			if($v->passes())
			{
				$chanel = Chanel::find($request->get('id'));
				$chanel->id = $request->get('id');
				$chanel->chanel_submenu_heading_text = $request->get('chanel_submenu_heading_text');
				$chanel->chanel_msg = '';
	
				
				if(!empty($request->get('chanel_msg'))){
					$chanel->chanel_msg = $request->get('chanel_msg');
				}
				
				$img_name_s = $request->get('old_image');
				if($request->hasFile('image')){
					$error_img = $_FILES["image"]["error"];
					$img_name = $_FILES["image"]["name"];
	
					if($error_img == '0' && $img_name != '' )
					{
					   $img_path = $_FILES["image"]["tmp_name"];
					   $img_name_s = time()."_".$img_name;
					   $upload_img = public_path().'/uploads/'.$img_name_s;
	
					   move_uploaded_file($img_path,$upload_img);
					}
				}
	
				$chanel->image = $img_name_s;
				$chanel->save();
	
				return redirect('bot/detail/'.$bot_id)->with('ok', trans('front/command.updated'));
			}
			else{
				$messages = $v->messages();
				return redirect('command/chanel_edit/'.$id)->withErrors($v);
			}
		}
	}
	
	
	public function chanel_delete($botId = NULL,$channel_id = NULL){
		if(!empty($botId) && !empty($channel_id)){
			DB::table('chanels')->where('id', '=', $channel_id)->delete();
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.deleted'));
		}
		else{
			//return redirect('front_user')->with('ok', trans('front/command.deleted'));
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.updated'));
		}
	}
	
	
	public function contactform_edit($id){
		if(!empty($id)){
			$total_bots = $this->botsTOTAL;
			$total_chanels = $this->chanelTOTAL;

			$userId = Auth::user()->id;

			$Form_action = 'command/contactform_edit/'.$id;
			$search = '';
			if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
				$search = $_REQUEST['search'];
			}
			
			$contact_forms = DB::table('contact_forms')
								->where('id','=',$id)
								->get();
			
			$contact_forms_ques = DB::table('contact_form_questions')
								->where('contact_form_id','=',$id)
								->get();
			
			return view('front.command.contactform_edit',compact('total_bots','total_chanels','Form_action','search','contact_forms','contact_forms_ques'));
		}
		else{
			return redirect('dashboard')->with('ok', trans('front/dashboard.error'));
		}
	}
	
	
	public function contactform_update(Request $request){
		if(!empty($request->get('id'))){
			$id = $request->get('id');
			$bot_id = $request->get('bot_id');
			
			$rules = array(
				'submenu_heading_text' => 'required|unique:contact_forms,submenu_heading_text,'.$id,
			);

			$v = Validator::make($request->all(), $rules);

			if($v->passes())
			{
				//$to_email = $request->get('email');
				$contactFormEmail = DB::table('site_settings')
												->where('name','=','contact_form_email')
												->get();
						
				$to_email = $contactFormEmail[0]->value;//$request->get('email');
				
				
				$contact_form = ContactForm::find($request->get('id'));
				$contact_form->id = $request->get('id');
				$contact_form->email = $request->get('email');
				$contact_form->submenu_heading_text = $request->get('contact_submenu_heading_text');
				$contact_form->headline = $request->get('headline');
	
				$contact_form->save();
				
				$contact_form_id = $request->get('id');
				
				if(!empty($contact_form_id) && count($request->get('ques_heading'))>0){
					DB::table('contact_form_questions')->where('contact_form_id', '=', $contact_form_id)->delete();
					foreach($request->get('ques_heading') as $k1 => $v1){
						$contact_form_ques = new ContactFormQuestion;
						$contact_form_ques->contact_form_id = $contact_form_id;
						$contact_form_ques->ques_heading = $v1;
						$contact_form_ques->response_type = $request->get('type_response')[$k1];
						$contact_form_ques->save();
					}
				}
				
				
				return redirect('bot/detail/'.$bot_id)->with('ok', trans('front/command.updated'));
			}
			else{
				$messages = $v->messages();
				return redirect('command/contactform_edit/'.$id)->withErrors($v);
			}
		}
	}
	
	public function contactform_delete($botId = NULL,$contactform_id = NULL){
		if(!empty($botId) && !empty($contactform_id)){
			DB::table('contact_forms')->where('id', '=', $contactform_id)->delete();
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.deleted'));
		}
		else{
			//return redirect('front_user')->with('ok', trans('front/command.deleted'));
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.updated'));
		}
	}
	
	
	public function gallery_edit($id = NULL){
		if(!empty($id)){
			$total_bots = $this->botsTOTAL;
			$total_chanels = $this->chanelTOTAL;

			$userId = Auth::user()->id;

			$Form_action = 'command/gallery_edit/'.$id;
			$search = '';
			if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
				$search = $_REQUEST['search'];
			}
			
			$gallery = DB::table('galleries')
								->where('id','=',$id)
								->get();
			
			
			$gallery_images = DB::table('gallery_images')
								->where('gallery_id','=',$id)
								->get();
			
			$conditions = ['user_id' => $userId,'types' => 'bot','type_id' => $gallery[0]->type_id];
			
			$subscription = DB::table('user_subscriptions')->where($conditions)->get();

			$planId = (isset($subscription[0]->plan_id) && !empty($subscription[0]->plan_id)?$subscription[0]->plan_id:'');
			
			
			$plan = '';
			if(!empty($planId))
			{
				$p_conditions = ['id' => $planId];
				$plan = DB::table('plans')->where($p_conditions)->get();
			}
			
			return view('front.command.gallery_edit',compact('total_bots','total_chanels','Form_action','search','gallery','gallery_images','plan'));
		}
		else{
			return redirect('dashboard')->with('ok', trans('front/dashboard.error'));
		}
	}
	
	
	public function gallery_update(Request $request){
		if(!empty($request->get('id'))){
			$bot_id = $request->get('bot_id');
			$id = $request->get('id');
			
			 $rules = array(
					'gallery_submenu_heading_text' => 'required|unique:galleries,gallery_submenu_heading_text,'.$id
				);
	
				$v = Validator::make($request->all(), $rules);
				
				if($v->passes())
				{
					$gallery = Gallery::find($request->get('id'));
					$gallery->id = $request->get('id');
					$gallery->gallery_submenu_heading_text = $request->get('gallery_submenu_heading_text');
					$gallery->introduction_headline = $request->get('introduction_headline');
					$gallery->updated_at = date('Y-m-d h:i:s');
		
					if($gallery->save()){
						$galleryId = $gallery->id;
		
						if(!empty($request->get('title'))){
							DB::table('gallery_images')->where('gallery_id', '=', $galleryId)->delete();
							foreach($request->get('title') as $k1 => $v1){
								$data = explode('_',$k1);
		
								$gallery_img = new GalleryImage;
								$gallery_img->gallery_id = $galleryId;
								$gallery_img->title = $v1;
								$gallery_img->image = $data[0];
								$gallery_img->sort_order = $data[1];
								$gallery_img->created_at = date('Y-m-d h:i:s');
								$gallery_img->updated_at = date('Y-m-d h:i:s');
		
								$gallery_img->save();
							}
						}
					}	
				}
				
				return redirect('bot/detail/'.$bot_id)->with('ok', trans('front/command.updated'));
			}
			else{
				$messages = $v->messages();
				return redirect('command/gallery_edit/'.$id)->withErrors($v);
			}
			
	}
	
	
	public function gallery_delete($botId = NULL,$gallery_id = NULL){
		if(!empty($botId) && !empty($gallery_id)){
			DB::table('galleries')->where('id', '=', $gallery_id)->delete();
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.deleted'));
		}
		else{
			//return redirect('front_user')->with('ok', trans('front/command.deleted'));
			return redirect('bot/detail/'.$botId)->with('ok', trans('front/command.updated'));
		}
	}
	
	
}
