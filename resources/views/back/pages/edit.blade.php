@extends('back.template')

@section('main')
{!! HTML::script('ckeditor/ckeditor.js') !!}
	<!-- Entête de page -->
	@include('back.partials.entete', ['title' => trans('back/page.dashboard'), 'icone' => 'user', 'fil' => link_to('page', trans('back/page.pages')) . ' / ' . trans('back/page.edition')])

	<div class="col-sm-12">
		 {!! Form::model($page, array('route' => array('page.update', $page->id),'method' => 'put')) !!}
			{!! Form::control('text', 0, 'title', $errors, trans('back/page.title')) !!}
			{!! Form::control('textarea', 0, 'content', $errors, trans('back/page.description')) !!}
			{!! Form::control('text', 0, 'meta_title', $errors, trans('back/page.meta_title')) !!}
			{!! Form::control('text', 0, 'meta_description', $errors, trans('back/page.meta_description')) !!}
			{!! Form::control('text', 0, 'meta_keyword', $errors, trans('back/page.meta_keyword')) !!}
			{!! Form::checkBoxNew('status', trans('back/page.status'),1,$page->status) !!}
			
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>

@stop

