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

  @include('back.partials.entete', ['title' => trans('back/plans.dashboard') . link_to_route('plan.create', trans('back/plans.add'), [], ['class' => 'btn btn-info pull-right']), 'icone' => 'user', 'fil' => trans('back/plans.plans')])
 
   @if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif

  <div class="pull-right link">{!! $links !!}</div>

	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/plans.name') }}</th>
					<th>{{ trans('back/plans.duration') }}</th>
					<th>{{ trans('back/plans.price') }}</th>
					<th>{{ trans('back/plans.status') }}</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			  @include('back.plans.table')
      </tbody>
		</table>
	</div>

	<div class="pull-right link">{!! $links !!}</div>

@stop
