@extends('front.login_template')

@section('main')
<div class="row">
<div class="col-sm-12">
@if(session()->has('status'))
	@include('partials/error', ['type' => 'success', 'message' => session('status')])
@endif
@if(session()->has('error'))
	@include('partials/error', ['type' => 'danger', 'message' => session('error')])
@endif
<h2>{{ trans('front/password.title') }}</h2>
<p>{{ trans('front/password.info') }}</p>
</div>

<div class="col-sm-6 col-sm-offset-3">
<div class="sigup content">
<div class="register">
<img class="register_logo"  src="{{asset("img/login_logo.png")}}"  />
<h3>{{ trans('front/password.title') }}</h3>
<p>{{ trans('front/password.reset-info') }}</p>
<div class="col-laguage">
	<?php 
		$currLang = Config::get('app.locale');
		$select1 = '';
		if($currLang == 'en'){
			$select1 = 'selected="selected"';
		}
	
		$select2 = '';
		if($currLang == 'fr'){
			$select2 = 'selected="selected"';
		}
	?>
	<select name="select_lang" onchange="changeLang();" id="select_lang">
		<option <?php echo $select1;?> value="{!! URL::to('/language/en') !!}">{!! trans('front/header.english') !!}</option>
		<option <?php echo $select2;?> value="{!! URL::to('/language/fr') !!}">{!! trans('front/header.french') !!}</option>
	</select>
	
	<script>
		function changeLang(){
			var url = $('#select_lang').val();
			window.location.href = url;
		}
	</script>	
</div>
</div>
{!! Form::open(['url' => 'password/reset', 'method' => 'post', 'role' => 'form']) !!}	
{!! Form::hidden('token', $token) !!}
<ul>
<li>{!! Form::control('email', 6, 'email', $errors, trans('front/password.email')) !!}</li>
<li>{!! Form::control('password', 6, 'password', $errors, trans('front/password.password'), null, [trans('front/password.warning'), trans('front/password.warning-password')]) !!}</li>
<li>{!! Form::control('password', 6, 'password_confirmation', $errors, trans('front/password.confirm-password')) !!}</li>
<li>{!! Form::submit(trans('front/password.send')) !!}</li>
<li>
{!! link_to('/', trans('front/register.already_text')) !!}
</li>
</ul>
{!! Form::close() !!}
</div>
</div>
</div>
</div>
@stop
@section('scripts')

<script>
	$(function() { $('.badge').popover();	});
</script>
@stop