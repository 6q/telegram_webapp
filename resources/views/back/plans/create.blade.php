@extends('back.template')

@section('main')

 <!-- Entête de page -->
  @include('back.partials.entete', ['title' => trans('back/plans.dashboard'), 'icone' => 'user', 'fil' => link_to('plan', trans('back/plans.Plans')) . ' / ' . trans('back/plans.creation')])

	<div class="col-sm-12">
		{!! Form::open(['url' => 'plan', 'method' => 'post', 'class' => 'form-horizontal panel']) !!}	
			{!! Form::control('text', 0, 'name', $errors, trans('back/plans.name')) !!}
			{!! Form::control('textarea', 0, 'description', $errors, trans('back/plans.description')) !!}
			{!! Form::control('text', 0, 'duration', $errors, trans('back/plans.duration')) !!}
			{!! Form::control('text', 0, 'price', $errors, trans('back/plans.price')) !!}
			{!! Form::control('text', 0, 'autoresponses', $errors, trans('back/plans.autoresponses')) !!}
			{!! Form::control('text', 0, 'contact_forms', $errors, trans('back/plans.contact_forms')) !!}
			{!! Form::control('text', 0, 'image_gallery', $errors, trans('back/plans.image_gallery')) !!}
			{!! Form::control('text', 0, 'gallery_images', $errors, trans('back/plans.gallery_images')) !!}
			{!! Form::control('text', 0, 'manual_message', $errors, trans('back/plans.manual_message')) !!}
			{!! Form::checkHorizontal('custom_image', trans('back/plans.custom_image'),1) !!}
			{!! Form::checkHorizontal('custom_welcome_message', trans('back/plans.custom_welcome_message'),1) !!}
			{!! Form::checkHorizontal('custom_not_allowed_message', trans('back/plans.custom_not_allowed_message'),1) !!}
			
			{!! Form::checkHorizontal('status', trans('back/plans.status'),1) !!}
			
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>

@stop