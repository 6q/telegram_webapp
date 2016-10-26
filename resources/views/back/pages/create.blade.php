@extends('back.template')

@section('main')


{!! HTML::script('ckeditor/ckeditor.js') !!}

 <!-- Entête de page -->
  @include('back.partials.entete', ['title' => trans('back/page.dashboard'), 'icone' => 'user', 'fil' => link_to('user', trans('back/page.page')) . ' / ' . trans('back/page.creation')])

	<div class="col-sm-12">
		{!! Form::open(['url' => 'page', 'method' => 'post', 'class' => 'form-horizontal panel']) !!}	
			{!! Form::control('text', 0, 'title', $errors, trans('back/page.title')) !!}
			{!! Form::control('textarea', 0, 'description', $errors, trans('back/page.description')) !!}
			{!! Form::control('text', 0, 'meta_title', $errors, trans('back/page.meta_title')) !!}
			{!! Form::control('text', 0, 'meta_description', $errors, trans('back/page.meta_description')) !!}
			{!! Form::control('text', 0, 'meta_keyword', $errors, trans('back/page.meta_keyword')) !!}
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>

@stop