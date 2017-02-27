@extends('front.template')
@section('main')

	{!! HTML::style('css/front/simplePagination.css') !!}
	{!! HTML::script('js/front/jquery.simplePagination.js') !!}

<script>
    $(document).ready(function () {

    });

        function warnBeforeRedirect(linkURL) {
            swal({
                html:true,
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
                    google.charts.load('current', {'packages':['corechart']});

                    function drawChart(data_arr) {
                        var data = google.visualization.arrayToDataTable(data_arr);

                        var options_fullStacked = {
                            title: '',
                            chartArea:{left:0,top:0,bottom:10,width:"100%",height:"100%"},
                            curveType: 'function',
                            tooltip: {
                                isHtml: true
                            },
                            vAxis: {
                                viewWindow: {
                                    min:0
                                }
                            },
                            lineWidth: 5,
                            pointSize: 10,
                            colors: ['#00B09E'],
                            legend: { position: 'bottom' }
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
                            if($bots[0]->id ==  $tbv1->id){
                                $select = 'selected="selected"';
                            }
                            ?>
							<option <?php echo $select; ?> value="<?php echo $tbv1->id; ?>"><?php echo $tbv1->username;?></option>
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
					<li class="titol_bot"><h4>{!! $bots[0]->username !!} <a href="https://telegram.me/{!! $bots[0]->username !!}" target="_blank" title="Telegram Bot"><i class="fa fa-external-link" aria-hidden="true"></i></a></h4>
						<?php if(isset($planDetails[0]->name) && !empty($planDetails[0]->name)){ echo $planDetails[0]->name; } ?>
					</li>
					<li class="token_bot"><b>{{ trans('front/bots.bot_token') }}:</b> {!! $bots[0]->bot_token !!}</li>
				</ul>
				<div class="send-edit">
					<a href="javascript:void(0);" class="btn btn-primary" onclick="mypopup_botfunction('<?php echo $bots[0]->id;?>');"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{ trans('front/dashboard.send_message') }}</a>
					<a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> {!! trans('front/dashboard.edit_bot') !!}</a>
					<!--<a href="{!! URL::to('/command/create/'.$bots[0]->id) !!}" class="btn btn-success"><i class="fa fa-star" aria-hidden="true"></i> {!! trans('front/dashboard.create_command') !!}</a>-->
				</div>
				<ul class="nav nav-tabs nav-pills pills_bot" role="tablist">
					<li class="active">
						<a data-toggle="tab" href="#bot_main_buttons"><i class="fa fa-bars" aria-hidden="true"></i> {!! trans('front/bots.buttons') !!}</a>
					</li>
					<li>
						<a data-toggle="tab" href="#bot_users"><i class="fa fa-user" aria-hidden="true"></i> {!! trans('front/bots.users') !!}</a>
					</li>
					<li><a data-toggle="tab" href="#bot_messages"><i class="fa fa-line-chart" aria-hidden="true"></i> Log</a></li>
				</ul>

			</div>
		</div>





		<div class="col-lg-12 tab-content">
			<div id="bot_main_buttons" class="tab-pane fade in active">
				<div class="col-plan col-lg-6">
					<h2 class="h2_information"><?php echo $bots[0]->autoresponse; ?>                     <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar el nom" class="editar_boto">
							<i class="fa fa-pencil" aria-hidden="true"></i>
						</a></h2>
                    <div id="a_autoResp">    
						<table id="botAutoresponse">
                            <thead>
                            <tr>
                                <th>{{ trans('front/bots.submenu_heading_text') }}</th>
                                <th>{{ trans('front/bots.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($autoResponse)){
                            foreach($autoResponse as $d2 => $v2){
                            ?>
                            <tr>
                                <td><?php echo $v2->submenu_heading_text;?></td>
                                <td>
                                    <a class="btn btn-warning" href="{!! URL::to('/command/autoresponse_edit/'.$v2->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a class="btn btn-danger" onclick="return warnBeforeRedirect('{!! URL::to('/command/autoresponse_delete/'.$v2->type_id.'/'.$v2->id) !!}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                            <tfoot>
                            <tr>
                                <td colspan="5" class="paginacio">
									<div class="botonou">
                                        <?php
                                        if(isset($planDetails[0]->autoresponses) && !empty($planDetails[0]->autoresponses) && $total_pages < $planDetails[0]->autoresponses){
                                        ?>
										<a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=autoresponses') !!}" class="btn btn-success">
											{!! trans('front/dashboard.create_command') !!}
										</a>

                                        <?php
                                        }
                                        else{
                                        ?>
										<a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=autoresponses&act=1') !!}" class="btn btn-success">
											{!! trans('front/dashboard.create_command') !!}
										</a>
                                        <?php
                                        }
                                        ?>
									</div>
                                    <?php if(isset($planDetails[0]->autoresponses) && !empty($planDetails[0]->autoresponses) && $planDetails[0]->autoresponses<999){
                                        echo '<div class="info_test"> '.$total_pages.' / '.$planDetails[0]->autoresponses.' </div>';
                                    } ?>

										<div id="botAutoresponseNavPosition" class="light-theme simple-pagination">
                                            <?php
                                            $lastpage = 0;
                                            if($total_pages > 0)
                                            {
                                                $prev = $page - 1;
                                                $next = $page + 1;
                                                $lastpage = ceil($total_pages/$limit);
                                                $lpm1 = $lastpage - 1;
                                            }

                                            $pagination = '';
                                            if($lastpage >= 1)
                                            {
                                                $pagination = '<ul>';

                                                if ($page > 1)
                                                    $pagination.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePagination('."'0','first','".$bots[0]->id."'".')")">&lt;</a>';
                                                else
                                                    $pagination.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                                                if ($lastpage < 7 + ($adjacents * 2))
                                                {
                                                    for ($counter = 1; $counter <= $lastpage; $counter++)
                                                    {
                                                        if ($counter == $page)
                                                            $pagination.= '<li class="active"><span class="current">'.$counter.'</span></li>';
                                                        else
                                                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$bots[0]->id."'".')">'.$counter.'</a></li>';
                                                    }
                                                }
                                                elseif($lastpage > 5 + ($adjacents * 2))
                                                {
                                                    if($page < 1 + ($adjacents * 2))
                                                    {
                                                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                                                        {
                                                            if ($counter == $page)
                                                                $pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
                                                            else
                                                                $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$bots[0]->id."'".')">'.$counter.'</a></li>';
                                                        }
                                                        $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no', '".$bots[0]->id."'".')">'.$lpm1.'</a></li>';
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$bots[0]->id."'".')">'.$lastpage.'</a></li>';
                                                    }
                                                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                                    {
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no', '".$bots[0]->id."'".')">1</a></li>';
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                        $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                                                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                                                        {
                                                            if ($counter == $page)
                                                                $pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
                                                            else
                                                                $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$bots[0]->id."'".')">'.$lastpage.'</a></li>';
                                                        }
                                                        $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no','".$bots[0]->id."'".')">'.$lpm1.'</a></li>';
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$bots[0]->id."'".')">'.$lastpage.'</a></li>';
                                                    }
                                                    else
                                                    {
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no','".$bots[0]->id."'".')">1</a></li>';
                                                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                        $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                                                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                                                        {
                                                            if ($counter == $page)
                                                                $pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
                                                            else
                                                                $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$bots[0]->id."'".')">'.$counter.'</a></li>';
                                                        }
                                                    }
                                                }

                                                if ($page < $counter - 1)
                                                {
                                                    $pagination.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePagination('."'".$next."','".$next."_no', '".$bots[0]->id."'".')">&gt;</a></li>';
                                                }
                                                else
                                                {
                                                    $pagination.= '<li class="active"><span class="current next">&gt;</span>';
                                                }

                                                $pagination .= '</ul>';
                                            }

                                            echo $pagination;
                                            ?>
											<img id="imgLoadAjax" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
										</div>
                                </td>
                            </tr>
                            </tfoot>
                            </tbody>
                        </table>

                    </div>
				</div>

				<div class="col-plan col-lg-6">
					<h2 class="h2_contact"><?php echo $bots[0]->contact_form; ?>                     <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar el nom" class="editar_boto">
							<i class="fa fa-pencil" aria-hidden="true"></i>
						</a></h2>
                    
                    <div id="c_autoResp">
                    	<table id="botContactForm">
                            <thead>
                            <tr>
                                <th>{{ trans('front/bots.submenu_heading_text') }}</th>
                                <th>{{ trans('front/bots.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($contactForm)){
                            foreach($contactForm as $d3 => $v3){
                            ?>
                            <tr>
                                <td><?php echo $v3->submenu_heading_text;?></td>
                                <td><a class="btn btn-warning" href="{!! URL::to('/command/contactform_edit/'.$v3->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a class="btn btn-danger" onclick="return warnBeforeRedirect('{!! URL::to('/command/contactform_delete/'.$v3->type_id.'/'.$v3->id) !!}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
									<div class="botonou">
                                        <?php
                                        if(isset($planDetails[0]->contact_forms) && !empty($planDetails[0]->contact_forms) && $total_pages_contatc_form < $planDetails[0]->contact_forms)
                                        {
                                        ?>
										<a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=contactforms') !!}" class="btn btn-success">{!! trans('front/dashboard.create_command') !!}</a>
                                        <?php
                                        }
                                        else{
                                        ?>
										<a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=contactforms&act=1') !!}" class="btn btn-success">{!! trans('front/dashboard.create_command') !!}</a>
                                        <?php
                                        }
                                        ?>
									</div>

                                    <?php if(isset($planDetails[0]->contact_forms) && !empty($planDetails[0]->contact_forms) && $planDetails[0]->contact_forms<999){
                                        echo '<div class="info_test"> '.$total_pages_contatc_form.' / '.$planDetails[0]->contact_forms.' </div>';
                                    } ?>

									<div id="botContactFormNavPosition" class="light-theme simple-pagination">
                                        <?php
                                        $lastpage_cf = 0;
                                        if($total_pages_contatc_form > 0)
                                        {
                                            $prev_cf = $page - 1;
                                            $next_cf = $page + 1;
                                            $lastpage_cf = ceil($total_pages_contatc_form/$limit);
                                            $lpm1_cf = $lastpage_cf - 1;
                                        }

                                        $pagination_cf = '';
                                        if($lastpage_cf >= 1)
                                        {
                                            $pagination_cf = '<ul>';

                                            if ($page > 1)
                                                $pagination_cf.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationCF('."'0','first','".$bots[0]->id."'".')")">&lt;</a>';
                                            else
                                                $pagination_cf.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                                            if ($lastpage_cf < 7 + ($adjacents * 2))
                                            {
                                                for ($counter_cf = 1; $counter_cf <= $lastpage_cf; $counter_cf++)
                                                {
                                                    if ($counter_cf == $page)
                                                        $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span></li>';
                                                    else
                                                        $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$bots[0]->id."'".')">'.$counter_cf.'</a></li>';
                                                }
                                            }
                                            elseif($lastpage_cf > 5 + ($adjacents * 2))
                                            {
                                                if($page < 1 + ($adjacents * 2))
                                                {
                                                    for ($counter_cf = 1; $counter_cf < 4 + ($adjacents * 2); $counter_cf++)
                                                    {
                                                        if ($counter_cf == $page)
                                                            $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span>';
                                                        else
                                                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$bots[0]->id."'".')">'.$counter_cf.'</a></li>';
                                                    }
                                                    $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lpm1_cf."','".$lpm1_cf."_no', '".$bots[0]->id."'".')">'.$lpm1_cf.'</a></li>';
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lastpage_cf."','".$lastpage_cf."_no', '".$bots[0]->id."'".')">'.$lastpage_cf.'</a></li>';
                                                }
                                                elseif($lastpage_cf - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                                {
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'1','1_no', '".$bots[0]->id."'".')">1</a></li>';
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                    $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                                                    for ($counter_cf = $page - $adjacents; $counter_cf <= $page + $adjacents; $counter_cf++)
                                                    {
                                                        if ($counter_cf == $page)
                                                            $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span>';
                                                        else
                                                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$bots[0]->id."'".')">'.$lastpage_cf.'</a></li>';
                                                    }
                                                    $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lpm1_cf."','".$lpm1_cf."_no','".$bots[0]->id."'".')">'.$lpm1_cf.'</a></li>';
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lastpage_cf."','".$lastpage_cf."_no', '".$bots[0]->id."'".')">'.$lastpage_cf.'</a></li>';
                                                }
                                                else
                                                {
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'1','1_no','".$bots[0]->id."'".')">1</a></li>';
                                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                    $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                                                    for ($counter_cf = $lastpage_cf - (2 + ($adjacents * 2)); $counter_cf <= $lastpage_cf; $counter_cf++)
                                                    {
                                                        if ($counter_cf == $page)
                                                            $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span>';
                                                        else
                                                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$bots[0]->id."'".')">'.$counter_cf.'</a></li>';
                                                    }
                                                }
                                            }


                                            if ($page < $counter_cf - 1)
                                            {
                                                $pagination_cf.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationCF('."'".$next_cf."','".$next_cf."_no', '".$bots[0]->id."'".')">&gt;</a></li>';
                                            }
                                            else
                                            {
                                                $pagination_cf.= '<li class="active"><span class="current next">&gt;</span>';
                                            }

                                            $pagination_cf .= '</ul>';
                                        }

                                        echo $pagination_cf;
                                        ?>
										<img id="imgLoadAjaxCF" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
									</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>    
				</div>
				<div style="clear:both"></div>
				
                <div class="col-plan col-lg-6">
					<h2 class="h2_photos">
						<?php echo $bots[0]->galleries; ?>
						<a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar el nom" class="editar_boto">
							<i class="fa fa-pencil" aria-hidden="true"></i>
						</a>
					</h2>
					<div id="g_autoResp">
                    	<table id="botGallery">
						<thead>
						<tr>
							<th>{{ trans('front/bots.submenu_heading_text') }}</th>
							<th>{{ trans('front/bots.action') }}</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if(!empty($gallery)){
						foreach($gallery as $d4 => $v4){
						?>
						<tr>
							<td><?php echo $v4->gallery_submenu_heading_text;?></td>
							<td>
								<a class="btn btn-warning" href="{!! URL::to('/command/gallery_edit/'.$v4->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
								<a class="btn btn-danger" onclick="return warnBeforeRedirect('{!! URL::to('/command/gallery_delete/'.$v4->type_id.'/'.$v4->id) !!}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
								<div class="botonou">
									 <?php if(isset($planDetails[0]->image_gallery) && !empty($planDetails[0]->image_gallery) && $total_pages_gallery < $planDetails[0]->image_gallery){
									 ?>
										<a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=galleries') !!}" class="btn btn-success">{!! trans('front/dashboard.create_command') !!}</a>
									 <?php
										 }
										 else{
										?>
											<a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=galleries&act=1') !!}" class="btn btn-success">{!! trans('front/dashboard.create_command') !!}</a>
										<?php
										 }
									 ?>
								</div>

                                <?php if(isset($planDetails[0]->image_gallery) && !empty($planDetails[0]->image_gallery) && $planDetails[0]->image_gallery<999){
                                    echo '<div class="info_test"> '.$total_pages_gallery.' / '.$planDetails[0]->image_gallery.' </div>';
                                } ?>

								<div id="botGalleryNavPosition" class="light-theme simple-pagination">
                                    <?php
                                    $lastpage_g = 0;
                                    if($total_pages_gallery > 0)
                                    {
                                        $prev_g = $page - 1;
                                        $next_g = $page + 1;
                                        $lastpage_g = ceil($total_pages_gallery/$limit);
                                        $lpm1_g = $lastpage_g - 1;
                                    }

                                    $pagination_g = '';
                                    if($lastpage_g >= 1)
                                    {
                                        $pagination_g = '<ul>';

                                        if ($page > 1)
                                            $pagination_g.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationG('."'0','first','".$bots[0]->id."'".')")">&lt;</a>';
                                        else
                                            $pagination_g.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                                        if ($lastpage_g < 7 + ($adjacents * 2))
                                        {
                                            for ($counter_g = 1; $counter_g <= $lastpage_g; $counter_g++)
                                            {
                                                if ($counter_g == $page)
                                                    $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span></li>';
                                                else
                                                    $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$bots[0]->id."'".')">'.$counter_g.'</a></li>';
                                            }
                                        }
                                        elseif($lastpage_g > 5 + ($adjacents * 2))
                                        {
                                            if($page < 1 + ($adjacents * 2))
                                            {
                                                for ($counter_g = 1; $counter_g < 4 + ($adjacents * 2); $counter_g++)
                                                {
                                                    if ($counter_g == $page)
                                                        $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span>';
                                                    else
                                                        $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$bots[0]->id."'".')">'.$counter_g.'</a></li>';
                                                }
                                                $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lpm1_g."','".$lpm1_g."_no', '".$bots[0]->id."'".')">'.$lpm1_g.'</a></li>';
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lastpage_g."','".$lastpage_g."_no', '".$bots[0]->id."'".')">'.$lastpage_g.'</a></li>';
                                            }
                                            elseif($lastpage_g - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                            {
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'1','1_no', '".$bots[0]->id."'".')">1</a></li>';
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                                                for ($counter_g = $page - $adjacents; $counter_g <= $page + $adjacents; $counter_g++)
                                                {
                                                    if ($counter_g == $page)
                                                        $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span>';
                                                    else
                                                        $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$bots[0]->id."'".')">'.$counter_g.'</a></li>';
                                                }
                                                $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lpm1_g."','".$lpm1_g."_no','".$bots[0]->id."'".')">'.$lpm1_g.'</a></li>';
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lastpage_g."','".$lastpage_g."_no', '".$bots[0]->id."'".')">'.$lastpage_g.'</a></li>';
                                            }
                                            else
                                            {
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'1','1_no','".$bots[0]->id."'".')">1</a></li>';
                                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                                                for ($counter_g = $lastpage_g - (2 + ($adjacents * 2)); $counter_g <= $lastpage_g; $counter_g++)
                                                {
                                                    if ($counter_g == $page)
                                                        $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span>';
                                                    else
                                                        $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$bots[0]->id."'".')">'.$counter_g.'</a></li>';
                                                }
                                            }
                                        }

                                        if ($page < $counter_g - 1)
                                        {
                                            $pagination_g.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationG('."'".$next_g."','".$next_g."_no', '".$bots[0]->id."'".')">&gt;</a></li>';
                                        }
                                        else
                                        {
                                            $pagination_g.= '<li class="active"><span class="current next">&gt;</span>';
                                        }

                                        $pagination_g .= '</ul>';
                                    }

                                    echo $pagination_g;
                                    ?>
									<img id="imgLoadAjaxG" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
								</div>
							</td>
						</tr>
						</tbody>
					</table>
                    </div>
				</div>

				<div class="col-plan col-lg-6">
					<h2 class="h2_channels"><?php echo $bots[0]->channels; ?>                     <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}#main_buttons" data-toggle="tooltip" data-original-title="Editar el nom" class="editar_boto">
							<i class="fa fa-pencil" aria-hidden="true"></i>
						</a></h2>
                        
                        <div id="ch_autoResp">
							<table id="botChannels">
                                <thead>
                                <tr>
                                    <th>{{ trans('front/bots.submenu_heading_text') }}</th>
                                    <th>{{ trans('front/bots.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($chanels)){
                                foreach($chanels as $d5 => $v5){
                                ?>
                                <tr>
                                    <td><?php echo $v5->chanel_submenu_heading_text;?></td>
                                    <td>
                                        <a class="btn btn-warning" href="{!! URL::to('/command/chanel_edit/'.$v5->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <a class="btn btn-danger" onclick="return warnBeforeRedirect('{!! URL::to('/command/chanel_delete/'.$v5->type_id.'/'.$v5->id) !!}');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
										<div class="botonou">
                                        	<a href="{!! URL::to('/command/create/'.$bots[0]->id.'?type=chanel') !!}" class="btn btn-success">{!! trans('front/dashboard.create_command') !!}</a>
										</div>

										<div id="botChannelsNavPosition" class="light-theme simple-pagination">
                                            <?php
                                            $lastpage_ch = 0;
                                            if($total_pages_chanels > 0)
                                            {
                                                $prev_ch = $page - 1;
                                                $next_ch = $page + 1;
                                                $lastpage_ch = ceil($total_pages_chanels/$limit);
                                                $lpm1_ch = $lastpage_ch - 1;
                                            }

                                            $pagination_ch = '';
                                            if($lastpage_ch >= 1)
                                            {
                                                $pagination_ch = '<ul>';

                                                if ($page > 1)
                                                    $pagination_ch.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationCH('."'0','first','".$bots[0]->id."'".')")">&lt;</a>';
                                                else
                                                    $pagination_ch.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                                                if ($lastpage_ch < 7 + ($adjacents * 2))
                                                {
                                                    for ($counter_ch = 1; $counter_ch <= $lastpage_ch; $counter_ch++)
                                                    {
                                                        if ($counter_ch == $page)
                                                            $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span></li>';
                                                        else
                                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$bots[0]->id."'".')">'.$counter_ch.'</a></li>';
                                                    }
                                                }
                                                elseif($lastpage_ch > 5 + ($adjacents * 2))
                                                {
                                                    if($page < 1 + ($adjacents * 2))
                                                    {
                                                        for ($counter_ch = 1; $counter_ch < 4 + ($adjacents * 2); $counter_ch++)
                                                        {
                                                            if ($counter_ch == $page)
                                                                $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                                            else
                                                                $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$bots[0]->id."'".')">'.$counter_ch.'</a></li>';
                                                        }
                                                        $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lpm1_ch."','".$lpm1_ch."_no', '".$bots[0]->id."'".')">'.$lpm1_ch.'</a></li>';
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lastpage_ch."','".$lastpage_ch."_no', '".$bots[0]->id."'".')">'.$lastpage_ch.'</a></li>';
                                                    }
                                                    elseif($lastpage_ch - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                                    {
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'1','1_no', '".$bots[0]->id."'".')">1</a></li>';
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                        $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                                        for ($counter_ch = $page - $adjacents; $counter_ch <= $page + $adjacents; $counter_ch++)
                                                        {
                                                            if ($counter_ch == $page)
                                                                $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                                            else
                                                                $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$bots[0]->id."'".')">'.$counter_ch.'</a></li>';
                                                        }
                                                        $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lpm1_ch."','".$lpm1_ch."_no','".$bots[0]->id."'".')">'.$lpm1_ch.'</a></li>';
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lastpage_ch."','".$lastpage_ch."_no', '".$bots[0]->id."'".')">'.$lastpage_ch.'</a></li>';
                                                    }
                                                    else
                                                    {
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'1','1_no','".$bots[0]->id."'".')">1</a></li>';
                                                        $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                        $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                                        for ($counter_ch = $lastpage_ch - (2 + ($adjacents * 2)); $counter_ch <= $lastpage_ch; $counter_ch++)
                                                        {
                                                            if ($counter_ch == $page)
                                                                $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                                            else
                                                                $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$bots[0]->id."'".')">'.$counter_ch.'</a></li>';
                                                        }
                                                    }
                                                }

                                                if ($page < $counter_ch - 1)
                                                {
                                                    $pagination_ch.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationCH('."'".$next_ch."','".$next_ch."_no', '".$bots[0]->id."'".')">&gt;</a></li>';
                                                }
                                                else
                                                {
                                                    $pagination_ch.= '<li class="active"><span class="current next">&gt;</span>';
                                                }

                                                $pagination_ch .= '</ul>';
                                            }

                                            echo $pagination_ch;
                                            ?>
											<img id="imgLoadAjaxCH" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
										</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                   		</div> 
				</div>
				<div style="clear:both"></div>
                
                <div class="col-plan col-lg-12">
					<h2 class="h2_commands">{!! trans('front/bots.bot_command') !!} ({{ $bots[0]->comanda }})</h2>
                        
                        <div id="bt_commands_BC">
							<table id="botChannels">
                                <thead>
                                <tr>
                                    <th>{!! trans('front/bots.bot_command') !!}</th>
                                    <th>{{ trans('front/bots.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($botCommands)){
                                foreach($botCommands as $d6 => $v6){
                                ?>
                                <tr>
                                    <td><?php echo $v6->title;?></td>
                                    <td>
                                        <a class="btn btn-warning" href="{!! URL::to('/bot/bot_command_edit/'.$v6->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <a class="btn btn-danger" onclick="return warnBeforeRedirect('{!! URL::to('/bot/bot_command_delete/'.$v6->bot_id.'/'.$v6->id) !!}');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
										<div class="botonou">
											<a href="{!! URL::to('bot/bot_command/'.$bots[0]->id) !!}"  class="btn btn-success <?php if(isset($planDetails[0]->bot_commands) && !empty($planDetails[0]->bot_commands) && $total_bot_commands == $planDetails[0]->bot_commands) echo "disabled"; ?>">{!! trans('front/bots.bot_add_command') !!}</a>
										</div>

                                            
                                             <?php if(isset($planDetails[0]->bot_commands) && !empty($planDetails[0]->bot_commands) && $planDetails[0]->bot_commands<999){
												echo '<div class="info_test"> '.$total_bot_commands.' / '.$planDetails[0]->bot_commands.' </div>';
											} ?>

										<div id="botCommandNavPosition" class="light-theme simple-pagination">
                                            <?php
                                            $lastpage_bc = 0;
                                            if($total_bot_commands > 0)
                                            {
                                                $prev_bc = $page - 1;
                                                $next_bc = $page + 1;
                                                $lastpage_bc = ceil($total_bot_commands/$bot_commands_limit);
                                                $lpm1_bc = $lastpage_bc - 1;
                                            }

                                            $pagination_bc = '';
                                            if($lastpage_bc >= 1)
                                            {
                                                $pagination_bc = '<ul>';

                                                if ($page > 1)
                                                    $pagination_bc.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationBC('."'0','first','".$bots[0]->id."'".')")">&lt;</a>';
                                                else
                                                    $pagination_bc.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                                                if ($lastpage_bc < 7 + ($adjacents * 2))
                                                {
                                                    for ($counter_bc = 1; $counter_bc <= $lastpage_bc; $counter_bc++)
                                                    {
                                                        if ($counter_bc == $page)
                                                            $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span></li>';
                                                        else
                                                            $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$bots[0]->id."'".')">'.$counter_bc.'</a></li>';
                                                    }
                                                }
                                                elseif($lastpage_bc > 5 + ($adjacents * 2))
                                                {
                                                    if($page < 1 + ($adjacents * 2))
                                                    {
                                                        for ($counter_bc = 1; $counter_bc < 4 + ($adjacents * 2); $counter_bc++)
                                                        {
                                                            if ($counter_bc == $page)
                                                                $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span>';
                                                            else
                                                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$bots[0]->id."'".')">'.$counter_bc.'</a></li>';
                                                        }
                                                        $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lpm1_bc."','".$lpm1_bc."_no', '".$bots[0]->id."'".')">'.$lpm1_bc.'</a></li>';
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lastpage_bc."','".$lastpage_bc."_no', '".$bots[0]->id."'".')">'.$lastpage_bc.'</a></li>';
                                                    }
                                                    elseif($lastpage_bc - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                                    {
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'1','1_no', '".$bots[0]->id."'".')">1</a></li>';
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                        $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                                        for ($counter_bc = $page - $adjacents; $counter_bc <= $page + $adjacents; $counter_bc++)
                                                        {
                                                            if ($counter_bc == $page)
                                                                $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span>';
                                                            else
                                                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$bots[0]->id."'".')">'.$counter_bc.'</a></li>';
                                                        }
                                                        $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lpm1_bc."','".$lpm1_bc."_no','".$bots[0]->id."'".')">'.$lpm1_bc.'</a></li>';
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lastpage_bc."','".$lastpage_bc."_no', '".$bots[0]->id."'".')">'.$lastpage_bc.'</a></li>';
                                                    }
                                                    else
                                                    {
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'1','1_no','".$bots[0]->id."'".')">1</a></li>';
                                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                        $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                                        for ($counter_bc = $lastpage_bc - (2 + ($adjacents * 2)); $counter_bc <= $lastpage_bc; $counter_bc++)
                                                        {
                                                            if ($counter_bc == $page)
                                                                $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span>';
                                                            else
                                                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$bots[0]->id."'".')">'.$counter_bc.'</a></li>';
                                                        }
                                                    }
                                                }

                                                if ($page < $counter_bc - 1)
                                                {
                                                    $pagination_bc.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationBC('."'".$next_bc."','".$next_bc."_no', '".$bots[0]->id."'".')">&gt;</a></li>';
                                                }
                                                else
                                                {
                                                    $pagination_bc.= '<li class="active"><span class="current next">&gt;</span>';
                                                }

                                                $pagination_bc .= '</ul>';
                                            }

                                            echo $pagination_bc;
                                            ?>
											<img id="imgLoadAjaxBC" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
										</div>
									</td>
								</tr>
                                </tbody>
                            </table>

                   		</div> 
				</div>
			</div>

			<div class="col-plan tab-pane fade" id="bot_users">
            	<p>
					<style>
						input.datepicker.entre {
							position:relative;
							height:auto;
							width:100px;
							opacity:1;
							margin:0 10px;
						}
					</style>
					de: <input class="datepicker entre" type="text" id="from_user" size="10">
					a: <input class="datepicker entre" type="text" id="to_user" size="10">

					<a class="btn btn-primary" id="user_excel" href="{!! URL::to('/bot/download_user/'.$bots[0]->id) !!}">{{ trans('front/bots.user_download') }}</a>
                    <a class="btn btn-primary" id="user_pdf" href="{!! URL::to('/bot/pdf_download/'.$bots[0]->id) !!}">{{ trans('front/bots.pdf_user_download') }}</a>

					<script>
                        $("#user_excel").click(function(){
                            var from = $('#from_user').val().replace(/\//g, "-");;
                            var to = $('#to_user').val().replace(/\//g, "-");;
                        	$('#user_excel').attr("href", "{!! URL::to('/bot/download_user/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
                        	$('#user_pdf').attr("href", "{!! URL::to('/bot/pdf_download/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
                        });
                        $("#user_pdf").click(function(){
                            var from = $('#from_user').val().replace(/\//g, "-");;
                            var to = $('#to_user').val().replace(/\//g, "-");;
                            $('#user_excel').attr("href", "{!! URL::to('/bot/download_user/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
                            $('#user_pdf').attr("href", "{!! URL::to('/bot/pdf_download/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
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
                            <td><?php echo $auv1->created_at;?></td>
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
                                    if($total_pages_activeUser > 0)
                                    {
                                        $prev_u = $page - 1;
                                        $next_u = $page + 1;
                                        $lastpage_u = ceil($total_pages_activeUser/$limitUser);
                                        $lpm1_u = $lastpage_u - 1;
                                    }

                                    $pagination_u = '';
                                    if($lastpage_u >= 1)
                                    {
                                        $pagination_u = '<ul>';

                                        if ($page > 1)
                                            $pagination_u.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationU('."'0','first','".$bots[0]->id."'".')")">&lt;</a>';
                                        else
                                            $pagination_u.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                                        if ($lastpage_u < 7 + ($adjacents * 2))
                                        {
                                            for ($counter_u = 1; $counter_u <= $lastpage_u; $counter_u++)
                                            {
                                                if ($counter_u == $page)
                                                    $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span></li>';
                                                else
                                                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$bots[0]->id."'".')">'.$counter_u.'</a></li>';
                                            }
                                        }
                                        elseif($lastpage_u > 5 + ($adjacents * 2))
                                        {
                                            if($page < 1 + ($adjacents * 2))
                                            {
                                                for ($counter_u = 1; $counter_u < 4 + ($adjacents * 2); $counter_u++)
                                                {
                                                    if ($counter_u == $page)
                                                        $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span>';
                                                    else
                                                        $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$bots[0]->id."'".')">'.$counter_u.'</a></li>';
                                                }
                                                $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lpm1_u."','".$lpm1_u."_no', '".$bots[0]->id."'".')">'.$lpm1_u.'</a></li>';
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lastpage_u."','".$lastpage_u."_no', '".$bots[0]->id."'".')">'.$lastpage_u.'</a></li>';
                                            }
                                            elseif($lastpage_u - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                            {
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'1','1_no', '".$bots[0]->id."'".')">1</a></li>';
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                                                for ($counter_u = $page - $adjacents; $counter_u <= $page + $adjacents; $counter_u++)
                                                {
                                                    if ($counter_u == $page)
                                                        $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span>';
                                                    else
                                                        $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$bots[0]->id."'".')">'.$counter_u.'</a></li>';
                                                }
                                                $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lpm1_u."','".$lpm1_u."_no','".$bots[0]->id."'".')">'.$lpm1_u.'</a></li>';
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lastpage_u."','".$lastpage_u."_no', '".$bots[0]->id."'".')">'.$lastpage_u.'</a></li>';
                                            }
                                            else
                                            {
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'1','1_no','".$bots[0]->id."'".')">1</a></li>';
                                                $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                                $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                                                for ($counter_u = $lastpage_u - (2 + ($adjacents * 2)); $counter_u <= $lastpage_u; $counter_u++)
                                                {
                                                    if ($counter_u == $page)
                                                        $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span>';
                                                    else
                                                        $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$bots[0]->id."'".')">'.$counter_u.'</a></li>';
                                                }
                                            }
                                        }

                                        if ($page < $counter_u - 1)
                                        {
                                            $pagination_u.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationU('."'".$next_u."','".$next_u."_no', '".$bots[0]->id."'".')">&gt;</a></li>';
                                        }
                                        else
                                        {
                                            $pagination_u.= '<li class="active"><span class="current next">&gt;</span>';
                                        }

                                        $pagination_u .= '</ul>';
                                    }

                                    echo $pagination_u;
                                    ?>
									<img id="imgLoadAjaxU" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
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

					<a class="btn btn-primary" id="log_excel" href="{!! URL::to('/bot/download_log/'.$bots[0]->id) !!}">{{ trans('front/bots.log_download') }}</a>
					<a class="btn btn-primary" id="log_pdf" href="{!! URL::to('/bot/log_pdf_download/'.$bots[0]->id) !!}">{{ trans('front/bots.pdf_log_download') }}</a>

					<script>
                        $("#log_excel").click(function(){
                            var from = $('#from_log').val().replace(/\//g, "-");;
                            var to = $('#to_log').val().replace(/\//g, "-");;
                            $('#log_excel').attr("href", "{!! URL::to('/bot/download_log/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
                            $('#log_pdf').attr("href", "{!! URL::to('/bot/log_pdf_download/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
                        });
                        $("#log_pdf").click(function(){
                            var from = $('#from_log').val().replace(/\//g, "-");;
                            var to = $('#to_log').val().replace(/\//g, "-");;
                            $('#log_excel').attr("href", "{!! URL::to('/bot/download_log/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
                            $('#log_pdf').attr("href", "{!! URL::to('/bot/log_pdf_download/'.$bots[0]->id) !!}"+"/"+from+"/"+to);
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
						<td><?php echo $bmv1->first_name.' '.$bmv1->last_name;?></td>
						<td>
                            <?php
                            if(file_exists(public_path().'/uploads/'.$bmv1->text) && !empty($bmv1->text)){
                            ?>
								<button href="#" id="link<?php echo $bmv1->id;?>" data-toggle="modal" data-target="#myModal" src="/uploads/<?=$bmv1->text?>" class="imatge">
									<i class="fa fa-camera" aria-hidden="true"></i>
								</button>

                            <?php
                            }
                            else{
                                echo $bmv1->text;
                            }
                            ?>
						</td>
						<td>
                            <?php
                            if(file_exists(public_path().'/uploads/'.$bmv1->reply_message) && !empty($bmv1->reply_message)){
                            ?>
								<button href="#" id="link<?php echo $bmv1->id;?>" data-toggle="modal" data-target="#myModal" src="/uploads/<?=$bmv1->reply_message?>" class="imatge">
									<i class="fa fa-camera" aria-hidden="true"></i>
								</button>
                        <?php
                        }
                        else{
                            echo $bmv1->reply_message;
                        }
                        ?>
						</td>
						<td><?php echo $bmv1->date;?></td>
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
                                if($total_pages_message > 0)
                                {
                                    $prev_m = $page - 1;
                                    $next_m = $page + 1;
                                    $lastpage_m = ceil($total_pages_message/$limitMessage);
                                    $lpm1_m = $lastpage_m - 1;
                                }

                                $pagination_m = '';
                                if($lastpage_m >= 1)
                                {
                                    $pagination_m = '<ul>';

                                    if ($page > 1)
                                        $pagination_m.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationM('."'0','first','".$bots[0]->id."'".')")">&lt;</a>';
                                    else
                                        $pagination_m.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                                    if ($lastpage_m < 7 + ($adjacents * 2))
                                    {
                                        for ($counter_m = 1; $counter_m <= $lastpage_m; $counter_m++)
                                        {
                                            if ($counter_m == $page)
                                                $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span></li>';
                                            else
                                                $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$bots[0]->id."'".')">'.$counter_m.'</a></li>';
                                        }
                                    }
                                    elseif($lastpage_m > 5 + ($adjacents * 2))
                                    {
                                        if($page < 1 + ($adjacents * 2))
                                        {
                                            for ($counter_m = 1; $counter_m < 4 + ($adjacents * 2); $counter_m++)
                                            {
                                                if ($counter_m == $page)
                                                    $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span>';
                                                else
                                                    $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$bots[0]->id."'".')">'.$counter_m.'</a></li>';
                                            }
                                            $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lpm1_m."','".$lpm1_m."_no', '".$bots[0]->id."'".')">'.$lpm1_m.'</a></li>';
                                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lastpage_m."','".$lastpage_m."_no', '".$bots[0]->id."'".')">'.$lastpage_m.'</a></li>';
                                        }
                                    }
                                    elseif($lastpage_m - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                    {
                                        $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'1','1_no', '".$bots[0]->id."'".')">1</a></li>';
                                        $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                        $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                                        for ($counter_m = $page - $adjacents; $counter_m <= $page + $adjacents; $counter_m++)
                                        {
                                            if ($counter_m == $page)
                                                $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span>';
                                            else
                                                $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$bots[0]->id."'".')">'.$lastpage_m.'</a></li>';
                                        }
                                        $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                                        $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lpm1_m."','".$lpm1_m."_no','".$bots[0]->id."'".')">'.$lpm1_m.'</a></li>';
                                        $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lastpage_m."','".$lastpage_m."_no', '".$bots[0]->id."'".')">'.$lastpage_m.'</a></li>';
                                    }
                                    else
                                    {
                                        $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'1','1_no','".$bots[0]->id."'".')">1</a></li>';
                                        $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'2','2_no', '".$bots[0]->id."'".')">2</a></li>';
                                        $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                                        for ($counter_m = $lastpage_m - (2 + ($adjacents * 2)); $counter_m <= $lastpage_m; $counter_m++)
                                        {
                                            if ($counter_m == $page)
                                                $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span>';
                                            else
                                                $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$bots[0]->id."'".')">'.$counter_m.'</a></li>';
                                        }
                                    }

                                    if ($page < $counter_m - 1)
                                    {
                                        $pagination_m.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationM('."'".$next_m."','".$next_m."_no', '".$bots[0]->id."'".')">&gt;</a></li>';
                                    }
                                    else
                                    {
                                        $pagination_m.= '<li class="active"><span class="current next">&gt;</span>';
                                    }

                                    $pagination_m .= '</ul>';
                                }

                                echo $pagination_m;
                                ?>
								<img id="imgLoadAjaxM" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
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

					<input type="hidden" name="b_bot_id" id="b_bot_id" />
					<p class="lead emoji-picker-container">
						<textarea id="bot_msg" class="form-control textarea-control" name="bot_msg" cols="20" rows="5" placeholder="{{ trans('front/dashboard.enter_message') }}" data-emojiable="true"></textarea>
					</p>


					<br>

					<label> {{ trans('front/dashboard.and_or_attach_an_image') }} </label>

					<input type="file" name="bot_image" id="bot_image" accept="image/*"  />
					<br />

					<input type="submit" name="submit" value="{{ trans('front/dashboard.send') }}" class="btn btn-primary"  />

					{!! Form::close() !!}
				</div>
			</div>

		</div>
	</div>

	<script>

		$(document).ready(function(){
			getCharts();
		});

		function getCharts(){
			$('.loading_img').css('display','block');
			var id = $('#chart_bots').val();
			var chart_time = $('#chart_time').val();
			var chart_details = $('#chart_details').val();
			var token = $('input[name=_token]').val();

			$.ajax({
				url: '<?php echo URL::to('/dashboard/getcharts')?>',
				headers: {'X-CSRF-TOKEN': token},
				data: {bot_id: id, chart_time:chart_time, chart_details:chart_details},
				type:'POST',
				success: function (resp) {
					google.charts.setOnLoadCallback(function(){
						var data_arr = JSON.parse(resp);
						drawChart(data_arr);
						$('.loading_img').css('display','none');
					});
				}
			});

		}

		function changePagination(pageId,liId,botId)
		{
			$('#imgLoadAjax').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_autoresponse')?>/'+botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,botId:botId},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjax').css('display','none');
					$('#a_autoResp').html(resp);
				}
			});
		}
		
		
		function changePaginationCF(pageId,liId,botId)
		{
			$('#imgLoadAjaxCF').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_contact_form')?>/'+botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,botId:botId},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjaxCF').css('display','none');
					$('#c_autoResp').html(resp);
				}
			});
		}
		
		function changePaginationG(pageId,liId,botId)
		{
			$('#imgLoadAjaxG').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_gallery')?>/'+botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,botId:botId},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjaxG').css('display','none');
					$('#g_autoResp').html(resp);
				}
			});
		}
		
		function changePaginationCH(pageId,liId,botId)
		{
			$('#imgLoadAjaxCH').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_channel')?>/'+botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,botId:botId},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjaxCH').css('display','none');
					$('#ch_autoResp').html(resp);
				}
			});
		}
		
		function changePaginationU(pageId,liId,botId)
		{
			$('#imgLoadAjaxU').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_bot_active_user')?>/'+botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,botId:botId},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjaxU').css('display','none');
					$('#u_autoResp').html(resp);
				}
			});
		}
		
		
		function changePaginationM(pageId,liId,botId)
		{
			$('#imgLoadAjaxM').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_bot_message')?>/'+botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,botId:botId},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjaxM').css('display','none');
					$('#m_autoResp').html(resp);
				}
			});
		}
		
		
		function changePaginationBC(pageId,liId,botId)
		{
			$('#imgLoadAjaxBC').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/bot/get_bot_command')?>/'+botId,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,botId:botId},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjaxBC').css('display','none');
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

		function mypopup_botfunction(bot_id){
			$('#bot_msg').css('border','1px solid #ccc');
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


		$(document).ready(function(e) {
			$("form#send_msg_bot").submit(function(event){
				event.preventDefault();

				var chk = true;
				var bot_msg = $('#bot_msg').val();
				var b_bot_id = $('#b_bot_id').val();
				var bot_img = $('#bot_image').val();

				if(bot_msg == '' && bot_img == ''){
					chk = false;
					$('#bot_msg').css('border','1px solid #ff0000');
				}
				else{
					$('#bot_msg').css('border','1px solid #ccc');
				}

				var formData = new FormData($(this)[0]);
				var token_new = $('input[name=_token]').val();

				if(chk){
					$('#imgLoad').css('display','block');
					$.ajax({
						url: '<?php echo URL::to('/dashboard/sendbotmessage')?>',
						headers: {'X-CSRF-TOKEN': token_new},
						data:formData,
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						type:'POST',
						success: function (resp) {
							$('#imgLoad').css('display','none');
							$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+resp);
							$('.alert-new').css('display','block');
							$('#alertMsg').css('display','block');
							$('#myModalBot').modal('hide');
						},
						error: function (request, status, error) {
							$('#imgLoad').css('display','none');
							$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Forbidden: Some error occured');
							$('.alert-new').css('display','block');
							$('#alertMsg').css('display','block');
						}
					});
				}
				return false;
			});
		});
	</script>
	<script>
		$(document).ready(function(e) {
            $('#bot_messages').on('click','.imatge', function() {
                //alert($(this).attr("src"));
                $("#showImg").empty();
                var image = $(this).attr("src");

                $("#showImg").append("<img class='img-responsive' src='" + image + "' />");

            });
			$('.chat_box').css('display','block');
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
@stop