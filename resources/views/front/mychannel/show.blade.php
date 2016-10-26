@extends('back.template')

@section('main')

	@include('back.partials.entete', ['title' => trans('back/plans.dashboard'), 'icone' => 'user', 'fil' => link_to('plan', trans('back/plans.Plans')) . ' / ' . trans('back/plans.card')])

	<p>{{ trans('back/plans.name') . ' : ' .  $plan->name }}</p>
	<p>{{ trans('back/plans.description') . ' : ' .  $plan->description }}</p>
	<p>{{ trans('back/plans.duration') . ' : ' .  $plan->duration }}</p>
	<p>{{ trans('back/plans.price') . ' : ' .  $plan->price }}</p>
	<p>{{ trans('back/plans.autoresponses') . ' : ' .  $plan->autoresponses }}</p>
	<p>{{ trans('back/plans.contact_forms') . ' : ' .  $plan->contact_forms }}</p>
	<p>{{ trans('back/plans.image_gallery') . ' : ' .  $plan->image_gallery }}</p>
	<p>{{ trans('back/plans.gallery_images') . ' : ' .  $plan->gallery_images }}</p>
	<p>{{ trans('back/plans.manual_message') . ' : ' .  $plan->manual_message }}</p>
	
	<p>
	{{ trans('back/plans.custom_image') . ' : '}} 
	
	@if($plan->custom_image ==1) {{ trans('back/plans.yes')}}  @else {{ trans('back/plans.no')}}  @endif</p>
   
  <p>
	{{ trans('back/plans.custom_welcome_message') . ' : '}} 
	
	@if($plan->custom_welcome_message ==1) {{ trans('back/plans.yes')}}  @else {{ trans('back/plans.no')}}  @endif</p>
  
  <p>
	{{ trans('back/plans.custom_not_allowed_message') . ' : '}} 
	
	@if($plan->custom_not_allowed_message ==1) {{ trans('back/plans.yes')}}  @else {{ trans('back/plans.no')}}  @endif</p>

  <p>
  {{ trans('back/plans.status') . ' : '}} 
	 @if($plan->status ==1) <img class="img-responsive" src="{{asset("img/enable.png")}}" alt=""> @else <img class="img-responsive" src="{{asset("img/disable.png")}}" alt=""> @endif
  </p>
	
   
@stop