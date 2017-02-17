<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ThrottlesLogins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepository;
use App\Jobs\SendMail;

use Illuminate\Contracts\Auth\User;
use Auth;
use App\Http\Controllers\Auth\AuthController;

use Illuminate\Support\Facades\Route;

use DB;

class AuthController extends Controller
{

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
		
		$actionname = Route::getCurrentRoute()->getActionName();
		$dd = explode('@',$actionname);
	
		if (Auth::check() && $dd[1] == 'getLogin'){
			//redirect('/dashboard');
			header('Location: dashboard');
			die();
		}
		
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  App\Http\Requests\LoginRequest  $request
	 * @param  Guard  $auth
	 * @return Response
	 */
	public function postLogin(
		LoginRequest $request,
		Guard $auth)
	{
		$logValue = $request->input('log');

		$logAccess = filter_var($logValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $throttles = in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
			return redirect('/')
				->with('error', trans('front/login.maxattempt'))
				->withInput($request->only('log'));
        }

		$credentials = [
			$logAccess  => $logValue, 
			'password'  => $request->input('password')
		];

		if(!$auth->validate($credentials)) {
			if ($throttles) {
	            $this->incrementLoginAttempts($request);
	        }

			return redirect('/')
				->with('error', trans('front/login.credentials'))
				->withInput($request->only('log'));
		}
			
		$user = $auth->getLastAttempted();

		if($user->confirmed) {
			if ($throttles) {
                $this->clearLoginAttempts($request);
            }

			$auth->login($user, $request->has('memory'));
            
			if($request->session()->has('user_id'))	{
				$request->session()->forget('user_id');
			}
			
			if ($user->role_id == 1) 
			{
			
				return redirect('/admin');
			}

			return redirect('/dashboard');
		}
		
		$request->session()->put('user_id', $user->id);	

		return redirect('/')->with('error', trans('front/verify.again'));			
	}


	/**
	 * Handle a registration request for the application.
	 *
	 * @param  App\Http\Requests\RegisterRequest  $request
	 * @param  App\Repositories\UserRepository $user_gestion
	 * @return Response
	 */
	public function postRegister(
		RegisterRequest $request,
		UserRepository $user_gestion)
	{	   
		$user = $user_gestion->store(
			$request->all(), 
			$confirmation_code = str_random(30)
		);
		
		$pass = $request->get('password');
		if(!empty($user))
		{
			$to_email = $user->email;
			if(!empty($to_email)){
				$contactFormEmail = DB::table('site_settings')
								->where('name','=','contact_form_email')
								->get();
								
				$from_email = $contactFormEmail[0]->value;
				
				$email_template = DB::table('email_templates')
										->where('title','LIKE','welcome_email')
										->get();
				$email_subject = $email_template[0]->subject;						
				$template = $email_template[0]->description;
				
				$emailFindReplace = array(
					'##USERNAME##' => $to_email,
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
					$message->from($email_arr['to_email']);
					$message->to($email_arr['to_email'])->subject($email_arr['subject']);
				});
				
				$credentials = array(
					'email' => $to_email,
					'password' => $pass
				);
				
				if (Auth::attempt($credentials)) {
					return redirect('/dashboard')->with('ok', trans('front/verify.message'));
				}
				
			}
		}

		//$this->dispatch(new SendMail($user));

		return redirect('/')->with('ok', trans('front/verify.message'));
	}

	/**
	 * Handle a confirmation request.
	 *
	 * @param  App\Repositories\UserRepository $user_gestion
	 * @param  string  $confirmation_code
	 * @return Response
	 */
	public function getConfirm(
		UserRepository $user_gestion,
		$confirmation_code)
	{
		$user = $user_gestion->confirm($confirmation_code);

        return redirect('/')->with('ok', trans('front/verify.success'));
	}

	/**
	 * Handle a resend request.
	 *
	 * @param  App\Repositories\UserRepository $user_gestion
	 * @param  Illuminate\Http\Request $request
	 * @return Response
	 */
	public function getResend(
		UserRepository $user_gestion,
		Request $request)
	{
		if($request->session()->has('user_id'))	{
			$user = $user_gestion->getById($request->session()->get('user_id'));

			$this->dispatch(new SendMail($user));

			return redirect('/')->with('ok', trans('front/verify.resend'));
		}

		return redirect('/');        
	}
	
}
