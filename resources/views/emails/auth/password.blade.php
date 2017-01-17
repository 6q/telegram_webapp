<?php 
$email_check = DB::table('users')->where('email','LIKE',$to_email)->get();
$email_template = DB::table('email_templates')->where('title','LIKE','forgot_password')->get();										
$template = $email_template[0]->description;	

		$emailFindReplace = array(
			'##SITE_LOGO##' => asset('/img/front/logo.png'),
			'##SITE_LINK##' => asset('/'),
			'##SITE_NAME##' => 'Citymes',
			'##USERNAME##' => $email_check[0]->first_name.' '.$email_check[0]->last_name,
			'##URL##' => asset('/password/reset/'.$token),
			'##CONTACT_URL##' => asset('/'),
		);
		
		$html = strtr($template, $emailFindReplace);
		echo $html;
?>