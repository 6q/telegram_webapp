@extends('back.template')

@section('main')

	@include('back.partials.entete', ['title' => trans('back/page.dashboard'), 'icone' => 'page', 'fil' => link_to('page', trans('back/page.pages')) . ' / ' . trans('back/page.card')])

	<p>{{ trans('back/page.title') . ' : '}}<?php echo $page->title;?></p>
	<p>{{ trans('back/page.description') . ' : '}}<?php echo $page->content;?></p>
	<p>{{ trans('back/page.meta_title') . ' : '}}<?php echo $page->meta_title;?></p>
	<p>{{ trans('back/page.meta_description') . ' : '}}<?php echo $page->meta_description;?></p>
	<p>{{ trans('back/page.meta_keyword') . ' : '}}<?php echo $page->meta_keyword;?></p>
	
	<p>
	{{ trans('back/page.status') . ' : '}} @if($page->status ==1) <img class="img-responsive" src="{{asset("img/enable.png")}}" alt=""> @else <img class="img-responsive" src="{{asset("img/disable.png")}}" alt=""> @endif</p>

@stop