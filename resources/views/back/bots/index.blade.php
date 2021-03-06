@extends('back.template')

@section('head')

<style type="text/css">
  
  .badge {
    padding: 1px 8px 1px;
    background-color: #aaa !important;
  }
  
  .thumb {
    width: 100%;
  }
  
  .no_record{
    text-align:center;
    font-weight:bold;
  }

</style>

@stop

@section('main')

  @include('back.partials.entete', ['title' => trans('back/bot.dashboard') . link_to_route('user.index', trans('back/bot.back'), [], ['class' => 'btn btn-info pull-right']), 'icone' => 'user', 'fil' => trans('back/bot.users')])
 
  @if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif

  <div class="pull-right link">{!! $links !!}</div>

	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('back/bot.stripe_customer_id') }}</th>
					<th>{{ trans('back/bot.bot_username') }}</th>
					<th>{{ trans('back/bot.token') }}</th>
					<th>{{ trans('back/bot.description') }}</th>
					<th>{{ trans('back/bot.start_message') }}</th>
					<th>{{ trans('back/bot.created_at') }}</th>
					<th>{{ trans('back/bot.actions') }}</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			  @include('back.bots.table')
      </tbody>
		</table>
	</div>

	<div class="pull-right link">{!! $links !!}</div>

@stop

@section('scripts')

@stop