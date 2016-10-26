<?php namespace App\Http\Requests;

class PlanCreateRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required',
			'duration' => 'required|integer',
			'price' => 'required'
			
		];
	}

}