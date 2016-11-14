<div class="col-sm-3" id="main-sidebar">

	<div class="col-lg-2" id="menu">
		<div class="home">
			<a href="{!! URL::to('/dashboard') !!}" data-toggle="tooltip" title="{!! trans('front/header.dashboard') !!}">{!! HTML::image('img/front/home.png') !!}</a>
		</div>
		<div class="user">
			<a href="{!! URL::to('/front_user') !!}" data-toggle="tooltip" title="{!! trans('front/header.my_account') !!}">{!! HTML::image('img/front/img1.png') !!}</a>
		<!--{!! link_to_route_img('front_user.edit', HTML::image('img/front/img1.png'), [Auth::user()->id], ['class' => '']) !!}-->
		</div>
<!--
		<div class="col_content">
			<a href="{!! URL::to('/recent_activity') !!}" data-toggle="tooltip" title="{!! trans('front/header.logs') !!}">{!! HTML::image('img/front/img2.png') !!}</a>
		</div>
-->
		<div class="col_message">
			<a href="{!! URL::to('/messages') !!}" data-toggle="tooltip" title="{!! trans('front/header.logs') !!}">{!! HTML::image('img/front/img2.png') !!}</a>
		</div>


		<div class="languages">
			<a data-toggle="modal" data-target="#languages">{!! trans('front/header.lan') !!}</a>
		</div>
		<div class="col_lock">
			<a href="{!! URL::to('/auth/logout') !!}" data-toggle="tooltip" title="{!! trans('front/header.logout') !!}">{!! HTML::image('img/front/img3.png') !!}</a>
		</div>



	</div>

	<div class="col-sm-10 col-sm-offset-2  col-lg-10 col-lg-offset-2 " id="sidebar">
		<h1 class="logo">
			<a href="{!! URL::to('/dashboard') !!}">
				{!! HTML::image('img/front/logo.png') !!}
			</a>
		</h1>

		<ul>
			<li>
				<p>
					<a href="{!! URL::to('/dashboard') !!}#my_bots">
						<span>{!! count($total_bots) !!}</span>{{ trans('front/dashboard.bots') }}
					</a>
				</p>
			</li>

			<li>
				<p>
					<a href="{!! URL::to('/dashboard') !!}#my_channels">
						<span>{!! count($total_chanels) !!}</span>{{ trans('front/dashboard.channels') }}
					</a>
				</p>
			</li>
		</ul>
<div class="col-content_tab">
		<div class="new_bot_channel">
			<div class="left">
				<a class="bot_button" href="{!! URL::to('/bot/create') !!}">
					{!! HTML::image('img/front/plus.png') !!}
				</a>
				<p>{{ trans('front/MyChannel.add_new_bot') }}</p>
			</div>

			<div class="right">
				<a class="bot_button" href="{!! URL::to('/my_channel/create') !!}">
					{!! HTML::image('img/front/plus.png') !!}
				</a>
				<p>{{ trans('front/MyChannel.add_new_channel') }}</p>
			</div>
			<div style="clear:both"></div>
            
		</div>
	<div class="chat_box" style="display:none;">
		<h6>Telegram Preview</h6>
		<div class="chat_type">
			<img src="{{URL::asset('img/front/telegram-preview.png')}}" >
		</div>
		<div class="chat_tab">
			<ul>
				<li><a id="auto_resp">1</a></li>
				<li><a id="conntact_fbutton">2</a></li>
				<li><a id="gallery_imgs">3</a></li>
				<li><a id="chnl_btn">4</a></li>
			</ul>
		</div>
	</div>
</div>
	</div>
</div>

<div id="languages" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">{!! trans('front/header.change_language') !!}</h4>
			</div>
			<div class="modal-body" style="text-align:center">
				<p><a href="{!! URL::to('/language/ca') !!}">Català</a></p>
				<p><a href="{!! URL::to('/language/es') !!}">Español</a></p>
				<p><a href="{!! URL::to('/language/en') !!}">English</a></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{!! trans('front/header.close') !!}</button>
			</div>
		</div>

	</div>
</div>