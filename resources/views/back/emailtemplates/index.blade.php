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

  @include('back.partials.entete', ['title' => trans('back/emailtemplate.dashboard') . link_to_route('emailtemplate.create', trans('back/emailtemplate.add'), [], ['class' => 'btn btn-info pull-right']), 'icone' => 'user', 'fil' => trans('back/emailtemplate.view_email_template')])

	@if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif

  <div class="pull-right link">{!! $links !!}</div>

	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/emailtemplate.title') }}</th>
					<th>{{ trans('back/emailtemplate.subject') }}</th>
					<th>{{ trans('back/emailtemplate.status') }}</th>
					<th>{{ trans('back/emailtemplate.created_date') }}</th>
					<th>{{ trans('back/emailtemplate.action') }}</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			  @include('back.emailtemplates.table') 
      </tbody>
		</table>
	</div>

	<div class="pull-right link">{!! $links !!}</div>

@stop

@section('scripts')

@stop