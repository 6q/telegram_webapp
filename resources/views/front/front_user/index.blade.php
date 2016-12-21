@extends('front.template')
@section('main')

    <script src="https://use.fontawesome.com/e14e68e15c.js"></script>

	{!! HTML::style('css/front/simplePagination.css') !!}
	{!! HTML::script('js/front/jquery.simplePagination.js') !!}

	<div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

		@include('front.top')

		<div class="my_account">
			<h4>{!! trans('front/fornt_user.my_account') !!}</h4>
			<div class="modify_icon">
				{!! link_to_route_img('front_user.edit', "<span>".trans('front/fornt_user.modify_account')."</span>".HTML::image('img/front/modify_icon.png'), [Auth::user()->id], ['class' => '']) !!}
			</div>
		</div>

		<div class="col-lg-7">
            <div class="col-plan">
				<h2>{{ trans('front/fornt_user.my_bots') }}</h2>

					<table id="bots_content">

						<tbody>
						<?php
						if(!empty($data)){
						foreach($data as $d1 => $dv1){
						$planName = (isset($dv1['user_subscription']['plan_name']) && !empty($dv1['user_subscription']['plan_name'])) ? $dv1['user_subscription']['plan_name'] : '';

						$expDate = (isset($dv1['user_subscription']['expiry_date']) && !empty($dv1['user_subscription']['expiry_date'])) ? $dv1['user_subscription']['expiry_date'] : '';
						$price = (isset($dv1['user_subscription']['price']) && !empty($dv1['user_subscription']['price'])) ? $dv1['user_subscription']['price'] : '';

						?>
						<tr>
							<td style="text-align:left;padding:0 20px 20px">
								<h3 style="font-size:16px; font-weight:bold;">
                                	<?php
                                    	if($dv1['bot']['is_subscribe'] == 1){
										?>
                                        	<a href="javascript:void(0);"><?php echo $dv1['bot']['username'];?></a>
                                        <?php
										}
										else{
										?>
                                        	<a href="{!! URL::to('/bot/detail/'.$dv1['bot']['id']) !!}"><?php echo $dv1['bot']['username'];?></a> 
                                        <?php
										}
									?>
								</h3>
								<ul>
									<li>
                                        <b>{{ trans('front/fornt_user.cost') }}</b>: <?php echo $price; ?>€
									</li>
									<li>
                                        <b>{{ trans('front/fornt_user.automatic_renewal') }}</b>: <?php if (!empty($expDate)) {
												echo date('d/m/Y', strtotime($expDate));
											}?>
                                    </li>
									<li>
                                        <b><?php echo $planName; ?></b>
                                    </li>
								</ul>
							</td>
                            <td>
                            	<?php
                                	if($dv1['bot']['is_subscribe'] == 1){
										?>
                                        	<a href="javascript:void(0);" title=""><i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                                        <?php
									}
									else{
									?>
                                  		<a href="{!! URL::to('/bot/bot_subscription_cancel/'.$dv1['bot']['id']) !!}"
                                   onclick="return confirm('Segur que vols cancelar la subscripció? No hi ha volta enrere');" data-toggle="tooltip" title="" data-original-title="Cancelar Subscripció"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>  	
                                    <?php
									}
								?>
                                <a  href="{!! URL::to('/bot/bot_delete/'.$dv1['bot']['id']) !!}"
                                   onclick="return confirm('Segur que el vols eliminar? No hi ha volta enrere.');" data-toggle="tooltip" title="" data-original-title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
						</tr>
						<?php
						}
						}
						else{
						?>
						<tr>
							<td>
								<ul>
									<li>{{ trans('front/fornt_user.no_record_found') }}</li>
								</ul>
							</td>
						</tr>
						<?php
						}
						?>
						</tbody>
					</table>
					<div id="bots_contentNavPosition"></div>
                </div>
                <div class="col-plan">
                    <h2>{{ trans('front/fornt_user.my_channel') }}</h2>

                    <table id="channels_content">
						<tbody>
						<?php
						if(!empty($chanel_data)){
						foreach($chanel_data as $ck1 => $cv1){
						?>
						<tr>
                            <td style="text-align:left;padding:0 20px 20px">
                                <h3 style="font-size:16px; font-weight:bold;">
                                    <?php echo $cv1['channel']['name'];?>
                                </h3>
								<ul>
									<li>
                                        <b>{{ trans('front/fornt_user.cost') }}</b>: <?php echo $cv1['user_subscription']['price'];?>€
									</li>
									<li>
                                        <b>{{ trans('front/fornt_user.automatic_renewal') }}</b>:<?php echo date('d/m/Y', strtotime($cv1['user_subscription']['expiry_date']));?>
                                    </li>
								</ul>
							</td>
                            <td>
                            	<?php
                                	if($cv1['channel']['is_subscribe'] == 1){
										?>
                                        	<a href="javascript:void(0);"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                                        <?php
									}
									else{
										?>
                                        	<a href="{!! URL::to('/my_channel/channel_subscription_cancel/'.$cv1['channel']['id']) !!}" onclick="return confirm('Segur que vols cancelar la subscripció? No hi ha volta enrere');" data-toggle="tooltip" title="" data-original-title="Cancelar Subscripció"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>      	
                                        <?php	
									}
								?>
                                <a href="{!! URL::to('/my_channel/channel_delete/'.$cv1['channel']['id']) !!}"
                                   onclick="return confirm('Segur que el vols eliminar? No hi ha volta enrere.');" data-toggle="tooltip" title="" data-original-title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
						</tr>
						<?php
						}
						}
						else{
						?>
						<tr>
							<td>
								<ul>
									<li>{{ trans('front/fornt_user.no_record_found') }}</li>
								</ul>
							</td>
						</tr>
						<?php
						}
						?>
						</tbody>
					</table>
					<div id="channel_contentNavPosition"></div>

			</div>
		</div>

		<div class="col-lg-5">


			<div class="col-plan">
				<h2>{{ trans('front/fornt_user.billing_transactions') }}</h2>
				<table id="bot_billing">
					<thead>
					<tr>
						<th>{{ trans('front/fornt_user.transaction_date') }}</th>
						<th>{{ trans('front/fornt_user.type') }} </th>
						<th>{{ trans('front/fornt_user.amount') }}</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if(!empty($transactions)){
					foreach($transactions as $t1 => $t2){
					?>
					<tr>
						<td><?php echo $t2->created_at;?></td>
						<td>
                        <?php
                            if ($t2->types=="bot") {
                                echo trans('front/dashboard.bot');
                            } else echo trans('front/dashboard.channel');
                            ?>
                        </td>
						<td><?php echo $t2->amount;?>€</td>
					</tr>
					<?php
					}
					}
					else{
					?>
					<tr>
						<td colspan="4">{{ trans('front/fornt_user.no_record') }}</td>
					</tr>
					<?php
					}
					?>
					</tbody>
				</table>
				<div id="bot_billingNavPosition"></div>
			</div>
        </div>
	</div>


	<script>
		jQuery(function ($) {
			var pageParts = $("#bots_content tbody tr");
			var numPages = pageParts.length;
			var perPage = 5;
			pageParts.slice(perPage).hide();

			$("#bots_contentNavPosition").pagination({
				items: numPages,
				itemsOnPage: perPage,
				cssStyle: "light-theme",
				onPageClick: function (pageNum) {
					var start = perPage * (pageNum - 1);
					var end = start + perPage;
					pageParts.hide().slice(start, end).show();
				}
			});
		});

		jQuery(function ($) {
			var pageParts = $("#channel_content tbody tr");
			var numPages = pageParts.length;
			var perPage = 5;
			pageParts.slice(perPage).hide();

			$("#channel_contentNavPosition").pagination({
				items: numPages,
				itemsOnPage: perPage,
				cssStyle: "light-theme",
				onPageClick: function (pageNum) {
					var start = perPage * (pageNum - 1);
					var end = start + perPage;
					pageParts.hide().slice(start, end).show();
				}
			});
		});


		jQuery(function ($) {
			var pageParts = $("#bot_plan_sub tbody tr");
			var numPages = pageParts.length;
			var perPage = 5;
			pageParts.slice(perPage).hide();

			$("#bot_plan_subNavPosition").pagination({
				items: numPages,
				itemsOnPage: perPage,
				cssStyle: "light-theme",
				onPageClick: function (pageNum) {
					var start = perPage * (pageNum - 1);
					var end = start + perPage;
					pageParts.hide().slice(start, end).show();
				}
			});
		});

		jQuery(function ($) {
			var pageParts = $("#bot_billing tbody tr");
			var numPages = pageParts.length;
			var perPage = 10;
			pageParts.slice(perPage).hide();

			$("#bot_billingNavPosition").pagination({
				items: numPages,
				itemsOnPage: perPage,
				cssStyle: "light-theme",
				onPageClick: function (pageNum) {
					var start = perPage * (pageNum - 1);
					var end = start + perPage;
					pageParts.hide().slice(start, end).show();
				}
			});
		});

		jQuery(function ($) {
			var pageParts = $("#channel_plans tbody tr");
			var numPages = pageParts.length;
			var perPage = 5;
			pageParts.slice(perPage).hide();

			$("#channel_plansNavPosition").pagination({
				items: numPages,
				itemsOnPage: perPage,
				cssStyle: "light-theme",
				onPageClick: function (pageNum) {
					var start = perPage * (pageNum - 1);
					var end = start + perPage;
					pageParts.hide().slice(start, end).show();
				}
			});
		});



	</script>

@stop