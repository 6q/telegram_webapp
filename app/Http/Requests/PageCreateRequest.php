<?php namespace App\Http\Requests;

class PageCreateRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'title' => 'required',
			'description' => 'required',
			'meta_title' => 'required',
			'meta_description' => 'required',
			'meta_keyword' => 'required'
		];
	}

}