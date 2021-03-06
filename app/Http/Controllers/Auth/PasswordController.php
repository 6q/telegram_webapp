<?php

namespace App\Http\Controllers\Auth;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Http\Requests\Auth\EmailPasswordLinkRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;

class PasswordController extends Controller
{

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;

	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  EmailPasswordLinkRequest  $request
	 * @param  Illuminate\View\Factory $view
	 * @return Response
	 */
	public function postEmail(EmailPasswordLinkRequest $request,Factory $view)
	{
		$to_email = $request->get('email');
		
		$view->composer('emails.auth.password', function($view) use ($to_email) {
            $view->with([
                'title'   => trans('front/password.email-title'),
                'intro'   => trans('front/password.email-intro'),
                'link'    => trans('front/password.email-link'),
                'expire'  => trans('front/password.email-expire'),
                'minutes' => trans('front/password.minutes'),
				'to_email' => $to_email
            ]);
        });
		
		$contactFormEmail = DB::table('site_settings')
								->where('name','=','contact_form_email')
								->get();
		$from_email = $contactFormEmail[0]->value;
		
		$email_template = DB::table('email_templates')->where('title','LIKE','forgot_password')->get();										
		$email_subject = $email_template[0]->subject;	
			   
		
		$email_arr = array();
		$email_arr['email_subject'] = $email_subject;
		$email_arr['from_email'] = $from_email;
		
		
        $response = Password::sendResetLink($request->only('email'), function (Message $message) use($email_arr) {
            $message->subject($email_arr['email_subject']);
			$message->from($email_arr['from_email']);
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));

            case Password::INVALID_USER:
                return redirect()->back()->with('error', trans($response));
        }
	}

	/**
	 * Reset the given user's password.
	 * 
	 * @param  ResetPasswordRequest  $request
	 * @return Response
	 */
	public function postReset(ResetPasswordRequest $request)
	{
		$credentials = $request->only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password) {
			$this->resetPassword($user, $password);
		});

		switch ($response) {
			case Password::PASSWORD_RESET:
				return redirect()->to('/')->with('ok', trans('passwords.reset'));

			default:
				return redirect()->back()
						->with('error', trans($response))
						->withInput($request->only('email'));
		}
	}

}
