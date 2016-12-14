@extends('front.login_template')

@section('main')
<div class="row">
<div class="col-sm-12">
@if(session()->has('error'))
	@include('partials/error', ['type' => 'danger', 'message' => session('error')])
@endif	
				
</div>
<div class="col-sm-6 col-sm-offset-3">
<div class="login content">
<div class="register">
<img class="register_logo"  src="{{asset("img/login_logo.png")}}"  />
<h3>{{ trans('front/login.connection') }}</h3>
<p>{{ trans('front/login.text') }}</p>
</div>
{!! Form::open(['url' => '/', 'method' => 'post', 'role' => 'form']) !!}	
<ul>
<li>
{!! Form::control1('text', 6, 'log', $errors, trans('front/login.log')) !!}
</li>
<li>{!! Form::control1('password', 6, 'password', $errors, trans('front/login.password')) !!}</li>
<li>{!! Form::submit(trans('front/login.send')) !!}</li>
<li>
{!! link_to('auth/register', trans('front/login.registering'), ['class' => 'not_registred']) !!}

{!! link_to('password/email', trans('front/login.forget'), ['class' => 'forget_password']) !!}

</li>
</ul>
{!! Form::close() !!}

</div></div>

</div>




@stop

