<?php

namespace App\Http\Requests;

class MyChannelCreateRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'name' => 'required',
            'share_link' => 'required',
            'description' => 'required',
        ];
    }

}
