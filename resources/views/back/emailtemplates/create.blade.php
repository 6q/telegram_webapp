@extends('back.template')

@section('main')


{!! HTML::script('ckeditor/ckeditor.js') !!}


  @include('back.partials.entete', ['title' => trans('back/emailtemplate.dashboard'), 'icone' => 'user', 'fil' => link_to('emailtemplate', trans('back/emailtemplate.view_email_template')) . ' / ' . trans('back/emailtemplate.creation')])

	<div class="col-sm-12">
		{!! Form::open(['url' => 'emailtemplate', 'method' => 'post', 'class' => 'form-horizontal panel']) !!}	
			{!! Form::control('text', 0, 'title', $errors, trans('back/emailtemplate.title')) !!}
      {!! Form::control('text', 0, 'subject', $errors, trans('back/emailtemplate.subject')) !!}
			{!! Form::control('textarea', 0, 'description', $errors, trans('back/emailtemplate.description')) !!}
			{!! Form::control('text', 0, 'from', $errors, trans('back/emailtemplate.from')) !!}
			{!! Form::control('text', 0, 'reply_to_email', $errors, trans('back/emailtemplate.reply_to_email')) !!}
      {!! Form::checkHorizontal('is_html', trans('back/emailtemplate.is_html'),1) !!}
      {!! Form::checkHorizontal('status', trans('back/emailtemplate.status'),1) !!}
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>

@stop