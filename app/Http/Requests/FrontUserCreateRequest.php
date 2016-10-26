<?php namespace App\Http\Requests;

class FrontUserCreateRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'first_name' => 'required',
			'last_name' => 'required',
			'country' => 'required',
			'zipcode' => 'required',
			'mobile' => 'required'
		];
	}

}