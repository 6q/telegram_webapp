@extends('back.template')

@section('main')

 <!-- Entête de page -->
  @include('back.partials.entete', ['title' => trans('back/plans.dashboard'), 'icone' => 'user', 'fil' => link_to('plan', trans('back/plans.Plans')) . ' / ' . trans('back/plans.creation')])

	<div class="col-sm-12">
		{!! Form::open(['url' => 'plan', 'method' => 'post', 'class' => 'form-horizontal panel']) !!}	
			{!! Form::control('text', 0, 'name', $errors, trans('back/plans.name')) !!}
            
            <div class="form-group ">
            	<label class="control-label">{!! trans('back/plans.select_type') !!}</label>
	            {{ Form::select('plan_type', ['1' => 'Bot','2' => 'Channel'],null, ['id' =>'plan_type','class' => 'form-control']) }}
            </div>
            
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
	            {!! Form::checkHorizontal('custom_image', trans('back/plans.custom_image'),1) !!}
				{!! Form::checkHorizontal('custom_welcome_message', trans('back/plans.custom_welcome_message'),1) !!}
				{!! Form::checkHorizontal('custom_not_allowed_message', trans('back/plans.custom_not_allowed_message'),1) !!}
            </div>    
			
			{!! Form::checkHorizontal('status', trans('back/plans.status'),1) !!}
			
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
    	$(document).ready(function(e) {
            $('#plan_type').change(function(e) {
                var plan_type_id = this.value;
				
				if(plan_type_id == 2){
					$('.bot_plans_fields').css('display','none');
				}
				else{
					$('.bot_plans_fields').css('display','block');
				}
            });
        });
    </script>

@stop