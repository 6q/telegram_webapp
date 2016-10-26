<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBilling extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_billings';
	
	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() 
	{
		return $this->belongsTo('App\Models\User');
	}
	
	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function country() 
	{
		return $this->belongsTo('App\Models\Country');
	}
	
	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function state() 
	{
		return $this->belongsTo('App\Models\State');
	}
	

}
