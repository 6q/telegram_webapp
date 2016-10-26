<div class="col-sm-3" id="main-sidebar">

	<div class="col-lg-2" id="menu">
		<div class="home">
			<a href="{!! URL::to('/dashboard') !!}" data-toggle="tooltip" title="{!! trans('front/header.dashboard') !!}">{!! HTML::image('img/front/home.png') !!}</a>
		</div>
		<div class="user">
			<a href="{!! URL::to('/front_user') !!}" data-toggle="tooltip" title="{!! trans('front/header.my_account') !!}">{!! HTML::image('img/front/img1.png') !!}</a>
		<!--{!! link_to_route_img('front_user.edit', HTML::image('img/front/img1.png'), [Auth::user()->id], ['class' => '']) !!}-->
		</div>

		<div class="col_content">
			<a href="{!! URL::to('/recent_activity') !!}" data-toggle="tooltip" title="{!! trans('front/header.logs') !!}">{!! HTML::image('img/front/img2.png') !!}</a>
		</div>

		<div class="col_message">
			<a href="{!! URL::to('/messages') !!}" data-toggle="tooltip" title="{!! trans('front/header.messages') !!}">{!! HTML::image('img/front/message.png') !!}</a>
		</div>

	<!--<div class="col_lock">
			<a href="{!! URL::to('/front_user/change_password/'.Auth::user()->id) !!}" data-toggle="tooltip" title="{!! trans('front/header.password_change') !!}">{!! HTML::image('img/password.png') !!}</a>
		</div>
		-->

		<div class="col_lock">
			<a href="{!! URL::to('/auth/logout') !!}" data-toggle="tooltip" title="{!! trans('front/header.logout') !!}">{!! HTML::image('img/front/img3.png') !!}</a>
		</div>


		<script>
			function changeLang(){
				var url = $('#select_lang').val();
				window.location.href = url;
			}
		</script>
	</div>

	<div class="col-sm-10 col-sm-offset-2  col-lg-10 col-lg-offset-2 " id="sidebar">
		<h1 class="logo">
			<a href="{!! URL::to('/dashboard') !!}">
				{!! HTML::image('img/front/logo.png') !!}
			</a>
		</h1>
		<?php
		$currLang = Config::get('app.locale');
		$select1 = '';
		if($currLang == 'en'){
			$select1 = 'selected="selected"';
		}

		$select2 = '';
		if($currLang == 'ca'){
			$select2 = 'selected="selected"';
		}

		$select3 = '';
		if($currLang == 'es'){
			$select3 = 'selected="selected"';
		}
		?>
		<select name="select_lang" onchange="changeLang();" id="select_lang">
			<option <?php echo $select1;?> value="{!! URL::to('/language/en') !!}">{!! trans('front/header.english') !!}</option>
			<option <?php echo $select2;?> value="{!! URL::to('/language/ca') !!}">{!! trans('front/header.catalan') !!}</option>
			<option <?php echo $select3;?> value="{!! URL::to('/language/es') !!}">{!! trans('front/header.spanish') !!}</option>
		</select>
		<ul>
			<li>
				<p>
					<a href="{!! URL::to('/front_user') !!}"><span>{!! count($total_bots) !!}</span>{{ trans('front/dashboard.bots') }}</a>
				</p>
			</li>

			<li>
				<p><a href="{!! URL::to('/front_user') !!}"><span>{!! count($total_chanels) !!}</span>{{ trans('front/dashboard.channels') }}</a></p>
			</li>
		</ul>

		<div class="new_bot_channel">
			<a class="bot_button" href="{!! URL::to('/my_channel/create') !!}">{!! HTML::image('img/front/plus.png') !!}</a>
			<p>{{ trans('front/MyChannel.add_new_bot') }}</p>
		</div>

		<div class="col-summary">
			<div class="summary_content">
				<h4>{{ trans('front/dashboard.bots') }}</h4>
				<?php
				if(isset($total_bots) && !empty($total_bots)){
				foreach($total_bots as $tbk1 => $tbv1){
				?>
				<p><a href="{!! URL::to('/bot/detail/'.$tbv1->id) !!}"><?php echo $tbv1->username;?></a></p>
				<?php
				}
				}
				?>
			</div>

			<div class="summary_content"><h4>{{ trans('front/dashboard.channels') }}</h4>
				<?php
				if(isset($total_chanels) && !empty($total_chanels)){
				foreach($total_chanels as $tck1 => $tcv1){
				?>
				<p><a href="{!! URL::to('/my_channel/detail/'.$tcv1->id) !!}"><?php echo $tcv1->name;?></a></p>
				<?php
				}
				}
				?>
			</div>
		</div>
	</div>

</div>