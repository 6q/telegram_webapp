@extends('back.template')

@section('head')

@stop

@section('main')

  @include('back.partials.entete', ['title' => trans('back/bot.bot_command'), 'icone' => 'user', 'fil' => trans('back/bot.users')])
 
  @if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif

	
	<div class="col-sm-12">
    	 @if ($errors->has())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>        
                @endforeach
            </div>
            @endif
                
		{!! Form::open(['url' => 'bot/bot_command/'.$bot_id, 'method' => 'post', 'class' => 'form-horizontal panel']) !!}	
        	{!! Form::control('hidden', 0, 'bot_id', $errors, '',$bot_id) !!}
			{!! Form::control('text', 0, 'title', $errors, trans('back/bot.bot_command')) !!}
			{!! Form::control('textarea', 0, 'command_description', $errors, trans('back/bot.command_description')) !!}
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>



@stop

@section('scripts')

@stop