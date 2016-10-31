<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{{ trans('front/site.title') }}</title>
		
		@yield('head')
		
		{!! HTML::style('css/front/style.css') !!}
		{!! HTML::style('css/front/bootstrap.css') !!}
		{!! HTML::style('css/front/datepicker.css') !!}
		
		{!! HTML::script('js/front/jquery1.12.4.js') !!}
		{!! HTML::script('js/front/bootstrap-datepicker.js') !!}
		{!! HTML::script('js/front/bootstrap.js') !!}
	</head>
	
	<body>
		<section class="col_top">
			<div class="container_1">
				@if(session()->has('ok'))
					@include('partials/error', ['type' => 'success', 'message' => session('ok')])
				@endif	
				@if(isset($info))
					@include('partials/error', ['type' => 'info', 'message' => $info])
				@endif
					@include('front.header')
				@yield('main')
				
				

				
				@yield('content')


			</div>
			<footer>
				<a href="">Contactar</a> | &copy; Citymes
			</footer>
		</section>
	</body>
</html>
   
<script>
  jQuery(document).ready(function () {
        //alert('hello');
        jQuery(function () {
            jQuery(".datepicker").datepicker();
        });
    });
  jQuery(document).ready(function(){
	  jQuery('[data-toggle="tooltip"]').tooltip({placement: "bottomreat el"});
	});
</script>
@yield('scripts')
























































<div class="container">
    
		
<?php /*?>	<header role="banner">

		<div class="brand">{{ trans('front/site.title') }}</div>
		<div class="address-bar">{{ trans('front/site.sub-title') }}</div>
		<div id="flags" class="text-center"></div>
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.html">{{ trans('front/site.title') }}</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						
						
						@if(Request::is('auth/register'))
							<li class="active">
								{!! link_to('auth/register', trans('front/site.register')) !!}
							</li>
						@elseif(Request::is('password/email'))
							<li class="active">
								{!! link_to('password/email', trans('front/site.forget-password')) !!}
							</li>
						@else
							@if(session('statut') == 'visitor')
						     <li class="active">
								{!! link_to('auth/register', trans('front/site.register')) !!}
							</li>
							@else
								@if(session('statut') == 'admin')
									<li>
										{!! link_to_route('admin', trans('front/site.administration')) !!}
									</li>
								@elseif(session('statut') == 'user') 
									<li>
										{!! link_to('dashboard', trans('front/site.dashboard')) !!}
									</li>
								@endif
								<li>
									{!! link_to('auth/logout', trans('front/site.logout')) !!}
								</li>
							@endif
						@endif
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"><img width="32" height="32" alt="{{ session('locale') }}"  src="{!! asset('img/' . session('locale') . '-flag.png') !!}" />&nbsp; <b class="caret"></b></a>
							<ul class="dropdown-menu">
							@foreach ( config('app.languages') as $user)
								@if($user !== config('app.locale'))
									<li><a href="{!! url('language') !!}/{{ $user }}"><img width="32" height="32" alt="{{ $user }}" src="{!! asset('img/' . $user . '-flag.png') !!}"></a></li>
								@endif
							@endforeach
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		@yield('header')	
	</header>

	<main role="main" class="container">
		@if(session()->has('ok'))
			@include('partials/error', ['type' => 'success', 'message' => session('ok')])
		@endif	
		@if(isset($info))
			@include('partials/error', ['type' => 'info', 'message' => $info])
		@endif
		@yield('main')
	</main>
    
	<footer role="contentinfo">
		 @yield('footer')
		<p class="text-center"><small>Copyright &copy; Citymess</small></p>
	</footer><?php */?>
	</div>
