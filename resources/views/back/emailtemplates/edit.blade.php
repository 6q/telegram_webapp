@extends('back.template')

@section('main')
{!! HTML::script('ckeditor/ckeditor.js') !!}

		@include('back.partials.entete', ['title' => trans('back/emailtemplate.dashboard'), 'icone' => 'user', 'fil' => link_to('emailtemplate', trans('back/emailtemplate.pages')) . ' / ' . trans('back/emailtemplate.edition')])

	<div class="col-sm-12">
		 {!! Form::model($emailtemplate, array('route' => array('emailtemplate.update', $emailtemplate->id),'method' => 'put')) !!}
			{!! Form::control('text', 0, 'title', $errors, trans('back/emailtemplate.title')) !!}
      		{!! Form::control('text', 0, 'subject', $errors, trans('back/emailtemplate.subject')) !!}
			{!! Form::control('textarea', 0, 'description', $errors, trans('back/emailtemplate.description')) !!}
			
            <?php /*
            {!! Form::control('text', 0, 'from', $errors, trans('back/emailtemplate.from')) !!}
			{!! Form::control('text', 0, 'reply_to_email', $errors, trans('back/emailtemplate.reply_to_email')) !!}
      		{!! Form::checkBoxNew('is_html', trans('back/emailtemplate.is_html'),1,$emailtemplate->is_html) !!}
			*/ ?>
            
      		
            {!! Form::checkBoxNew('status', trans('back/emailtemplate.status'),1,$emailtemplate->status) !!}
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>

@stop

