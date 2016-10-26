@extends('back.template')

@section('head')

<style type="text/css">
  
  .badge {
    padding: 1px 8px 1px;
    background-color: #aaa !important;
  }

</style>

@stop

@section('main')

  @include('back.partials.entete', ['title' => trans('back/page.dashboard') . link_to_route('page.create', trans('back/page.add'), [], ['class' => 'btn btn-info pull-right']), 'icone' => 'user', 'fil' => trans('back/page.pages')])

	@if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif

  <div class="pull-right link">{!! $links !!}</div>

	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/page.title') }}</th>
					<th>{{ trans('back/page.meta_title') }}</th>
					<th>{{ trans('back/page.meta_description') }}</th>
					<th>{{ trans('back/page.meta_keyword') }}</th>
					<th>{{ trans('back/page.status') }}</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			  @include('back.pages.table')
      </tbody>
		</table>
	</div>

	<div class="pull-right link">{!! $links !!}</div>

@stop

@section('scripts')

@stop