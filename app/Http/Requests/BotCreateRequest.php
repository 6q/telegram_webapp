<?php namespace App\Http\Requests;

class BotCreateRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'username' => 'required',
			'bot_token' => 'required',
		];
	}

}