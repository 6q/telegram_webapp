<div class="col-sm-3" id="main-sidebar">

	<div class="col-sm-2" id="menu">
		<div class="home">
			<a href="{!! URL::to('/dashboard') !!}" data-toggle="tooltip" title="{!! trans('front/header.dashboard') !!}">
				<i class="fa fa-home" aria-hidden="true" style="font-size:22px;"></i>
			</a>
		</div>
		<div class="user">
			<a href="{!! URL::to('/front_user') !!}" data-toggle="tooltip" title="{!! trans('front/header.my_account') !!}">
				<i class="fa fa-user" aria-hidden="true" style="font-size:22px;"></i>
			</a>
		
		</div>

		<div class="col_message">
			<a href="{!! URL::to('/messages') !!}" data-toggle="tooltip" title="{!! trans('front/header.logs') !!}">
				<i class="fa fa-comments" aria-hidden="true" style="font-size:22px;"></i>
			</a>
		</div>

		<div class="col_info">

			@if(Config::get('app.locale')=="en")
				<a href="{!! URL::to('/pages/detail/22') !!}" data-toggle="tooltip" title="{!! trans('front/header.info') !!}">
					<i class="fa fa-info" aria-hidden="true" style="font-size:22px;"></i>
				</a>
			@elseif(Config::get('app.locale')=="es")
				<a href="{!! URL::to('/pages/detail/21') !!}" data-toggle="tooltip" title="{!! trans('front/header.info') !!}">
					<i class="fa fa-info" aria-hidden="true" style="font-size:22px;"></i>
				</a>
			@elseif(Config::get('app.locale')=="ca")
				<a href="{!! URL::to('/pages/detail/20') !!}" data-toggle="tooltip" title="{!! trans('front/header.info') !!}">
					<i class="fa fa-info" aria-hidden="true" style="font-size:22px;"></i>
				</a>
			@endif

		</div>

		<div class="col_lock">
			<a href="{!! URL::to('/auth/logout') !!}" data-toggle="tooltip" title="{!! trans('front/header.logout') !!}">
				<i class="fa fa-lock" aria-hidden="true" style="font-size:22px;"></i>
			</a>
		</div>

		<div id="menu_bottom">
			<div class="col_help">
				<a href="#" onclick="FreshWidget.show(); return false;" data-toggle="tooltip" title="{!! trans('front/header.help') !!}">
					<i class="fa fa-question" aria-hidden="true" style="font-size:22px;"></i>
				</a>
			</div>
			<div class="languages">
				<a data-toggle="modal" data-target="#languages" title="{!! trans('front/header.change_language') !!}"><i class="fa fa-globe" aria-hidden="true" style="font-size:22px;"></i></a>
			</div>
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

			<? if (!empty($bots)) { ?>
				<ul>
					<li><a id="auto_resp" href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar">1</a></li>
					<li><a id="conntact_fbutton" href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar">2</a></li>
					<li><a id="gallery_imgs" href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar">3</a></li>
					<li><a id="chnl_btn" href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar">4</a></li>
				</ul>
			<? } else { ?>

				<ul>
					<li><a id="auto_resp">1</a></li>
					<li><a id="conntact_fbutton">2</a></li>
					<li><a id="gallery_imgs">3</a></li>
					<li><a id="chnl_btn">4</a></li>
				</ul>

			<? } ?>
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
		</div>

	</div>
</div>