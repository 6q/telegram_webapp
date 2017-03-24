@extends('front.template')
@section('main')

    {!! HTML::style('css/front/simplePagination.css') !!}
    {!! HTML::script('js/front/jquery.simplePagination.js') !!}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js"></script>
    <script>
		$(document).ready(function () {

		});

		function warnBeforeRedirect(linkURL) {
			swal({
				html: true,
				title: "{{ trans('front/bots.are_you_sure') }}",
				text: "{{ trans('front/bots.you_are_going_to_delete') }}",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "{{ trans('front/bots.cancel') }}",
			}, function () {
				// Redirect the user
				window.location.href = linkURL;
			});
		}

    </script>
    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

        @include('front.top')
        <div id="bot_statistics">

            <script type="text/javascript">
				google.charts.load('current', {'packages': ['corechart']});

				function drawChart(data_arr) {
					var data = google.visualization.arrayToDataTable(data_arr);

					var options_fullStacked = {
						title: '',
						chartArea: {left: 0, top: 0, bottom: 10, width: "100%", height: "100%"},
						curveType: 'function',
						tooltip: {
							isHtml: true
						},
						vAxis: {
							viewWindow: {
								min: 0
							}
						},
						lineWidth: 5,
						pointSize: 10,
						colors: ['#00B09E'],
						legend: {position: 'bottom'}
					};


					var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
					chart.draw(data, options_fullStacked);
				}
            </script>

            <div class="status">

                {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'status_dropdown']) !!}

                <div class="week">
                    <select id="chart_details" onchange="getCharts()">
                    <!--<option value="recieved_messages" selected>{{ trans('front/dashboard.recieved_messages') }}</option>-->
                        <option value="send_messages">{{ trans('front/dashboard.send_messages') }} </option>
                        <option value="active_users">{{ trans('front/dashboard.active_users') }}</option>
                    </select>
                </div>

                <div class="week">
                    <select id="chart_time" onchange="getCharts()">
                        <option value="10_days" selected>{{ trans('front/dashboard.ten_days') }}</option>
                        <option value="30_days">{{ trans('front/dashboard.thirty_days') }}</option>
                        <option value="90_days">{{ trans('front/dashboard.ninety_days') }}</option>
                    </select>
                </div>

                <div class="week">
                    <select id="chart_bots" onchange="getCharts()" style="display:none;">
                        <option value="all_bots" selected>{{ trans('front/dashboard.all_bot') }}</option>
						<?php
						if(isset($total_bots) && !empty($total_bots)){
						foreach($total_bots as $tbk1 => $tbv1){
						$select = '';
						if ($bots[0]->id == $tbv1->id) {
							$select = 'selected="selected"';
						}
						?>
                        <option
							<?php echo $select; ?> value="<?php echo $tbv1->id; ?>"><?php echo $tbv1->username;?></option>
						<?php
						}
						}
						?>
                    </select>
                </div>

                {!! Form::close() !!}

            </div>
            <div class="graph botDetail">
                <img src="{{URL::asset('img/balls.gif')}}" class="loading_img">
                <div id="chart_div" style="height: 300px;"></div>
            </div>
        </div>

        <div class="my_account col-user">
            <div class="col-lg-2">
                <img src="{{URL::asset('img/front/bot.png')}}">
            </div>
            <div class="col-lg-10">
                <ul>
                    <li class="titol_bot"><h4>{!! $bots[0]->username !!} <a
                                    href="https://telegram.me/{!! $bots[0]->username !!}" target="_blank"
                                    title="Telegram Bot"><i class="fa fa-external-link" aria-hidden="true"></i></a></h4>
						<?php if (isset($planDetails[0]->name) && !empty($planDetails[0]->name)) {
							echo $planDetails[0]->name;
						} ?>
                    </li>
                    <li class="token_bot"><b>{{ trans('front/bots.bot_token') }}:</b> {!! $bots[0]->bot_token !!}</li>
                </ul>
                <div class="send-edit">
                    <a href="javascript:void(0);" class="btn btn-primary"
                       onclick="mypopup_botfunction('<?php echo $bots[0]->id;?>');"><i class="fa fa-paper-plane"
                                                                                       aria-hidden="true"></i> {{ trans('front/dashboard.send_message') }}
                    </a>
                    <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}" class="btn btn-warning"><i
                                class="fa fa-pencil" aria-hidden="true"></i> {!! trans('front/dashboard.edit_bot') !!}
                    </a>
                <!--<a href="{!! URL::to('/command/create/'.$bots[0]->id) !!}" class="btn btn-success"><i class="fa fa-star" aria-hidden="true"></i> {!! trans('front/dashboard.create_command') !!}</a>-->
                </div>
                <ul class="nav nav-tabs nav-pills pills_bot" role="tablist">
                    <li class="active">
                        <a data-toggle="tab" href="#bot_main_buttons"><i class="fa fa-bars"
                                                                         aria-hidden="true"></i> {!! trans('front/bots.buttons') !!}
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#bot_users"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> {!! trans('front/bots.users') !!}
                        </a>
                    </li>
                    <li><a data-toggle="tab" href="#bot_messages"><i class="fa fa-line-chart" aria-hidden="true"></i>
                            Log</a></li>
                </ul>

            </div>
        </div>


        <div class="col-lg-12 tab-content">
            <div id="bot_main_buttons" class="tab-pane fade in active">
                <div class="col-plan col-lg-6">
                    <h2 class="h2_information">
						<?php echo $bots[0]->autoresponse; ?>
                        <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip"
                           data-original-title="Editar el nom" class="editar_boto">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a></h2>
                    <div id="a_autoResp">
                        <div id="botAutoresponse">

                            <div colspan="5" class="telebuttons">
                                <ul id="sortable_botAutoresponse">
									<?php
									if(!empty($autoResponse)){
									foreach($autoResponse as $d2 => $v2){
									?>
                                    <li data-itemId="{{ $v2->id }}">
                                        <a class="telebutton"
                                           href="{!! URL::to('/command/autoresponse_edit/'.$v2->id) !!}"
                                           data-toggle="tooltip" data-original-title="Editar">
											<?php echo $v2->submenu_heading_text;?>
                                        </a>
                                        <i class="danger fa fa-arrows" aria-hidden="true"></i>
                                    </li>
									<?php
									}
									}
									?>
                                </ul>
                            </div>

                            <div class="paginacio">
                                <div class="botonou">
									<?php
									if(isset($planDetails[0]->autoresponses) && !empty($planDetails[0]->autoresponses) && $total_pages < $planDetails[0]->autoresponses){
									?>
                                    <a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=autoresponses') !!}"
                                       class="btn btn-success">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>

									<?php
									}
									else{
									?>
                                    <a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=autoresponses&act=1') !!}"
                                       class="btn btn-success">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
									<?php
									}
									?>
                                </div>
								<?php if (isset($planDetails[0]->autoresponses) && !empty($planDetails[0]->autoresponses) && $planDetails[0]->autoresponses < 999) {
									echo '<div class="info_test"> ' . $total_pages . ' / ' . $planDetails[0]->autoresponses . ' </div>';
								} ?>

                                <div id="botAutoresponseNavPosition" class="light-theme simple-pagination">
                                    <input type="text" class="tablesearch"
                                           placeholder="{!! trans('front/dashboard.search') !!}..."
                                           id="searchAutoresponse">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="col-plan col-lg-6">
                    <h2 class="h2_contact"><?php echo $bots[0]->contact_form; ?>
                        <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip"
                           data-original-title="Editar el nom" class="editar_boto">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                    </h2>

                    <div id="c_autoResp">
                        <div id="botContactForm">
                            <div class="telebuttons">
                                <ul id="sortable_botContactForm">
									<?php
									if(!empty($contactForm)){
									foreach($contactForm as $d3 => $v3){
									?>
                                    <li>
                                        <a class="telebutton"
                                           href="{!! URL::to('/command/contactform_edit/'.$v3->id) !!}"
                                           data-toggle="tooltip" data-original-title="Editar">
											<?php echo $v3->submenu_heading_text;?>
                                        </a>
                                        <i class="fa fa-arrows danger" aria-hidden="true"></i>
                                    </li>
									<?php
									}
									}
									?>
                                </ul>
                            </div>

                            <div class="paginacio">
                                <div class="botonou">
									<?php
									if(isset($planDetails[0]->contact_forms) && !empty($planDetails[0]->contact_forms) && $total_pages_contatc_form < $planDetails[0]->contact_forms)
									{
									?>
                                    <a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=contactforms') !!}"
                                       class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
									<?php
									}
									else{
									?>
                                    <a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=contactforms&act=1') !!}"
                                       class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
									<?php
									}
									?>
                                </div>

								<?php if (isset($planDetails[0]->contact_forms) && !empty($planDetails[0]->contact_forms) && $planDetails[0]->contact_forms < 999) {
									echo '<div class="info_test"> ' . $total_pages_contatc_form . ' / ' . $planDetails[0]->contact_forms . ' </div>';
								} ?>

                                <div id="botContactFormNavPosition" class="light-theme simple-pagination">
                                    <input type="text" class="tablesearch"
                                           placeholder="{!! trans('front/dashboard.search') !!}..."
                                           id="searchContactForm">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:both"></div>

                <div class="col-plan col-lg-6">
                    <h2 class="h2_photos">
						<?php echo $bots[0]->galleries; ?>
                        <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip"
                           data-original-title="Editar el nom" class="editar_boto">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a>
                    </h2>
                    <div id="g_autoResp">
                        <div id="botGallery">
                            <div class="telebuttons">
                                <ul id="sortable_botGallery">
									<?php
									if(!empty($gallery)){
									foreach($gallery as $d4 => $v4){
									?>
                                    <li>
                                        <a class="telebutton" href="{!! URL::to('/command/gallery_edit/'.$v4->id) !!}"
                                           data-toggle="tooltip" data-original-title="Editar">
											<?php echo $v4->gallery_submenu_heading_text;?>
                                        </a>
                                        <i class="fa fa-arrows danger" aria-hidden="true"></i>
                                    </li>

									<?php
									}
									}
									?>
                                </ul>
                            </div>
                            <div class="paginacio">
                                <div class="botonou">
									<?php if(isset($planDetails[0]->image_gallery) && !empty($planDetails[0]->image_gallery) && $total_pages_gallery < $planDetails[0]->image_gallery){
									?>
                                    <a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=galleries') !!}"
                                       class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
									<?php
									}
									else{
									?>
                                    <a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=galleries&act=1') !!}"
                                       class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
									<?php
									}
									?>
                                </div>

								<?php if (isset($planDetails[0]->image_gallery) && !empty($planDetails[0]->image_gallery) && $planDetails[0]->image_gallery < 999) {
									echo '<div class="info_test"> ' . $total_pages_gallery . ' / ' . $planDetails[0]->image_gallery . ' </div>';
								} ?>

                                <div id="botGalleryNavPosition" class="light-theme simple-pagination">
                                    <input type="text" class="tablesearch"
                                           placeholder="{!! trans('front/dashboard.search') !!}..." id="searchGallery">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-plan col-lg-6">
                    <h2 class="h2_channels"><?php echo $bots[0]->channels; ?>
                        <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip"
                           data-original-title="Editar el nom" class="editar_boto">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </a></h2>

                    <div id="ch_autoResp">
                        <div id="botChannels">
                            <div class="telebuttons">
                                <ul id="sortable_botChannels">
									<?php
									if(!empty($chanels)){
									foreach($chanels as $d5 => $v5){
									?>
                                    <li>
                                        <a class="telebutton" href="{!! URL::to('/command/chanel_edit/'.$v5->id) !!}"
                                           data-toggle="tooltip" data-original-title="Editar">
											<?php echo $v5->chanel_submenu_heading_text;?>
                                        </a>
                                        <a class="danger"
                                           onclick="return warnBeforeRedirect('{!! URL::to('/command/chanel_delete/'.$v5->type_id.'/'.$v5->id) !!}')"><i
                                                    class="fa fa-arrows" aria-hidden="true"></i></a>
                                    </li>

									<?php
									}
									}
									?>
                                </ul>
                            </div>
                            <div class="paginacio">
                                <div class="botonou">
                                    <a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=chanel') !!}"
                                       class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>

                                <div id="botChannelsNavPosition" class="light-theme simple-pagination">
                                    <input type="text" class="tablesearch"
                                           placeholder="{!! trans('front/dashboard.search') !!}..." id="searchChannels">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div style="clear:both"></div>

                <div class="col-plan col-lg-12">
                    <h2 class="h2_commands">{!! trans('front/bots.bot_command') !!} ({{ $bots[0]->comanda }})</h2>

                    <div id="bt_commands_BC">
                        <div id="botChannels">
                            <div class="telebuttons commands">
                                <ul id="sortable_botCommands">
									<?php
									if(!empty($botCommands)){
									foreach($botCommands as $d6 => $v6){
									?>
                                    <li>
                                        <a class="telebutton" href="{!! URL::to('/bot/bot_command_edit/'.$v6->id) !!}"
                                           title="Editar">
											<?php echo $v6->title;?>
											<?php if (isset($v6->webservice_type) && !empty($v6->webservice_url)) echo '<i class="fa fa-plug" aria-hidden="true" title="Webservice" style="color:lightgrey" ></i>'; ?>
                                        </a>
                                    </li>
									<?php
									}
									}
									?>
                                </ul>

                            </div>
                            <div class="paginacio">
                                <div class="botonou">
                                    <a href="{!! URL::to('bot/bot_command/'.$bots[0]->id) !!}"
                                       class="btn btn-success <?php if (isset($planDetails[0]->bot_commands) && !empty($planDetails[0]->bot_commands) && $total_bot_commands == $planDetails[0]->bot_commands) echo "disabled"; ?>"><i
                                                class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>


								<?php if (isset($planDetails[0]->bot_commands) && !empty($planDetails[0]->bot_commands) && $planDetails[0]->bot_commands < 999) {
									echo '<div class="info_test"> ' . $total_bot_commands . ' / ' . $planDetails[0]->bot_commands . ' </div>';
								} ?>

                                <div id="botCommandNavPosition" class="light-theme simple-pagination">
                                    <input type="text" class="tablesearch"
                                           placeholder="{!! trans('front/dashboard.search') !!}..." id="searchCommands">

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-plan tab-pane fade" id="bot_users">
                <p>
                    <style>
                        input.datepicker.entre {
                            position: relative;
                            height: auto;
                            width: 100px;
                            opacity: 1;
                            margin: 0 10px;
                        }
                    </style>
                    de: <input class="datepicker entre" type="text" id="from_user" size="10">
                    a: <input class="datepicker entre" type="text" id="to_user" size="10">

                    <a class="btn btn-primary" id="user_excel"
                       href="{!! URL::to('/bot/download_user/'.$bots[0]->id) !!}">{{ trans('front/bots.user_download') }}</a>
                    <a class="btn btn-primary" id="user_pdf"
                       href="{!! URL::to('/bot/pdf_download/'.$bots[0]->id) !!}">{{ trans('front/bots.pdf_user_download') }}</a>

                    <script>
						$("#user_excel").click(function () {
							var from = $('#from_user').val().replace(/\//g, "-");
							;
							var to = $('#to_user').val().replace(/\//g, "-");
							;
							$('#user_excel').attr("href", "{!! URL::to('/bot/download_user/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
							$('#user_pdf').attr("href", "{!! URL::to('/bot/pdf_download/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
						});
						$("#user_pdf").click(function () {
							var from = $('#from_user').val().replace(/\//g, "-");
							;
							var to = $('#to_user').val().replace(/\//g, "-");
							;
							$('#user_excel').attr("href", "{!! URL::to('/bot/download_user/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
							$('#user_pdf').attr("href", "{!! URL::to('/bot/pdf_download/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
						});
                    </script>
                </p>
                <h2>{{ trans('front/bots.active_user') }}</h2>
                <div id="u_autoResp">
                    <table id="activeUser">
                        <thead>
                        <tr>
                            <th>{{ trans('front/bots.first_name') }}</th>
                            <th>{{ trans('front/bots.last_name') }} </th>
                            <th>{{ trans('front/bots.created_at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						if(!empty($activeUser)){
						foreach($activeUser as $auk1 => $auv1){
						?>
                        <tr>
                            <td><?php echo $auv1->first_name;?></td>
                            <td><?php echo $auv1->last_name;?></td>
                            <td>
								<?php
								$date = new DateTime($auv1->created_at);
								echo $date->format('d-m-Y') . "<br>" . $date->format('H:i:s');
								?>
                            </td>
                        </tr>
						<?php
						}
						}
						else{
						?>
                        <tr>
                            <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                        </tr>
						<?php
						}
						?>
                        <tr>
                            <td colspan="5" class="paginacio">
                                <div id="activeUserNavPosition" class="light-theme simple-pagination">
									<?php
									$lastpage_u = 0;
									if ($total_pages_activeUser > 0) {
										$prev_u = $page - 1;
										$next_u = $page + 1;
										$lastpage_u = ceil($total_pages_activeUser / $limitUser);
										$lpm1_u = $lastpage_u - 1;
									}

									$pagination_u = '';
									if ($lastpage_u >= 1) {
										$pagination_u = '<ul>';

										if ($page > 1)
											$pagination_u .= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationU(' . "'0','first','" . $bots[0]->id . "'" . ')")">&lt;</a>';
										else
											$pagination_u .= '<li class="disabled"><span class="current prev">&lt;</span></li>';


										if ($lastpage_u < 7 + ($adjacents * 2)) {
											for ($counter_u = 1; $counter_u <= $lastpage_u; $counter_u++) {
												if ($counter_u == $page)
													$pagination_u .= '<li class="active"><span class="current">' . $counter_u . '</span></li>';
												else
													$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $counter_u . "','" . $counter_u . "_no', '" . $bots[0]->id . "'" . ')">' . $counter_u . '</a></li>';
											}
										} elseif ($lastpage_u > 5 + ($adjacents * 2)) {
											if ($page < 1 + ($adjacents * 2)) {
												for ($counter_u = 1; $counter_u < 4 + ($adjacents * 2); $counter_u++) {
													if ($counter_u == $page)
														$pagination_u .= '<li class="active"><span class="current">' . $counter_u . '</span>';
													else
														$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $counter_u . "','" . $counter_u . "_no', '" . $bots[0]->id . "'" . ')">' . $counter_u . '</a></li>';
												}
												$pagination_u .= '<li><span class="ellipse clickable">...</span></li>';
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $lpm1_u . "','" . $lpm1_u . "_no', '" . $bots[0]->id . "'" . ')">' . $lpm1_u . '</a></li>';
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $lastpage_u . "','" . $lastpage_u . "_no', '" . $bots[0]->id . "'" . ')">' . $lastpage_u . '</a></li>';
											} elseif ($lastpage_u - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'1','1_no', '" . $bots[0]->id . "'" . ')">1</a></li>';
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'2','2_no', '" . $bots[0]->id . "'" . ')">2</a></li>';
												$pagination_u .= '<li><span class="ellipse clickable">...</span></li>';
												for ($counter_u = $page - $adjacents; $counter_u <= $page + $adjacents; $counter_u++) {
													if ($counter_u == $page)
														$pagination_u .= '<li class="active"><span class="current">' . $counter_u . '</span>';
													else
														$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $counter_u . "','" . $counter_u . "_no', '" . $bots[0]->id . "'" . ')">' . $counter_u . '</a></li>';
												}
												$pagination_u .= '<li><span class="ellipse clickable">...</span></li>';
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $lpm1_u . "','" . $lpm1_u . "_no','" . $bots[0]->id . "'" . ')">' . $lpm1_u . '</a></li>';
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $lastpage_u . "','" . $lastpage_u . "_no', '" . $bots[0]->id . "'" . ')">' . $lastpage_u . '</a></li>';
											} else {
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'1','1_no','" . $bots[0]->id . "'" . ')">1</a></li>';
												$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'2','2_no', '" . $bots[0]->id . "'" . ')">2</a></li>';
												$pagination_u .= '<li><span class="ellipse clickable">...</span></li>';
												for ($counter_u = $lastpage_u - (2 + ($adjacents * 2)); $counter_u <= $lastpage_u; $counter_u++) {
													if ($counter_u == $page)
														$pagination_u .= '<li class="active"><span class="current">' . $counter_u . '</span>';
													else
														$pagination_u .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $counter_u . "','" . $counter_u . "_no', '" . $bots[0]->id . "'" . ')">' . $counter_u . '</a></li>';
												}
											}
										}

										if ($page < $counter_u - 1) {
											$pagination_u .= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationU(' . "'" . $next_u . "','" . $next_u . "_no', '" . $bots[0]->id . "'" . ')">&gt;</a></li>';
										} else {
											$pagination_u .= '<li class="active"><span class="current next">&gt;</span>';
										}

										$pagination_u .= '</ul>';
									}

									echo $pagination_u;
									?>
                                    <img id="imgLoadAjaxU" src="{{URL::asset('img/balls.gif')}}"
                                         class="loading_ajax_img" style="display:none;">
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <div class="col-plan tab-pane fade" id="bot_messages">
                <p>

                    de: <input class="datepicker entre" type="text" id="from_log" size="10">
                    a: <input class="datepicker entre" type="text" id="to_log" size="10">

                    <a class="btn btn-primary" id="log_excel"
                       href="{!! URL::to('/bot/download_log/'.$bots[0]->id) !!}">{{ trans('front/bots.log_download') }}</a>
                    <a class="btn btn-primary" id="log_pdf"
                       href="{!! URL::to('/bot/log_pdf_download/'.$bots[0]->id) !!}">{{ trans('front/bots.pdf_log_download') }}</a>

                    <script>
						$("#log_excel").click(function () {
							var from = $('#from_log').val().replace(/\//g, "-");
							;
							var to = $('#to_log').val().replace(/\//g, "-");
							;
							$('#log_excel').attr("href", "{!! URL::to('/bot/download_log/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
							$('#log_pdf').attr("href", "{!! URL::to('/bot/log_pdf_download/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
						});
						$("#log_pdf").click(function () {
							var from = $('#from_log').val().replace(/\//g, "-");
							;
							var to = $('#to_log').val().replace(/\//g, "-");
							;
							$('#log_excel').attr("href", "{!! URL::to('/bot/download_log/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
							$('#log_pdf').attr("href", "{!! URL::to('/bot/log_pdf_download/'.$bots[0]->id) !!}" + "/" + from + "/" + to);
						});
                    </script>

                </p>
                <h2>{{ trans('front/bots.messages_activity') }}</h2>
                <div id="m_autoResp">
                    <table id="message_activity">
                        <thead>
                        <tr>
                            <th width="20%">{{ trans('front/bots.user') }}</th>
                            <th width="30%">{{ trans('front/bots.message') }} </th>
                            <th width="30%">{{ trans('front/bots.replay_message') }}</th>
                            <th width="20%">{{ trans('front/bots.date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						if(!empty($botMessages)){
						foreach($botMessages as $bmk1 => $bmv1){
						?>
                        <tr>
                            <td><?php echo $bmv1->first_name . ' ' . $bmv1->last_name;?></td>
                            <td>
								<?php

								if(strlen($bmv1->text) < 1000 && file_exists(public_path() . '/uploads/' . $bmv1->text) && !empty($bmv1->text)){
								?>
                                <button href="#" id="link<?php echo $bmv1->id;?>" data-toggle="modal"
                                        data-target="#myModal" src="/uploads/<?=$bmv1->text?>" class="imatge">
                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                </button>

								<?php
								}
								else {
									echo strlen($bmv1->text) > 100 ? substr($bmv1->text, 0, 100) . "..." : $bmv1->text;
								}
								?>
                            </td>
                            <td>
								<?php
								if(strlen($bmv1->reply_message) < 1000 && file_exists(public_path() . '/uploads/' . $bmv1->reply_message) && !empty($bmv1->reply_message)){
								?>
                                <button href="#" id="link<?php echo $bmv1->id;?>" data-toggle="modal"
                                        data-target="#myModal" src="/uploads/<?=$bmv1->reply_message?>" class="imatge">
                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                </button>
								<?php
								}
								else {
									echo strlen($bmv1->reply_message) > 100 ? substr($bmv1->reply_message, 0, 100) . "..." : $bmv1->reply_message;

								}
								?>
                            </td>
                            <td>
								<?php
								$date = new DateTime($bmv1->date);
								echo $date->format('d-m-Y') . "<br>" . $date->format('H:i:s');
								?>
                            </td>
                            </td>
                        </tr>
						<?php
						}
						}
						else{
						?>
                        <tr>
                            <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                        </tr>
						<?php
						}
						?>
                        <tr>
                            <td colspan="5" class="paginacio">

                                <div id="messageNavPosition" class="light-theme simple-pagination">
									<?php
									$lastpage_m = 0;
									if ($total_pages_message > 0) {
										$prev_m = $page - 1;
										$next_m = $page + 1;
										$lastpage_m = ceil($total_pages_message / $limitMessage);
										$lpm1_m = $lastpage_m - 1;
									}

									$pagination_m = '';
									if ($lastpage_m >= 1) {
										$pagination_m = '<ul>';

										if ($page > 1)
											$pagination_m .= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationM(' . "'0','first','" . $bots[0]->id . "'" . ')")">&lt;</a>';
										else
											$pagination_m .= '<li class="disabled"><span class="current prev">&lt;</span></li>';


										if ($lastpage_m < 7 + ($adjacents * 2)) {
											for ($counter_m = 1; $counter_m <= $lastpage_m; $counter_m++) {
												if ($counter_m == $page)
													$pagination_m .= '<li class="active"><span class="current">' . $counter_m . '</span></li>';
												else
													$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $counter_m . "','" . $counter_m . "_no', '" . $bots[0]->id . "'" . ')">' . $counter_m . '</a></li>';
											}
										} elseif ($lastpage_m > 5 + ($adjacents * 2)) {
											if ($page < 1 + ($adjacents * 2)) {
												for ($counter_m = 1; $counter_m < 4 + ($adjacents * 2); $counter_m++) {
													if ($counter_m == $page)
														$pagination_m .= '<li class="active"><span class="current">' . $counter_m . '</span>';
													else
														$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $counter_m . "','" . $counter_m . "_no', '" . $bots[0]->id . "'" . ')">' . $counter_m . '</a></li>';
												}
												$pagination_m .= '<li><span class="ellipse clickable">...</span></li>';
												$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $lpm1_m . "','" . $lpm1_m . "_no', '" . $bots[0]->id . "'" . ')">' . $lpm1_m . '</a></li>';
												$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $lastpage_m . "','" . $lastpage_m . "_no', '" . $bots[0]->id . "'" . ')">' . $lastpage_m . '</a></li>';
											}
										} elseif ($lastpage_m - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
											$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'1','1_no', '" . $bots[0]->id . "'" . ')">1</a></li>';
											$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'2','2_no', '" . $bots[0]->id . "'" . ')">2</a></li>';
											$pagination_m .= '<li><span class="ellipse clickable">...</span></li>';
											for ($counter_m = $page - $adjacents; $counter_m <= $page + $adjacents; $counter_m++) {
												if ($counter_m == $page)
													$pagination_m .= '<li class="active"><span class="current">' . $counter_m . '</span>';
												else
													$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $counter_m . "','" . $counter_m . "_no', '" . $bots[0]->id . "'" . ')">' . $lastpage_m . '</a></li>';
											}
											$pagination_m .= '<li><span class="ellipse clickable">...</span></li>';
											$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $lpm1_m . "','" . $lpm1_m . "_no','" . $bots[0]->id . "'" . ')">' . $lpm1_m . '</a></li>';
											$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $lastpage_m . "','" . $lastpage_m . "_no', '" . $bots[0]->id . "'" . ')">' . $lastpage_m . '</a></li>';
										} else {
											$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'1','1_no','" . $bots[0]->id . "'" . ')">1</a></li>';
											$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'2','2_no', '" . $bots[0]->id . "'" . ')">2</a></li>';
											$pagination_m .= '<li><span class="ellipse clickable">...</span></li>';
											for ($counter_m = $lastpage_m - (2 + ($adjacents * 2)); $counter_m <= $lastpage_m; $counter_m++) {
												if ($counter_m == $page)
													$pagination_m .= '<li class="active"><span class="current">' . $counter_m . '</span>';
												else
													$pagination_m .= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $counter_m . "','" . $counter_m . "_no', '" . $bots[0]->id . "'" . ')">' . $counter_m . '</a></li>';
											}
										}

										if ($page < $counter_m - 1) {
											$pagination_m .= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationM(' . "'" . $next_m . "','" . $next_m . "_no', '" . $bots[0]->id . "'" . ')">&gt;</a></li>';
										} else {
											$pagination_m .= '<li class="active"><span class="current next">&gt;</span>';
										}

										$pagination_m .= '</ul>';
									}

									echo $pagination_m;
									?>
                                    <img id="imgLoadAjaxM" src="{{URL::asset('img/balls.gif')}}"
                                         class="loading_ajax_img" style="display:none;">
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

    <div id="alertMsg" style="display:none;">
        <div id="resp" class="alert-new alert-success-new alert-dismissible" role="alert">
        </div>
    </div>

    <div id="myModalBot" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <img id="imgLoad" src="{{URL::asset('img/balls.gif')}}" class="loading2_img" style="display:none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('front/dashboard.send_a_message') }}</h4>
                </div>
                <div class="modal-body" style="text-align:center">
                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => '','id' =>'send_msg_bot']) !!}

                    <input type="hidden" name="b_bot_id" id="b_bot_id"/>
                    <p class="lead emoji-picker-container">
                        <textarea id="bot_msg" class="form-control textarea-control" name="bot_msg" cols="20" rows="15"
                                  placeholder="{{ trans('front/dashboard.enter_message') }}" data-emojiable="true"
                                  maxlength="4000"></textarea>
                    </p>
                    <small>* Màx. 4000 caràcters</small>
                    <br>
                    <br>

                    <label> {{ trans('front/dashboard.and_or_attach_an_image') }} </label>

                    <input type="file" name="bot_image" id="bot_image" accept="image/*"/>
                    <br/>

                    <input type="submit" name="submit" value="{{ trans('front/dashboard.send') }}"
                           class="btn btn-primary"/>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

    <script>

		$(document).ready(function () {
			getCharts();
		});

		function getCharts() {
			$('.loading_img').css('display', 'block');
			var id = $('#chart_bots').val();
			var chart_time = $('#chart_time').val();
			var chart_details = $('#chart_details').val();
			var token = $('input[name=_token]').val();

			$.ajax({
				url: '<?php echo URL::to('/dashboard/getcharts')?>',
				headers: {'X-CSRF-TOKEN': token},
				data: {bot_id: id, chart_time: chart_time, chart_details: chart_details},
				type: 'POST',
				success: function (resp) {
					google.charts.setOnLoadCallback(function () {
						var data_arr = JSON.parse(resp);
						drawChart(data_arr);
						$('.loading_img').css('display', 'none');
					});
				}
			});

		}

		function changePagination(pageId, liId, botId) {
			$('#imgLoadAjax').css('display', 'inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_autoresponse')?>/' + botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId, botId: botId},
				type: 'POST',
				success: function (resp) {
					$('#imgLoadAjax').css('display', 'none');
					$('#a_autoResp').html(resp);
				}
			});
		}


		function changePaginationCF(pageId, liId, botId) {
			$('#imgLoadAjaxCF').css('display', 'inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_contact_form')?>/' + botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId, botId: botId},
				type: 'POST',
				success: function (resp) {
					$('#imgLoadAjaxCF').css('display', 'none');
					$('#c_autoResp').html(resp);
				}
			});
		}

		function changePaginationG(pageId, liId, botId) {
			$('#imgLoadAjaxG').css('display', 'inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_gallery')?>/' + botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId, botId: botId},
				type: 'POST',
				success: function (resp) {
					$('#imgLoadAjaxG').css('display', 'none');
					$('#g_autoResp').html(resp);
				}
			});
		}

		function changePaginationCH(pageId, liId, botId) {
			$('#imgLoadAjaxCH').css('display', 'inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_channel')?>/' + botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId, botId: botId},
				type: 'POST',
				success: function (resp) {
					$('#imgLoadAjaxCH').css('display', 'none');
					$('#ch_autoResp').html(resp);
				}
			});
		}

		function changePaginationU(pageId, liId, botId) {
			$('#imgLoadAjaxU').css('display', 'inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_bot_active_user')?>/' + botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId, botId: botId},
				type: 'POST',
				success: function (resp) {
					$('#imgLoadAjaxU').css('display', 'none');
					$('#u_autoResp').html(resp);
				}
			});
		}


		function changePaginationM(pageId, liId, botId) {
			$('#imgLoadAjaxM').css('display', 'inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_bot_message')?>/' + botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId, botId: botId},
				type: 'POST',
				success: function (resp) {
					$('#imgLoadAjaxM').css('display', 'none');
					$('#m_autoResp').html(resp);
				}
			});
		}


		function changePaginationBC(pageId, liId, botId) {
			$('#imgLoadAjaxBC').css('display', 'inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_bot_command')?>/' + botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId, botId: botId},
				type: 'POST',
				success: function (resp) {
					$('#imgLoadAjaxBC').css('display', 'none');
					$('#bt_commands_BC').html(resp);
				}
			});
		}

        /*
         jQuery(function($) {
         var pageParts = $("#botContactForm tbody tr");
         var numPages = pageParts.length;
         var perPage = 4;
         pageParts.slice(perPage).hide();

         $("#botContactFormNavPosition").pagination({
         items: numPages,
         itemsOnPage: perPage,
         cssStyle: "light-theme",
         onPageClick: function(pageNum) {
         var start = perPage * (pageNum - 1);
         var end = start + perPage;
         pageParts.hide().slice(start, end).show();
         }
         });
         });
         */

    </script>

    <script type="text/javascript">

		function mypopup_botfunction(bot_id) {
			$('#bot_msg').css('border', '1px solid #ccc');
			$('#b_bot_id').val(bot_id);

			$('#bot_msg').val('');

			$('#myModalBot').modal();
		}

        /*
         function sendMsgBot(){
         var chk = true;
         var bot_msg = $('#bot_msg').val();
         var b_bot_id = $('#b_bot_id').val();

         if(bot_msg == ''){
         chk = false;
         $('#bot_msg').css('border','1px solid #ff0000');
         }
         else{
         $('#bot_msg').css('border','1px solid #ccc');
         }


         if(chk){
         var token_new = $('input[name=_token]').val();
         $.ajax({
         url: '<?php //echo URL::to('/dashboard/sendbotmessage')?>',
         headers: {'X-CSRF-TOKEN': token_new},
         data: {bot_msg:bot_msg,b_bot_id:b_bot_id},
         type:'POST',
         success: function (resp) {
         alert(resp);
         $('#myModalBot').modal('hide');
         },
         error: function (request, status, error) {
         alert('Forbidden: bot is not a member of the channel chat');
         }
         });
         }
         }
         */


		$(document).ready(function (e) {
			$("form#send_msg_bot").submit(function (event) {
				event.preventDefault();

				var chk = true;
				var bot_msg = $('#bot_msg').val();
				var b_bot_id = $('#b_bot_id').val();
				var bot_img = $('#bot_image').val();

				if (bot_msg == '' && bot_img == '') {
					chk = false;
					$('#bot_msg').css('border', '1px solid #ff0000');
				}
				else {
					$('#bot_msg').css('border', '1px solid #ccc');
				}

				var formData = new FormData($(this)[0]);
				var token_new = $('input[name=_token]').val();

				if (chk) {
					$('#imgLoad').css('display', 'block');
					$.ajax({
						url: '<?php echo URL::to('/dashboard/sendbotmessage')?>',
						headers: {'X-CSRF-TOKEN': token_new},
						data: formData,
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						type: 'POST',
						success: function (resp) {
							$('#imgLoad').css('display', 'none');
							$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' + resp);
							$('.alert-new').css('display', 'block');
							$('#alertMsg').css('display', 'block');
							$('#myModalBot').modal('hide');
						},
						error: function (request, status, error) {
							$('#imgLoad').css('display', 'none');
							$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Error, el missatge és massa llarg');
							$('.alert-new').css('display', 'block');
							$('#alertMsg').css('display', 'block');
						}
					});
				}
				return false;
			});
		});
    </script>
    <script>
		$(document).ready(function (e) {
			$('#bot_messages').on('click', '.imatge', function () {
				//alert($(this).attr("src"));
				$("#showImg").empty();
				var image = $(this).attr("src");

				$("#showImg").append("<img class='img-responsive' src='" + image + "' />");

			});
			$('.chat_box').css('display', 'block');
			$('#auto_resp').html("<?php echo $bots[0]->autoresponse; ?>");
			$('#conntact_fbutton').html("<?php echo $bots[0]->contact_form; ?>");
			$('#gallery_imgs').html("<?php echo $bots[0]->galleries; ?>");
			$('#chnl_btn').html("<?php echo $bots[0]->channels; ?>");


		});


    </script>
    <style>
        .thumb {
            width: 20%;
        }
    </style>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body" id="showImg">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                </div>
            </div>

        </div>
    </div>
    <style type="text/css">
        .my-img a {
            display: inline-block;
            margin: 10px;
            border: 2px solid #CCC;
        }

        .my-img a:hover {
            border: 2px solid #45AFFF;
        }

        .modal-lg {
            width: 86%;
        }

        .modal-body {
            overflow: auto;
            max-height: auto;
        }
    </style>



    <script>
		$(function () {
			//$("#sortable_botAutoresponse").sortable();
			//$("#sortable_botAutoresponse").disableSelection();

			$("#sortable_botContactForm").sortable();
			$("#sortable_botContactForm").disableSelection();

			$("#sortable_botGallery").sortable();
			$("#sortable_botGallery").disableSelection();

			$("#sortable_botChannels").sortable();
			$("#sortable_botChannels").disableSelection();

			/**
			 *
			 * @param type string 'insertAfter' or 'insertBefore'
			 * @param entityName
			 * @param id
			 * @param positionId
			 */
			var changePosition = function(requestData){
				var token = $('input[name=_token]').val();
				$.ajax({
					url: '<?php echo URL::to('/bot/sortbuttons')?>/',
					headers: {'X-CSRF-TOKEN': token},
					'type': 'POST',
					'data': requestData,
					'success': function(data) {
						if (data.success) {
							console.log('Saved!');
						} else {
							console.error(data.errors);
						}
					},
					'error': function(){
						console.error('Something wrong!');
					}
				});
			};

			$(document).ready(function(){
				var $sortableTable = $('#sortable_botAutoresponse');
				if ($sortableTable.length > 0) {
					$sortableTable.sortable({
						handle: '.telebutton',
						update: function(a, b){

							var entityName = $(this).data('entityname');
							var $sorted = b.item;

							var $previous = $sorted.prev();
							var $next = $sorted.next();

							if ($previous.length > 0) {
								changePosition({
									parentId: $sorted.data('parentid'),
									type: 'moveAfter',
									entityName: entityName,
									id: $sorted.data('itemid'),
									positionEntityId: $previous.data('itemid')
								});
							} else if ($next.length > 0) {
								changePosition({
									parentId: $sorted.data('parentid'),
									type: 'moveBefore',
									entityName: entityName,
									id: $sorted.data('itemid'),
									positionEntityId: $next.data('itemid')
								});
							} else {
								console.error('Something wrong!');
							}
						},
						cursor: "move"
					});
				}
			});

			$("#searchAutoresponse")
				.change(function () {
					var filter = $(this).val();
					if (filter) {
						// this finds all links in a list that contain the input,
						// and hide the ones not containing the input while showing the ones that do
						$("#sortable_botAutoresponse").find("a.telebutton:not(:Contains(" + filter + "))").parent().slideUp();
						$("#sortable_botAutoresponse").find("a.telebutton:Contains(" + filter + ")").parent().slideDown();
					} else {
						$("#sortable_botAutoresponse").find("li").slideDown();
					}
					return false;
				})
				.keyup(function () {
					// fire the above change event after every letter
					$(this).change();
				});
			$("#searchChannels")
				.change(function () {
					var filter = $(this).val();
					if (filter) {
						// this finds all links in a list that contain the input,
						// and hide the ones not containing the input while showing the ones that do
						$("#sortable_botChannels").find("a.telebutton:not(:Contains(" + filter + "))").parent().slideUp();
						$("#sortable_botChannels").find("a.telebutton:Contains(" + filter + ")").parent().slideDown();
					} else {
						$("#sortable_botChannels").find("li").slideDown();
					}
					return false;
				})
				.keyup(function () {
					// fire the above change event after every letter
					$(this).change();
				});
			$("#searchGallery")
				.change(function () {
					var filter = $(this).val();
					if (filter) {
						// this finds all links in a list that contain the input,
						// and hide the ones not containing the input while showing the ones that do
						$("#sortable_botGallery").find("a.telebutton:not(:Contains(" + filter + "))").parent().slideUp();
						$("#sortable_botGallery").find("a.telebutton:Contains(" + filter + ")").parent().slideDown();
					} else {
						$("#sortable_botGallery").find("li").slideDown();
					}
					return false;
				})
				.keyup(function () {
					// fire the above change event after every letter
					$(this).change();
				});
			$("#searchContactForm")
				.change(function () {
					var filter = $(this).val();
					if (filter) {
						// this finds all links in a list that contain the input,
						// and hide the ones not containing the input while showing the ones that do
						$("#sortable_botContactForm").find("a.telebutton:not(:Contains(" + filter + "))").parent().slideUp();
						$("#sortable_botContactForm").find("a.telebutton:Contains(" + filter + ")").parent().slideDown();
					} else {
						$("#sortable_botContactForm").find("li").slideDown();
					}
					return false;
				})
				.keyup(function () {
					// fire the above change event after every letter
					$(this).change();
				});
			$("#searchCommands")
				.change(function () {
					var filter = $(this).val();
					if (filter) {
						// this finds all links in a list that contain the input,
						// and hide the ones not containing the input while showing the ones that do
						$("#sortable_botCommands").find("a.telebutton:not(:Contains(" + filter + "))").parent().slideUp();
						$("#sortable_botCommands").find("a.telebutton:Contains(" + filter + ")").parent().slideDown();
					} else {
						$("#sortable_botCommands").find("li").slideDown();
					}
					return false;
				})
				.keyup(function () {
					// fire the above change event after every letter
					$(this).change();
				});

		});

    </script>

@stop