<?php namespace App\Repositories;

use App\Models\UserBilling;

class UserBillingRepository {

	/**
	 * The Role instance.
	 *
	 * @var App\Models\Role
	 */
	protected $userbilling;

	/**
	 * Create a new RolegRepository instance.
	 *
	 * @param  App\Models\Role $role
	 * @return void
	 */
	public function __construct(UserBilling $userbilling)
	{
		$this->userbilling = $userbilling;
	}

	/**
	 * Get all userbilling.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function all($user_id)
	{
	   	
		return $this->userbilling->with('country','state')->where('user_id', $user_id)->get();
	}

	/**
	 * Update roles.
	 *
	 * @param  array  $inputs
	 * @return void
	 */
	public function update($inputs)
	{
		foreach ($inputs as $key => $value)
		{
			$userbilling = $this->userbilling->where('id', $key)->firstOrFail();

			//$role->title = $value;
			
			$userbilling->save();
		}
	}

	
}
