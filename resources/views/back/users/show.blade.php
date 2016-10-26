@extends('back.template')

@section('main')

	@include('back.partials.entete', ['title' => trans('back/users.dashboard'), 'icone' => 'user', 'fil' => link_to('user', trans('back/users.Users')) . ' / ' . trans('back/users.card')])

	<p>{{ trans('back/users.name') . ' : ' .  $user->first_name }}</p>
	<p>{{ trans('back/users.email') . ' : ' .  $user->email }}</p>
	<p>{{ trans('back/users.role') . ' : ' .  $user->role->title }}</p>
	
	<p>
	{{ trans('back/users.status') . ' : '}} @if($user->status ==1) <img class="img-responsive" src="{{asset("img/enable.png")}}" alt=""> @else <img class="img-responsive" src="{{asset("img/disable.png")}}" alt=""> @endif</p>
   
   <p>{{ trans('back/users.zipcode') . ' : ' .  $user->zipcode }}</p>
   <p>
   @if($user->image !='')
   {{ trans('back/users.image') . ' : ' }} <img class="img-responsive" src="{{ url('/user_images/150x150/'.$user->image) }}" alt="">
   @endif
	</p>
   <p>{{ trans('back/users.user_type') . ' : ' .  $user->user_type }}</p>
   
   @if($user->user_type =='company')
    <p>{{ trans('back/users.company_name') . ' : ' .  $user->company_name }}</p>
	<p>{{ trans('back/users.vat_number') . ' : ' .  $user->vat_number }}</p>
   @endif
   <p>{{ trans('back/users.payment_method') . ' : ' .  $user->payment_method }}</p>
   
   <h2 class="page-header">{{ trans('back/user_billings.dashboard') }}</h2>
   <div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/user_billings.address') }}</th>
					<th>{{ trans('back/user_billings.country_id') }}</th>
					<th>{{ trans('back/user_billings.state_id') }}</th>
					<th>{{ trans('back/user_billings.city') }}</th>
					<th>{{ trans('back/user_billings.zipcode') }}</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
		@foreach ($user_billings as $billing)
			<tr>
				<td>{{ $billing->street }} {{ $billing->address }}</td>
				
				<td>{{ $billing->country->name }}</td>
				<td>{{ $billing->state->name }}</td>
				<td>{{ $billing->city }}</td>
				<td>{{ $billing->zipcode }}</td>
				
			</tr>
		@endforeach
      </tbody>
		</table>
	</div>
	
	<h2 class="page-header">{{ trans('back/user_subscriptions.dashboard') }}</h2>
   <div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/user_subscriptions.plan') }}</th>
					<th>{{ trans('back/user_subscriptions.price') }}</th>
					<th>{{ trans('back/user_subscriptions.type') }}</th>
					<th>{{ trans('back/user_subscriptions.subscription_date') }}</th>
					<th>{{ trans('back/user_subscriptions.expiry_date') }}</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
		@foreach ($user_subscriptions as $subscription)
			<tr>
				<td>{{ $subscription->plan->name }}</td>
				
				<td>{{ $subscription->price }}</td>
				<td>{{ $subscription->type }}</td>
				<td>{{ $subscription->subscription_date }}</td>
				<td>{{ $subscription->expiry_date }}</td>
				
			</tr>
		@endforeach
      </tbody>
		</table>
	</div>
	
	<h2 class="page-header">{{ trans('back/user_transactions.dashboard') }}</h2>
   <div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/user_transactions.plan') }}</th>
					<th>{{ trans('back/user_transactions.price') }}</th>
					<th>{{ trans('back/user_transactions.type') }}</th>
					<th>{{ trans('back/user_transactions.date') }}</th>
				
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
		@foreach ($user_transactions as $transaction)
			<tr>
				<td>{{ $transaction->plan->name }}</td>
				
				<td>{{ $transaction->price }}</td>
				<td>{{ $transaction->type }}</td>
				<td>{{ $transaction->created_at }}</td>
				
			</tr>
		@endforeach
      </tbody>
		</table>
	</div>
   	
   
@stop