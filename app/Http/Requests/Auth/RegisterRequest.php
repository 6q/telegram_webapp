<?php namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RegisterRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|min:5|confirmed',
			'password_confirmation' => 'required|min:5'
		];
	}

}
