<?php
use Telegram\Bot\Api;
Route::group(['middleware' => ['web']], function () {

    // Home

    Route::get('/', 'Auth\AuthController@getLogin');
    Route::post('/', 'Auth\AuthController@postLogin');

    /* Route::get('/', [
      'uses' => 'HomeController@index',
      'as' => 'home'
      ]); */
    Route::get('language/{lang}', 'HomeController@language')->where('lang', '[A-Za-z_-]+');


    // Admin
    Route::get('admin', [
        'uses' => 'AdminController@admin',
        'as' => 'admin',
        'middleware' => 'admin'
    ]);
    Route::get('dashboard', 'DashboardController@index');
    Route::post('dashboard/getcharts', 'DashboardController@getcharts');
	Route::post('my_channel/getchannelcharts', 'MyChannelController@getchannelcharts');
    Route::post('dashboard/sendmessage', 'DashboardController@sendmessage');
    Route::post('dashboard/sendbotmessage', 'DashboardController@sendbotmessage');


    // Blog
    // Contact
    Route::resource('contact', 'ContactController', [
        'except' => ['show', 'edit']
    ]);


    // User
    //Route::get('auth/login', 'Auth\AuthController@getLogin');
    //Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('user/sort/{role}', 'UserController@indexSort');

    Route::get('user/roles', 'UserController@getRoles');
    Route::post('user/roles', 'UserController@postRoles');

    Route::put('userseen/{user}', 'UserController@updateSeen');

    Route::post('front_user/update', 'FrontUserController@update');
    Route::get('front_user/change_password/{user_id}', 'FrontUserController@change_password');
    Route::post('front_user/change_password', 'FrontUserController@change_password');
    Route::resource('front_user', 'FrontUserController');
    
    Route::resource('messages', 'MessageController');
    Route::resource('recent_activity', 'RecentActivityController');

    Route::resource('user', 'UserController');

    Route::resource('plan', 'PlanController');
    
    Route::get('pages/detail/{pageid?}', 'PageController@detail');
    Route::resource('page', 'PageController');
    Route::resource('emailtemplate', 'EmailtemplateController');
	
	Route::resource('setting','SettingController');

    Route::get('bot/userbot/{user_id?}', 'BotController@userbot');
	Route::get('my_channel/userchannel/{user_id?}', 'MyChannelController@userchannel');
    Route::get('bot/bot_detail/{bot_id?}', 'BotController@admin_bot_detail');
    Route::get('bot/contactform_ques/{contact_form_id?}', 'BotController@contactform_ques');
    Route::get('bot/gallery_img/{gallery_id?}', 'BotController@gallery_img');
    Route::get('bot/detail/{botid?}', 'BotController@detail');
    Route::post('bot/setweb_hook', 'BotController@setweb_hook');
    Route::get('bot/update_bot/{bot_id?}', 'BotController@edit_bot');
    Route::post('bot/update_bot/{bot_id?}', 'BotController@update_bot');
	Route::get('bot/upgradeplan/{bot_id?}', 'BotController@upgradeplan');
	Route::post('bot/upgradeplan/{bot_id?}', 'BotController@upgrade_plan');
	Route::get('bot/download_user/{bot_id?}/{from?}/{to?}', 'BotController@download_user');
	Route::get('bot/download_log/{bot_id?}/{from?}/{to?}', 'BotController@download_log');
	
	Route::get('bot/pdf_download/{bot_id?}/{from?}/{to?}', 'BotController@pdf_download_user');
	Route::get('bot/log_pdf_download/{bot_id?}/{from?}/{to?}', 'BotController@log_pdf_download');
	
	Route::get('delete/log', 'BotController@delete_log');
	
	Route::get('bot/bot_command/{bot_id?}', 'BotController@add_bot_command');
	Route::post('bot/bot_command/{bot_id?}', 'BotController@addbot_command');
	
	Route::post('bot/get_autoresponse/{bot_id?}', 'BotController@paginate_autoresponse');
	Route::post('bot/get_contact_form/{bot_id?}', 'BotController@paginate_contact_form');
	Route::post('bot/get_bot_message/{bot_id?}', 'BotController@paginate_bot_message');
	Route::post('bot/get_gallery/{bot_id?}', 'BotController@paginate_gallery');
	Route::post('bot/get_channel/{bot_id?}', 'BotController@paginate_channel');
	Route::post('bot/get_bot_active_user/{bot_id?}', 'BotController@paginate_active_user');
	Route::post('bot/get_bot_command/{bot_id?}', 'BotController@paginate_bot_command');
	
	Route::get('bot/bot_command_delete/{bot_id?}/{command_id?}', 'BotController@bot_command_delete');
	Route::get('bot/bot_command_edit/{bot_id?}', 'BotController@bot_command_edit');
	Route::post('bot/bot_command_edit/{bot_id?}', 'BotController@bot_command_update');
	
	Route::post('my_channel/get_channel_msg/{channelId?}', 'MyChannelController@paginate_channel_msg');
	Route::post('front_user/get_bots/{channelId?}', 'FrontUserController@paginate_bots');
	Route::post('front_user/get_chanel/{channelId?}', 'FrontUserController@paginate_get_chanel');
	Route::post('front_user/get_bot_transaction/{channelId?}', 'FrontUserController@paginate_bot_transaction');
	
	Route::post('messages/get_messages/{botId?}', 'MessageController@paginate_bot_message');
	
	Route::get('bot/bot_delete/{bot_id?}', 'BotController@bot_delete');
	Route::get('bot/bot_subscription_cancel/{bot_id?}', 'BotController@bot_subscription_cancel');
   
    Route::get('my_channel/channel_delete/{channel_id?}', 'MyChannelController@channel_delete');
	Route::get('my_channel/channel_subscription_cancel/{channel_id?}', 'MyChannelController@channel_subscription_cancel');
	
	
    Route::resource('bot', 'BotController');

    Route::post('command/create/{bot_id?}', 'CommandController@create');
    Route::get('command/create/{bot_id?}', 'CommandController@create');
    
    Route::post('command/autoresponse_edit/{auto_id?}', 'CommandController@autoresponse_update');
    Route::get('command/autoresponse_edit/{auto_id?}', 'CommandController@autoresponse_edit');
	Route::get('command/autoresponse_delete/{bot_id?}/{auto_id?}', 'CommandController@autoresponse_delete');
    
    Route::post('command/chanel_edit/{chanel_id?}', 'CommandController@chanel_update');
    Route::get('command/chanel_edit/{chanel_id?}', 'CommandController@chanel_edit');
	Route::get('command/chanel_delete/{bot_id?}/{chanel_id?}', 'CommandController@chanel_delete');
	
    
    Route::post('command/contactform_edit/{contactform_id?}', 'CommandController@contactform_update');
    Route::get('command/contactform_edit/{contactform_id?}', 'CommandController@contactform_edit');
	Route::get('command/contactform_delete/{bot_id?}/{contactform_id?}', 'CommandController@contactform_delete');
    
    Route::post('command/gallery_edit/{gallery_id?}', 'CommandController@gallery_update');
    Route::get('command/gallery_edit/{gallery_id?}', 'CommandController@gallery_edit');
	Route::get('command/gallery_delete/{bot_id?}/{gallery_id?}', 'CommandController@gallery_delete');
    
    Route::resource('command/upload', 'CommandController@imgupload');
    Route::resource('command', 'CommandController');

    // Routes for mychannel Controller

    Route::get('my_channel/detail/{cahnelId?}', 'MyChannelController@detail');
	Route::get('my_channel/mychannel_detail/{cahnelId?}', 'MyChannelController@mychannel_detail');
    Route::get('my_channel/update_channel/{cahnelId?}', 'MyChannelController@edit_channel');
    Route::post('my_channel/update_channel/{cahnelId?}', 'MyChannelController@update_channel');
    Route::resource('my_channel', 'MyChannelController');
    // End


    Route::get('/bot/get_state/{country_id?}', function(Request $request, $country_id) {
        $state = DB::table('countries')->get();

        $states = DB::table('states')
                ->where('country_id', '=', $country_id)
                ->get();

        $stateHtml = '';
        $stateHtml .= '<select id="state" name="state" class="form-control">';
        $stateHtml .= '<option value="">Select State</option>';
        if (!empty($states)) {
            foreach ($states as $k1 => $v1) {
                $stateHtml .= '<option value="' . $v1->id . '">' . $v1->name . '</option>';
            }
        }
        $stateHtml .= '</select>';

        echo $stateHtml;
        die;
    });

    // Authentication routes...

    Route::get('auth/logout', 'Auth\AuthController@getLogout');
    Route::get('auth/confirm/{token}', 'Auth\AuthController@getConfirm');

    // Resend routes...
    Route::get('auth/resend', 'Auth\AuthController@getResend');

    // Registration routes...
    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::post('auth/register', 'Auth\AuthController@postRegister');

    // Password reset link request routes...
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');

    // Password reset routes...
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');
});

Route::get('/user_images/{size}/{name}', function($size = NULL, $name = NULL) {

    if (!is_null($size) && !is_null($name)) {
        $size = explode('x', $size);
        $cache_image = Image::cache(function($image) use($size, $name) {
                    return $image->make(url('/user_images/' . $name))->resize($size[0], $size[1]);
                }, 10); // cache for 10 minutes


        return Response::make($cache_image, 200, ['Content-Type' => 'image']);
    } else {
        abort(404);
    }
});


Route::post('/{bottoken}/webhook', function ($token) {

   /*$url='https://api.telegram.org/bot'.$token.'/getWebhookInfo';
   $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HTTPHEADER     => array("Accept-Language: en-US;q=0.6,en;q=0.4"),
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );


   // $header['content'] = $content;
     $rs= $content;

    //$rs=@file_get_contents('https://api.telegram.org/bot'.$token.'/getWebhookInfo');
	file_put_contents(public_path().'/'.$token.'webhook_info.txt',$rs);
	file_put_contents(public_path().'/updates_error.txt',$token);*/
	
   // $update = Telegram::commandsHandler(true);
   
   $update = file_get_contents('php://input');
    
    $data = json_decode($update,true);
    $messageText = (isset($data['message']['text']) && !empty($data['message']['text']))?$data['message']['text']:'';
	$messageTextImg = (isset($data['message']['document']['file_name']) && !empty($data['message']['document']['file_name']))?$data['message']['document']['file_name']:'';
	$messageTextImgSize = (isset($data['message']['document']['file_size']) && !empty($data['message']['document']['file_size']))?$data['message']['document']['file_size']:'';
	$messageTextPhoto = (isset($data['message']['photo'][3]['file_id']) && !empty($data['message']['photo'][3]['file_id']))?$data['message']['photo'][3]['file_id']:'';
	$messageTextPhotoSize = (isset($data['message']['photo'][3]['file_size']) && !empty($data['message']['photo'][3]['file_size']))?$data['message']['photo'][3]['file_size']:'';
	
	//file_put_contents(public_path().'/result3.txt',$update);
	//file_put_contents(public_path().'/result.txt',$update);
    file_put_contents(public_path().'/updates.txt',$update.'>>'.$token);
    
    $telegram = new Api($token);
	
	
    $response = $telegram->getMe();
	
	//$rs = $telegram->getWebhookInfo();
	
    
	$botId = $response->getId();
    $chatId = $data['message']['chat']['id'];
    $message_id = $data['message']['message_id'];
    
    $bot_data = DB::table('bots')->where('bot_token', 'LIKE', $token)->where('is_subscribe','=','0')->get();
    $dbBotId = (isset($bot_data[0]->id) && $bot_data[0]->id!='')?$bot_data[0]->id:'';

    /*
    DB::table('tmp_bots')->insert(
        ['data_value' => serialize($bot_data)]
    );
    */
    
    
    
    
    if(!empty($dbBotId))
	{
		//file_put_contents(public_path().'/result.txt',serialize($data));
		if(empty($messageText) && !empty($messageTextImg) && !empty($messageTextImgSize)){
			$file_id = $data['message']['document']['file_id'];
			$file_response = $telegram->getFile(['file_id' => $file_id,'file_size' => $messageTextImgSize]);
			$arr = json_decode(json_encode($file_response));
			$path = 'https://api.telegram.org/file/bot'.$token.'/'.$arr->file_path;
			$messageText = $path;
		}
		
		if(empty($messageText) && !empty($messageTextPhoto) && !empty($messageTextPhotoSize)){
			//file_put_contents(public_path().'/result.txt',json_encode($data['message']['photo'][0]['file_id']));		
			if(isset($data['message']['photo'][3]['file_id']) && !empty($data['message']['photo'][3]['file_id'])){
				$file_id = $data['message']['photo'][3]['file_id'];
				$file_response = $telegram->getFile(['file_id' => $file_id,'file_size' => $messageTextPhotoSize]);
				$arr = json_decode(json_encode($file_response));
				$path = 'https://api.telegram.org/file/bot'.$token.'/'.$arr->file_path;
				$messageText = $path;
			}
		}
		
		
		/* Add bot user */
		$from_id = $data['message']['from']['id'];
		$first_name = isset($data['message']['from']['first_name'])?$data['message']['from']['first_name']:'';
		$last_name = isset($data['message']['from']['last_name'])?$data['message']['from']['last_name']:'';
		
		$bot_user = DB::table('bot_users')
						->where('bot_id', '=', $dbBotId)
						->where('first_name', 'LIKE', $first_name)
						->get();
		
		$bot_user_id = '';
		if(isset($bot_user[0]->id) && !empty($bot_user[0]->id)){
			$bot_user_id = $bot_user[0]->id;
		}
		else{
			$bot_user_id = DB::table('bot_users')->insertGetId(
					['bot_id' => $dbBotId, 'first_name' => $first_name, 'last_name' => $last_name, 'username' => '', 'fromid' => $from_id, 'created_at' => date('Y-m-d h:i:s'), 'modified_at' => date('Y-m-d h:i:s')]
				);
			
			//file_put_contents(public_path().'/result.txt',$bot_user_id);
		}
		/*******************************************************/
		
		
		
		/* Bot Message */
		if(!empty($bot_user_id)){
			
			//$date = $data['message']['date'];
			$bot_msg_date = date('Y-m-d h:i:s');
			
			$bot_messages_id = DB::table('bot_messages')->insertGetId(
					['bot_id' => $dbBotId,'bot_user_id' => $bot_user_id,'date' => $bot_msg_date,'forward_from' => $from_id, 'forward_from_chat' => $chatId, 'forward_date' => $bot_msg_date, 'reply_to_chat' => '', 'reply_to_message' => '', 'text' => $messageText]
				);
		}    
		/*******************************************************/
		
		
		if($messageText != "\xE2\x97\x80"){
			$quesRowId = DB::table('tmp_ques')
							->orderBy('id', 'desc')
							->get();
			//file_put_contents(public_path().'/result.txt',serialize($quesRowId));
			
			if(isset($quesRowId[0]->id) && !empty($quesRowId[0]->id)){
				 DB::table('tmp_ques_ans')->where('tmp_ques_id', $quesRowId[0]->id)->update(['ans' => $messageText]);
			}
		}
		
		$bot_commands_title = array();
		$bot_commands = DB::table('bot_commands')->where('bot_id','=',$dbBotId)->get();
		if(!empty($bot_commands)){
			foreach($bot_commands as $key1 => $val1){
				$bot_commands_title[] = strtolower($val1->title);
			}
		}
        
        $autoresponse = '';
        if(isset($bot_data[0]->autoresponse) && !empty($bot_data[0]->autoresponse)){
            $autoresponse = $bot_data[0]->autoresponse;
        }
        
        $contact_form = '';
        if(isset($bot_data[0]->contact_form) && !empty($bot_data[0]->contact_form)){
            $contact_form = $bot_data[0]->contact_form;
        }
        
        $galleries = '';
        if(isset($bot_data[0]->galleries) && !empty($bot_data[0]->galleries)){
            $galleries = $bot_data[0]->galleries;
        }
        
        $channels = '';
        if(isset($bot_data[0]->channels) && !empty($bot_data[0]->channels)){
            $channels = $bot_data[0]->channels;
        }
        
        $array = array(
                '0' => $autoresponse,
                '1' => $contact_form,
                '2' => $galleries,
                '3' => $channels
        );
        
        $ke_arr = '';
        for($i = 0; $i<=3;$i++){
            if(isset($array[$i]) && !empty($array[$i])){
                $ke_arr[$i] = $array[$i];
            }
        }

        $ke_arr = array_chunk($ke_arr,2);
        $keyboard = $ke_arr;
		
		if($messageText == '/start'){
			$msg = (isset($bot_data[0]->start_message) && !empty($bot_data[0]->start_message))?$bot_data[0]->start_message:'';
			$msg .= chr(10).chr(10).'© Citymes.com';
		}
		else if(isset($bot_data[0]->comanda) && $messageText == $bot_data[0]->comanda)
		{
			$commands  = DB::table('bot_commands')->orderBy('title','ASC')->where('bot_id','=',$dbBotId)->get();
			//file_put_contents(public_path().'/result_command.txt',json_encode($commands));
			$msg = '';
			if(!empty($commands))
			{
				foreach($commands as $bck1 => $bcv1)
				{
					$msg .= $bcv1->title.chr(10);
				}
			} else $msg .= "\xE2\x9D\x8C".chr(10);
			//file_put_contents(public_path().'/result_command.txt',json_encode($msg));
		}
		else if(!empty($bot_commands_title) && in_array(strtolower(strtok($messageText, " ")),$bot_commands_title)){
			$bot_cmd_msg = DB::table('bot_commands')
							->where('bot_id','=',$dbBotId)
							->where('title','LIKE','%'.strtok($messageText, " ").'%')
							->get();
			if(!empty($bot_cmd_msg)){
				$msg = (isset($bot_cmd_msg[0]->command_description) && !empty($bot_cmd_msg[0]->command_description)?$bot_cmd_msg[0]->command_description:'');
				
				if(isset($bot_cmd_msg[0]->image) && !empty($bot_cmd_msg[0]->image)){
					$img_url = public_path().'/uploads/'.$bot_cmd_msg[0]->image;
	                $image_name = $bot_cmd_msg[0]->image;
				}

				if($bot_cmd_msg[0]->webservice_type<>0) {

					if ($bot_cmd_msg[0]->webservice_type == 1) {

						$url = $bot_cmd_msg[0]->webservice_url;

					} elseif ($bot_cmd_msg[0]->webservice_type == 2) {

						$arr = explode(' ', $messageText);
						if (array_key_exists(1,$arr)) {
							$url = $bot_cmd_msg[0]->webservice_url.$arr[1];

						} else {
							$url = "";
						}

					}
					if (filter_var($url, FILTER_VALIDATE_URL)) {
						$xml = simplexml_load_file($url);
						//echo $xml->asXML();
						if ($xml === false) {
							$msg .= "Failed loading XML: ";
							foreach(libxml_get_errors() as $error) {
								$msg .= chr(10). $error->message;
							}
						} else {
							//print_r($xml);
							$i = 0;
							foreach ($xml as $object)
							{
								//echo "<hr>";
								//print_r($object);
								if ($i == 1){
									$j = 0;
									foreach ($object as $resource) {
										if ($j == 0) {
											$msg .= chr(10)."____________________".chr(10);
										}

										$msg .= chr(10). "<b>".$resource["name"]."</b>: ".$resource;
										++$j;
									}
								}
								++$i;
							}
						}
					}

				}
			}
		}
		else{
			$msg = (isset($bot_data[0]->error_msg) && !empty($bot_data[0]->error_msg))?$bot_data[0]->error_msg:'Exigency to valid bot command.';
		}
		
        
       // file_put_contents(public_path().'/result.txt',serialize($keyboard));
        
        if($messageText != "\xE2\x97\x80"){
            DB::table('tmp_message')->insert(
                ['chat_id' => $chatId,'message_id' => $message_id,'message' => $messageText.'_'.$dbBotId]
            );
        }
        
        if(!empty($messageText) && $messageText == $autoresponse){
            
			$w_autoresponse = DB::table('bots')->where('id', '=', $dbBotId)->get();
			if(isset($w_autoresponse[0]->intro_autoresponses) && !empty($w_autoresponse[0]->intro_autoresponses)){
				$msg = $w_autoresponse[0]->intro_autoresponses;
			}
			else{
				$msg = "\xE2\x9E\xA1 ".$autoresponse;
			}
			
            $db_autoresponse = DB::table('autoresponses')->orderBy('submenu_heading_text','ASC')->where('type_id', '=', $dbBotId)->get();
            
            $arr = '';
            $Back = "\xE2\x97\x80";
            $i = 0;
            if(isset($db_autoresponse) && !empty($db_autoresponse)){
                foreach($db_autoresponse as $ak1 => $av1){
                    $arr[$i]['text'] = $av1->submenu_heading_text;
                    $arr[$i]['callback_data'] = $av1->id;
                    $i++;
                }
            }
            $arr[$i]['text'] = $Back;
            $arr[$i]['callback_data'] = '';

            $arr = array_chunk($arr,2);

            $keyboard = $arr;
            
            //file_put_contents(public_path().'/result.txt',json_encode($keyboard));  
            /*
            DB::table('tmp_bots')->insert(
                ['data_value' => json_encode($keyboard)]
            );
            */
          
            
        }
        else if(!empty($messageText) && $messageText == $contact_form){
            $db_contact_form = DB::table('contact_forms')->orderBy('submenu_heading_text','ASC')->where('type_id', '=', $dbBotId)->get();
			
			$w_contact_form = DB::table('bots')->where('id', '=', $dbBotId)->get();
			if(isset($w_contact_form[0]->intro_contact_form) && !empty($w_contact_form[0]->intro_contact_form)){
				$msg = $w_contact_form[0]->intro_contact_form;
			}
			else{
				$msg = "\xE2\x9E\xA1 ".$contact_form;
			}
            
            $arr = '';
            $Back = "\xE2\x97\x80";
            $i = 0;
            if(isset($db_contact_form) && !empty($db_contact_form)){
                foreach($db_contact_form as $ac1 => $cv1){
                    $arr[$i]['text'] = $cv1->submenu_heading_text;
                    $arr[$i]['callback_data'] = $cv1->id;
                    $i++;
                }
            }
            $arr[$i]['text'] = $Back;
            $arr[$i]['callback_data'] = '';

            $arr = array_chunk($arr,2);

            $keyboard = $arr;
            
            
        }
        else if(!empty($messageText) && $messageText == $galleries){
            $db_galleries = DB::table('galleries')->orderBy('gallery_submenu_heading_text','ASC')->where('type_id', '=', $dbBotId)->get();
			
			$w_galleries = DB::table('bots')->where('id', '=', $dbBotId)->get();
			if(isset($w_galleries[0]->intro_galleries) && !empty($w_galleries[0]->intro_galleries)){
				$msg = $w_galleries[0]->intro_galleries;
			}
			else{
				$msg = "\xE2\x9E\xA1 ".$galleries;
			}
            
            $arr = '';
            $Back = "\xE2\x97\x80";
            $i = 0;
            if(isset($db_galleries) && !empty($db_galleries)){
                foreach($db_galleries as $gk1 => $gv1){
                    $arr[$i]['text'] = $gv1->gallery_submenu_heading_text;
                    $arr[$i]['callback_data'] = $gv1->id;
                    $i++;
                }
            }
            $arr[$i]['text'] = $Back;
            $arr[$i]['callback_data'] = '';

            $arr = array_chunk($arr,2);

            $keyboard = $arr;
        }
        else if(!empty($messageText) && $messageText == $channels){
            $db_chanels = DB::table('chanels')->orderBy('chanel_submenu_heading_text','ASC')->where('type_id', '=', $dbBotId)->get();
			
			$w_chanels = DB::table('bots')->where('id', '=', $dbBotId)->get();
			if(isset($w_chanels[0]->intro_channels) && !empty($w_chanels[0]->intro_channels)){
				$msg = $w_chanels[0]->intro_channels;
			}
			else{
				$msg = "\xE2\x9E\xA1 ".$channels;
			}
            
            $arr = '';
            $Back = "\xE2\x97\x80";
            $i = 0;
            if(isset($db_chanels) && !empty($db_chanels)){
                foreach($db_chanels as $ck1 => $ch1){
                    $arr[$i]['text'] = $ch1->chanel_submenu_heading_text;
                    $arr[$i]['callback_data'] = $ch1->id;
                    $i++;
                }
            }
            $arr[$i]['text'] = $Back;
            $arr[$i]['callback_data'] = '';

            $arr = array_chunk($arr,2);

            $keyboard = $arr;
            
            file_put_contents(public_path().'/result.txt',json_encode($keyboard));  
            /*
            DB::table('tmp_bots')->insert(
                ['data_value' => json_encode($keyboard)]
            );
            */
          
            
        }
        else{
            if(!empty($messageText) && $messageText != "\xE2\x97\x80")
            {
                DB::table('tmp_bots')->insert(
                    ['data_value' => serialize($messageText)]
                );
                
                $tbl_autoresponse = DB::table('autoresponses')
                    ->where('type_id', '=', $dbBotId)
                    ->where('submenu_heading_text', 'LIKE', $messageText)
                    ->get();
            
            
                $tbl_contact_forms = DB::table('contact_forms')
                    ->where('type_id', '=', $dbBotId)
                    ->where('submenu_heading_text', 'LIKE', $messageText)
                    ->get();
                
                $tbl_galleries = DB::table('galleries')
                    ->where('type_id', '=', $dbBotId)
                    ->where('gallery_submenu_heading_text', 'LIKE', $messageText)
                    ->get();
                
                $tbl_chanels = DB::table('chanels')
                    ->where('type_id', '=', $dbBotId)
                    ->where('chanel_submenu_heading_text', 'LIKE', $messageText)
                    ->get();
            
                /*
                DB::table('tmp_bots')->insert(
                    ['data_value' => serialize($tbl_autoresponse)]
                );
                */
            
            
                if(isset($tbl_autoresponse[0]) && !empty($tbl_autoresponse[0])){
					DB::table('tmp_mytable')->truncate();
					
                    if(isset($tbl_autoresponse[0]->autoresponse_msg) && !empty($tbl_autoresponse[0]->autoresponse_msg)){
                        $msg = $tbl_autoresponse[0]->autoresponse_msg;
                    }
                    
					if(!empty($tbl_autoresponse[0]->image)){
                        //$msg = '';
                        $img_url = public_path().'/uploads/'.$tbl_autoresponse[0]->image;
                        $image_name = $tbl_autoresponse[0]->image;
                    }
                
                    /* Autoresponse Keyboard */
                    $arr = '';
                    $Back = "\xE2\x97\x80";
                    $i = 0;
                    $db_autoresponse = DB::table('autoresponses')->orderBy('submenu_heading_text','ASC')->where('type_id', '=', $dbBotId)->get();
                    if(isset($db_autoresponse) && !empty($db_autoresponse)){
                        foreach($db_autoresponse as $ak1 => $av1){
                            $arr[$i]['text'] = $av1->submenu_heading_text;
                            $arr[$i]['callback_data'] = $av1->id;
                            $i++;
                        }
                    }
                    $arr[$i]['text'] = $Back;
                    $arr[$i]['callback_data'] = '';

                    $arr = array_chunk($arr,2);

                    $keyboard = $arr;

                    /* Autoresponse Keyboard */
                }
                else if(isset($tbl_contact_forms[0]) && !empty($tbl_contact_forms[0])){
                    /* ContactForms */
					DB::table('tmp_mytable')->truncate();
					DB::table('tmp_mytable')->insert(
						['data_key' => $tbl_contact_forms[0]->id,'data_value' => $tbl_contact_forms[0]->headline]
					);
                    $db_contact_form = DB::table('contact_forms')->orderBy('submenu_heading_text','ASC')->where('type_id', '=', $dbBotId)->get();
            
                    $arr = '';
                    $Back = "\xE2\x97\x80";
                    $i = 0;
                    if(isset($db_contact_form) && !empty($db_contact_form)){
                        foreach($db_contact_form as $ac1 => $cv1){
                            $arr[$i]['text'] = $cv1->submenu_heading_text;
                            $arr[$i]['callback_data'] = $cv1->id;
                            $i++;
                        }
                    }
                    $arr[$i]['text'] = $Back;
                    $arr[$i]['callback_data'] = '';

                    $arr = array_chunk($arr,2);

                    $keyboard = $arr;
                    
                    $cfq_ID = $tbl_contact_forms[0]->id;	
                    $q_limit = 1;
                    $offset = 0;
                    
                    $cf_ques = DB::table('tmp_ques')
                        ->where('ques_id', '=', $cfq_ID)
                        ->orderBy('id', 'desc')
                        ->get();
					
					if(isset($cf_ques[0]->data_limit) && count($cf_ques[0]->data_limit) != 0){
                        $offset = $cf_ques[0]->data_limit+1;
                    }	
					
					$contact_form_questions = DB::table('contact_form_questions')
                        ->where('contact_form_id', '=', $cfq_ID)
                        ->limit($q_limit)
                        ->offset($offset)
                        ->get();
						
					$msg = '';
					
                    if(isset($contact_form_questions[0]->ques_heading) && !empty($contact_form_questions[0]->ques_heading)){
                        $msg = $contact_form_questions[0]->ques_heading;
						
                       	DB::table('tmp_ques')->insertGetId(
                            [
                                'ques_id' => $cfq_ID,
                                'data_limit' => $offset
                            ]
                        );
					
					//file_put_contents(public_path().'/result.txt',serialize($msg));	
						$lastID = DB::getPdo()->lastInsertId();
					
						DB::table('tmp_ques_ans')->insert(
                            [
								'tmp_ques_id' => $lastID,
                                'bot_id' => $dbBotId,
                                'ques' => $msg,
								'ans' => ''
                            ]
                        );
                    }
					else{
						$msg = $tbl_contact_forms[0]->headline;
						DB::table('tmp_ques')->truncate();
							
						$ques_ans_data = DB::table('tmp_ques_ans')->get();
						
						$contact_Form_Data = DB::table('contact_forms')
											->where('id','=',$cfq_ID)
											->get();
										
						$email_template = DB::table('email_templates')
									->where('title','LIKE','contact_form_email')
									->get();
						
						$email_subject = $email_template[0]->subject;
						$template = $email_template[0]->description;
						
						$bot_name = DB::table('bots')
										->where('id','=',$dbBotId)
										->get();
						$name = $bot_name[0]->username;
						$userID = $bot_name[0]->user_id;
						$userData = DB::table('users')
										->where('id','=',$userID)
										->get();
						$userNAME = (isset($userData[0]->first_name) && !empty($userData[0]->first_name))?$userData[0]->first_name:'';
						
						$contactFormEmail = DB::table('site_settings')
												->where('name','=','contact_form_email')
												->get();
						$from_email = $contactFormEmail[0]->value;
						//$to_email = $contactFormEmail[0]->value;
						$to_email = (isset($contact_Form_Data[0]->email) && !empty($contact_Form_Data[0]->email))?$contact_Form_Data[0]->email:'';

						$to_email = preg_replace('/\s+/', '', $to_email);
						$to_email = explode(',', $to_email);

						$ques_html = "";
						 foreach($ques_ans_data as $k1 => $v1){
							 $ques_html .= '<p><b>'.$v1->ques.'</b>: <br>';
							 $ques_html .= $v1->ans;
							 $ques_html .= '</p>';
						  }
						
						$emailFindReplace = array(
							'##USERNAME##' => $userNAME,
							'##BOT_NAME##' => $name,
							'##SUBMENU_HEADING_TEXT##' => $contact_Form_Data[0]->submenu_heading_text,
							'##HEADLINE##' => $contact_Form_Data[0]->headline,
							'##QUES-ANS##' => $ques_html
						);
							
						$html = strtr($template, $emailFindReplace);
						//file_put_contents(public_path().'/result.txt',json_encode($email_subject));
						
						$email_arr = array();
						if(!empty($to_email))
						{
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
						}
					
						DB::table('tmp_ques_ans')->truncate();
					}
					
                 //   file_put_contents(public_path().'/result.txt',json_encode($contact_form_questions)); 
                
                    /* ContactForms */
                }
                else if(isset($tbl_galleries[0]) && !empty($tbl_galleries[0])){
                    DB::table('tmp_mytable')->truncate();
					$gallery_ID = $tbl_galleries[0]->id;
					
					$g_images = DB::table('gallery_images')->where('gallery_id','=',$gallery_ID)->orderBy('sort_order', 'asc')->get();

					$arr = '';
					$Back = "\xE2\x97\x80";
                    $i = 0;
					if(isset($g_images) && !empty($g_images)){
                        foreach($g_images as $gk1 => $gv1){
                            $arr[$i]['text'] = $gv1->title;
                            $arr[$i]['callback_data'] = $gv1->id;
                            $i++;
                        }
                    }
                    $arr[$i]['text'] = $Back;
                    $arr[$i]['callback_data'] = '';

                    $arr = array_chunk($arr,2);

                    $keyboard = $arr;
					$msg = $tbl_galleries[0]->introduction_headline;
					
					$g_limit = 1;
                    $offset = 0;
					
					DB::table('tmp_img')->insert(
						[
							'gallery_id' => $gallery_ID,
							'data_limit' => $offset
						]
					);
                }
                else if(isset($tbl_chanels[0]) && !empty($tbl_chanels[0])){
					DB::table('tmp_mytable')->truncate();
					DB::table('tmp_mytable')->truncate();
                    if(isset($tbl_chanels[0]->chanel_msg) && !empty($tbl_chanels[0]->chanel_msg)){
                        $msg = $tbl_chanels[0]->chanel_msg;
                    }
                    
					if(!empty($tbl_chanels[0]->image)){
                        //$msg = '';
                        $img_url = public_path().'/uploads/'.$tbl_chanels[0]->image;
                        $image_name = $tbl_chanels[0]->image;
                    }
                
                    /* Channel Keyboard */
                    $arr = '';
                    $Back = "\xE2\x97\x80";
                    $i = 0;
                    $db_chanels = DB::table('chanels')->where('type_id', '=', $dbBotId)->get();
                    if(isset($db_chanels) && !empty($db_chanels)){
                        foreach($db_chanels as $ck1 => $chv1){
                            $arr[$i]['text'] = $chv1->chanel_submenu_heading_text ;
                            $arr[$i]['callback_data'] = $chv1->id;
                            $i++;
                        }
                    }
                    $arr[$i]['text'] = $Back;
                    $arr[$i]['callback_data'] = '';

                    $arr = array_chunk($arr,2);

                    $keyboard = $arr;
                }
                else{
					$chk_ques_data = DB::table('tmp_mytable')->get();
					if($messageText != "\xE2\x97\x80" && !empty($chk_ques_data[0]->data_key)){
						$dataKey = $chk_ques_data[0]->data_key;
						$dataValue = $chk_ques_data[0]->data_value;
						DB::table('tmp_mytable')->truncate();
						DB::table('tmp_mytable')->insert(
							['data_key' => $dataKey,'data_value' => $dataValue]
						);
						$db_contact_form = DB::table('contact_forms')->where('type_id', '=', $dbBotId)->get();
				
						$arr = '';
						$Back = "\xE2\x97\x80";
						$i = 0;
						if(isset($db_contact_form) && !empty($db_contact_form)){
							foreach($db_contact_form as $ac1 => $cv1){
								$arr[$i]['text'] = $cv1->submenu_heading_text;
								$arr[$i]['callback_data'] = $cv1->id;
								$i++;
							}
						}
						$arr[$i]['text'] = $Back;
						$arr[$i]['callback_data'] = '';
	
						$arr = array_chunk($arr,2);
	
						$keyboard = $arr;
						
						$cfq_ID = $dataKey;	
						$q_limit = 1;
						$offset = 0;
						
						$cf_ques = DB::table('tmp_ques')
							->where('ques_id', '=', $cfq_ID)
							->orderBy('id', 'desc')
							->get();
						
						if(isset($cf_ques[0]->data_limit) && count($cf_ques[0]->data_limit) != 0){
							$offset = $cf_ques[0]->data_limit+1;
						}	
						
						$contact_form_questions = DB::table('contact_form_questions')
							->where('contact_form_id', '=', $cfq_ID)
							->limit($q_limit)
							->offset($offset)
							->get();
							
						$msg = '';
						if(isset($contact_form_questions[0]->ques_heading) && !empty($contact_form_questions[0]->ques_heading)){
							$msg = $contact_form_questions[0]->ques_heading;
							DB::table('tmp_ques')->insert([
									'ques_id' => $cfq_ID,
									'data_limit' => $offset
								]
							);
							$lastID = DB::getPdo()->lastInsertId();
						
							DB::table('tmp_ques_ans')->insert(
								[
									'tmp_ques_id' => $lastID,
									'bot_id' => $dbBotId,
									'ques' => $msg,
									'ans' => ''
								]
							);
						}
						else{
							$msg = $dataValue;
							DB::table('tmp_ques')->truncate();
							
							$ques_ans_data = DB::table('tmp_ques_ans')->get();
							
							$contact_Form_Data = DB::table('contact_forms')
												->where('id','=',$cfq_ID)
												->get();
											
							$email_template = DB::table('email_templates')
										->where('title','LIKE','contact_form_email')
										->get();
							
							$email_subject = $email_template[0]->subject;	

							$template = $email_template[0]->description;
							
							$bot_name = DB::table('bots')
											->where('id','=',$dbBotId)
											->get();
							$name = $bot_name[0]->username;
							$userID = $bot_name[0]->user_id;
							$userData = DB::table('users')
											->where('id','=',$userID)
											->get();
							$userNAME = (isset($userData[0]->first_name) && !empty($userData[0]->first_name))?$userData[0]->first_name:'';
							
							$contactFormEmail = DB::table('site_settings')
													->where('name','=','contact_form_email')
													->get();
							$from_email = $contactFormEmail[0]->value;						
							
							//$to_email = $contactFormEmail[0]->value;
							$to_email = (isset($contact_Form_Data[0]->email) && !empty($contact_Form_Data[0]->email))?$contact_Form_Data[0]->email:'';
							
							$ques_html = '';
							 foreach($ques_ans_data as $k1 => $v1){
								 $ques_html .= '<p>';
								 $ques_html .= '<b>'.$v1->ques.'</b>:<br>';
								 $ques_html .= $v1->ans;
								 $ques_html .= '<p>';
							  }
							
							$emailFindReplace = array(
								'##USERNAME##' => $userNAME,
								'##BOT_NAME##' => $name,
								'##SUBMENU_HEADING_TEXT##' => $contact_Form_Data[0]->submenu_heading_text,
								'##HEADLINE##' => $contact_Form_Data[0]->headline,
								'##QUES-ANS##' => $ques_html
							);
								
							$html = strtr($template, $emailFindReplace);
							//file_put_contents(public_path().'/result.txt',json_encode($html));
							$email_arr = array();
							if(!empty($to_email))
							{
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
							}
						
							DB::table('tmp_ques_ans')->truncate();
						}
					}	
					
					
					
					$g_images = DB::table('tmp_img')
                        ->orderBy('id', 'desc')
                        ->get();	
						
					$galleryID = (isset($g_images[0]->gallery_id) && !empty($g_images[0]->gallery_id))?$g_images[0]->gallery_id:'';
					if(!empty($galleryID)){
						$gallery_images = DB::table('gallery_images')
							->where('gallery_id', '=', $galleryID)
							->where('title', 'LIKE', '%'.$messageText.'%')
							->get();	
						
						$msg = $gallery_images[0]->description;
						$img_url = '';
						$image_name = '';
						if(isset($gallery_images[0]->image) && !empty($gallery_images[0]->image)){
							$img_url = public_path().'/uploads/'.$gallery_images[0]->image; 
							$image_name = $gallery_images[0]->image;
						}	
					}
								
					
                    /*
                    $keyboard = [
                        [$autoresponse, $contact_form],
                        [$galleries, $channels],
                    ];
                    

                    $msg = (isset($bot_data[0]->start_message) && !empty($bot_data[0]->start_message))?$bot_data[0]->start_message:'';
                    
                    if(!empty($bot_messages_id)){
                        DB::table('bot_messages')->where('id', $bot_messages_id)->update(['reply_to_message' => $msg]);
                    }
					*/
                }
            }
        }
        
        if(!empty($messageText) && $messageText == "\xE2\x97\x80"){
            /*$keyboard = [
                [$autoresponse, $contact_form],
                [$galleries, $channels],
            ];
			*/
            $msg = (isset($bot_data[0]->start_message) && !empty($bot_data[0]->start_message))?$bot_data[0]->start_message:'';
            $msg .= chr(10).chr(10).'© Citymes.com';

            DB::table('tmp_img')->truncate();
            DB::table('tmp_bots')->truncate();
            DB::table('tmp_message')->truncate();
            DB::table('tmp_ques')->truncate();
			DB::table('tmp_mytable')->truncate();
			DB::table('tmp_ques_ans')->truncate();
        }
        
    }		
	//$new_keyboard_arr=array();	
    //$new_keyboard_arr['inline_keyboard']=$keyboard;
	//$markup = json_encode($keyboard, true);
	
	 
    $reply_markup = $telegram->replyKeyboardMarkup([
		  'keyboard' => $keyboard, 
		  'resize_keyboard' => true, 
		  'one_time_keyboard' => false
		]);
	
						
		

    
    if(!empty($img_url)){
        if(!empty($bot_messages_id)){
            DB::table('bot_messages')->where('id', $bot_messages_id)->update(['reply_message' => $image_name]);
        }
        
        $BOT_TOKEN = $token;
        $chat_id = $chatId;
        
        $BOTAPI = 'https://api.telegram.org/bot' . $BOT_TOKEN .'/';
        
        $cfile = new CURLFile($img_url, 'image/jpg');

        if(!empty($msg)) {
            if (strlen($msg)<200) {
                $data = [
                    'chat_id' => $chat_id,
                    'photo' => $cfile,
                    'caption' => strip_tags($msg),
                ];
                $ch = curl_init($BOTAPI.'sendPhoto');
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($ch);
                curl_close($ch);
            } else {
                $data = [
                    'chat_id' => $chat_id,
                    'photo' => $cfile
                ];
                $ch = curl_init($BOTAPI.'sendPhoto');
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($ch);
                curl_close($ch);
                $response = $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $msg,
                    'reply_markup' => $reply_markup,
	                'parse_mode' => 'HTML'
                ]);
            }
        }
        else{
            $data = [
                'chat_id' => $chat_id,
                'photo' => $cfile
            ];
            $ch = curl_init($BOTAPI.'sendPhoto');
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
        }


    } else if (!empty($msg)){
        if(!empty($bot_messages_id)){
            DB::table('bot_messages')->where('id', $bot_messages_id)->update(array('reply_message' => $msg));
        }

        $response = $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $msg,
            'reply_markup' => $reply_markup,
	        'parse_mode' => 'HTML'
        ]);
    }

    return 'ok';
	
});

Route::post('stripe/stripe_webhook', function (){
	$input = file_get_contents("php://input");
	$event_json = json_decode($input);
	file_put_contents(public_path().'/result2.txt',$input,FILE_APPEND);
	file_put_contents(public_path().'/result3.txt',$input);
	
	$subscriptionID = (isset($event_json->data->object->id) && !empty($event_json->data->object->id))?$event_json->data->object->id:'';
	
	$userID = '';
	$planID = '';
	$type = '';
	$typeId = '';
	$price = '';
	$created_at = date('Y-m-d h:i:s');
	$updated_at = date('Y-m-d h:i:s');
	$message = '';
	
	if(!empty($subscriptionID)){
		$botDATA = DB::table('bots')
					->where('stripe_subscription_id','=',$subscriptionID)
					->get();
		
		if(isset($botDATA[0]->user_id) && !empty($botDATA[0]->user_id)){
			$userID = $botDATA[0]->user_id;
			$type = 'bot';
			$typeId = $botDATA[0]->id;
			
			$message = 'bot <b>'.$botDATA[0]->username.'</b>';
		}
		
		if(empty($userID)){
			$channelData = DB::table('my_channels')
								->where('stripe_subscription_id','=',$subscriptionID)
								->get();
			
			if(isset($channelData[0]->user_id) && !empty($channelData[0]->user_id)){
				$userID = $channelData[0]->user_id;
				$type = 'Channel';
				$typeId = $channelData[0]->id;
				$message = 'Channel <b>@'.$channelData[0]->name.'</b>';
			}
		}
		
		
		if(!empty($userID) && !empty($type) && !empty($typeId)){
			$plan_id = DB::table('user_subscriptions')
						->where('user_id','=',$userID)
						->where('types','=',$type)
						->where('type_id','=',$typeId)
						->get();
			
			$planID = (isset($plan_id[0]->plan_id) && !empty($plan_id[0]->plan_id))?$plan_id[0]->plan_id:'';
			$price = (isset($plan_id[0]->price) && !empty($plan_id[0]->price))?$plan_id[0]->price:'';
		}
		
		
		if(!empty($userID) && !empty($type) && !empty($typeId) && !empty($planID) && !empty($price)){
			DB::table('user_transactions')->insert(
				['user_id' => $userID, 'plan_id' => $planID, 'types' => $type, 'type_id' => $typeId, 'amount' => $price, 'Description' => '', 'created_at' => $created_at, 'updated_at' => $updated_at]
			);
			
			$userDetail = DB::table('users')
							->where('id','=',$userID)
							->get();
							
			$to_email =  $userDetail[0]->email;	
			$contactFormEmail = DB::table('site_settings')
									->where('name','=','contact_form_email')
									->get();
			$from_email = $contactFormEmail[0]->value;			
			
			$email_template = DB::table('email_templates')
								->where('title','LIKE','subscription_renu')
								->get();
			$email_subject = $email_template[0]->subject;					
			$template = $email_template[0]->description;
			
			$emailFindReplace = array(
				'##MESSAGE##' => $message
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
		}
	}
});