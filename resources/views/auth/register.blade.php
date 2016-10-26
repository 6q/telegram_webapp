@extends('front.login_template')

@section('main')

<div class="row">
<div class="col-sm-12">
<h2>{{ trans('front/register.title') }}</h2>
<p>{{ trans('front/register.text_desc') }}</p>
</div>

<div class="col-sm-6 col-sm-offset-3">
<div class="sigup content">
<div class="register">
<img class="register_logo"  src="{{asset("img/login_logo.png")}}"  />
<h3>{{ trans('front/register.title') }}</h3>
<p>{{ trans('front/register.sub_text') }}</p>
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
{!! Form::open(['url' => 'auth/register', 'method' => 'post', 'role' => 'form']) !!}	
<ul>
<li>{!! Form::control1('email', 6, 'email', $errors, trans('front/register.email')) !!}</li>
<li>{!! Form::control1('password', 6, 'password', $errors, trans('front/register.password')) !!}</li>
<li>{!! Form::control1('password', 6, 'password_confirmation', $errors, trans('front/register.confirm-password')) !!}</li>
<li>{!! Form::submit(trans('front/register.send')) !!}</li>
<li>
{!! link_to('/', trans('front/register.already_text')) !!}
</li>
</ul>
{!! Form::close() !!}
</div>
</div>
</div>
</div>

	<?php /*?><div class="row">
		<div class="box">
			<div class="col-lg-12">
				<hr>	
				<h2 class="intro-text text-center">{{ trans('front/register.title') }}</h2>
				<hr>
				<p>{{ trans('front/register.infos') }}</p>		

				{!! Form::open(['url' => 'auth/register', 'method' => 'post', 'role' => 'form']) !!}	

					<div class="row">
						{!! Form::control('text', 6, 'username', $errors, trans('front/register.pseudo'), null, [trans('front/register.warning'), trans('front/register.warning-name')]) !!}
						{!! Form::control('email', 6, 'email', $errors, trans('front/register.email')) !!}
						{!! Form::control('password', 6, 'password', $errors, trans('front/register.password'), null, [trans('front/register.warning'), trans('front/register.warning-password')]) !!}
					</div>
					<div class="row">	
						
						{!! Form::control('password', 6, 'password_confirmation', $errors, trans('front/register.confirm-password')) !!}
					</div>
					{!! Form::text('address', '', ['class' => 'hpet']) !!}	

					<div class="row">	
						{!! Form::submit(trans('front/form.send'), ['col-lg-12']) !!}
					</div>
					
				{!! Form::close() !!}

			</div>
		</div>
	</div><?php */?>
@stop

@section('scripts')

	<script>
		$(function() { $('.badge').popover();	});
	</script>

@stop