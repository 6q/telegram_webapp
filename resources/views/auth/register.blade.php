@extends('front.login_template')

@section('main')


<div class="row">

<div class="col-sm-6 col-sm-offset-3">
<div class="sigup content">
<div class="register">
<img class="register_logo"  src="{{asset("img/login_logo.png")}}"  />
<h3>{{ trans('front/register.title') }}</h3>
<p>{{ trans('front/register.sub_text') }}</p>

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


@stop

@section('scripts')

	<script>
		$(function() { $('.badge').popover();	});
	</script>

@stop