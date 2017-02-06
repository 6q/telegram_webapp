@extends('back.template')

@section('main')

	<!-- Entête de page -->
	@include('back.partials.entete', ['title' => trans('back/plans.dashboard'), 'icone' => 'user', 'fil' => link_to('plan', trans('back/plans.Plans')) . ' / ' . trans('back/plans.edition')])


	<?php
    	if($plan->plan_type == 2){
		?>
        	<style>
            	.bot_plans_fields{display:none;}
            </style>
        <?php
		}
	?>
    
	<div class="col-sm-12">
		{!! Form::model($plan, ['route' => ['plan.update', $plan->id], 'method' => 'put', 'class' => 'form-horizontal panel',"enctype"=>"multipart/form-data"]) !!}
			{!! Form::control('text', 0, 'name', $errors, trans('back/plans.name')) !!}
			{!! Form::control('textarea', 0, 'description', $errors, trans('back/plans.description')) !!}
			{!! Form::control('text', 0, 'duration', $errors, trans('back/plans.duration')) !!}
			{!! Form::control('text', 0, 'price', $errors, trans('back/plans.price')) !!}
			
            <div class="bot_plans_fields">
            	{!! Form::control('text', 0, 'autoresponses', $errors, trans('back/plans.autoresponses')) !!}
                {!! Form::control('text', 0, 'contact_forms', $errors, trans('back/plans.contact_forms')) !!}
                {!! Form::control('text', 0, 'image_gallery', $errors, trans('back/plans.image_gallery')) !!}
                {!! Form::control('text', 0, 'gallery_images', $errors, trans('back/plans.gallery_images')) !!}
                {!! Form::control('text', 0, 'bot_commands', $errors, trans('back/plans.bot_commands')) !!}
            </div>
            
			{!! Form::control('text', 0, 'manual_message', $errors, trans('back/plans.manual_message')) !!}
			
            <div class="bot_plans_fields">
	            {!! Form::checkBoxNew('custom_image', trans('back/plans.custom_image'),1,$plan->custom_image) !!}
				{!! Form::checkBoxNew('custom_welcome_message', trans('back/plans.custom_welcome_message'),1,$plan->custom_welcome_message) !!}
				{!! Form::checkBoxNew('custom_not_allowed_message', trans('back/plans.custom_not_allowed_message'),1,$plan->custom_not_allowed_message) !!}
            </div>
                
			{!! Form::checkBoxNew('status', trans('back/plans.status'),1,$plan->status) !!}
			
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>

@stop

