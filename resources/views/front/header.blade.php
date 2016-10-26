<div class="col-sm-1 col-lg-1">
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