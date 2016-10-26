@extends('front.template')

@section('head')

<style type="text/css">
  
  .badge {
    padding: 1px 8px 1px;
    background-color: #aaa !important;
  }

</style>

@stop

@section('main')
   
   {!! link_to_route('bot.create', trans('front/bots.create'), [] ['class' => 'btn btn-success btn-block btn']) !!}
   
   @if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
   @endif

  <div class="pull-right link">{!! $links !!}</div>

	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ trans('front/bots.name') }}</th>
					<th>{{ trans('front/bots.bot_token') }}</th>
					<th>{{ trans('front/bots.start_message') }}</th>
					
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			  	@foreach ($bots as $bot)
		<tr>
			<td class="text-primary"><strong>{{ $bot->username }}</strong></td>
			
			<td>{{ $bot->duration }}</td>
			<td>{{ $bot->start_message }}</td>
			
			
			<td>{!! link_to_route('bot.edit', trans('front/bots.edit'), [$bot->id], ['class' => 'btn btn-warning btn-block']) !!}</td>
			<td>
				{!! Form::open(['method' => 'DELETE', 'route' => ['bot.destroy', $bot->id]]) !!}
				{!! Form::destroy(trans('back/plans.destroy'), trans('front/bots.confirm')) !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach
      </tbody>
		</table>
	</div>

	<div class="pull-right link">{!! $links !!}</div>

@stop
