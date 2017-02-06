<?php 
$email_check = DB::table('users')->where('email','LIKE',$to_email)->get();
$email_template = DB::table('email_templates')->where('title','LIKE','forgot_password')->get();										
$template = $email_template[0]->description;	

		$emailFindReplace = array(
			'##USERNAME##' => $email_check[0]->first_name.' '.$email_check[0]->last_name,
			'##URL##' => asset('/password/reset/'.$token),
		);
		
		$html = strtr($template, $emailFindReplace);
		echo $html;
?>