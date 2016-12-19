@extends('front.template')
@section('main')

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        function drawChart(data_arr) {
            var data = google.visualization.arrayToDataTable(data_arr);

            var options_fullStacked = {
                title: '',
                chartArea:{left:40,top:10,bottom:50,width:"100%",height:"100%"},
                curveType: 'function',
                tooltip: {
                    isHtml: true
                },
                vAxis: {
                    viewWindow: {
                        min:0
                    }
                },
                lineWidth: 3,
                pointSize: 5,
                legend: { position: 'bottom' },
            };


            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options_fullStacked);
        }
    </script>

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
        @include('front.top')

        <div class="col-dashboard">
            <div class="col-lg-9 col-dash">

                <div class="status">
                    <h4>{{ trans('front/dashboard.global_statistics') }}</h4>

                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'status_dropdown']) !!}

                    <div class="week">
                        <select id="chart_details" onchange="getCharts()">
                            <option value="recieved_messages" selected>{{ trans('front/dashboard.recieved_messages') }}</option>
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
                        <select id="chart_bots" onchange="getCharts()">
                            <option value="all_bots" selected>{{ trans('front/dashboard.all_bot') }}</option>
                            <?php
                            if(isset($total_bots) && !empty($total_bots)){
                            foreach($total_bots as $tbk1 => $tbv1){
                            ?>
                            <option value="<?php echo $tbv1->id; ?>"><?php echo $tbv1->username;?></option>
                            <?php
                            }
                            }
                            ?>
                        </select>
                    </div>

                    {!! Form::close() !!}

                </div>
				
                <div class="graph">
                	<img src="{{URL::asset('img/balls.gif')}}" class="loading_img">
                    <div id="chart_div" style="height: 300px;"></div>
                </div>

                <div class="col-my-content">
                    <h3 id="my_bots">{{ trans('front/dashboard.my_bots') }}</h3>
                    <ul class="row bots">
                        <?php
                        if (isset($bots) && !empty($bots)) {
                        foreach ($bots as $b1 => $bv1) {
                        ?>
                        <li class="col-sm-4">
                            <div class="days_preparing">
                                <h4><a href="{!! URL::to('/bot/detail/'.$bv1->id) !!}">{{ $bv1->username }}</a></h4>
                                <p class="h2">
                                    <a href="{!! URL::to('/bot/detail/'.$bv1->id) !!}">
                                        {!! HTML::image('img/front/days_counting_img.png') !!}
                                        <span class="count_users"><b  data-toggle="tooltip" title="{{ trans('front/dashboard.active_users') }}">{!! $bv1->total_usrs !!}<i class="fa fa-user-circle" aria-hidden="true" style="font-size:15px;"></i></b></span>
                                        <span class="count_messages"><b data-toggle="tooltip" title="{{ trans('front/dashboard.activity_last_days') }}">{!! $bv1->total_msg !!} <i class="fa fa-line-chart" aria-hidden="true" style="font-size:12px;"></i></b></span>

                                    </a>
                                </p>
                                <a href="{!! URL::to('/bot/detail/'.$bv1->id) !!}" class="btn btn-primary">{{ trans('front/dashboard.manage') }}</a>
                            </div>
                        </li>
                        <?php
                        }
                        }
                        ?>

                        <li class="col-sm-4">
                            <div class="days_preparing">
                                <h1 class="add_plus">{!! link_to_route('bot.create', '+', [], ['class' => '']) !!}</h1>
                            </div>
                        </li>
                    </ul>

                    <h3 id="my_channels">{{ trans('front/dashboard.my_channels') }}</h3>
                    <ul class="row canals">
                        <?php
                        if (isset($chanel) && !empty($chanel)) {
                        foreach ($chanel as $chanelKey => $myChanel) {
                        ?>
                        <li class="col-sm-4">
                            <div class="days_preparing">
                                <h4><a href="{!! URL::to('/my_channel/detail/'.$myChanel->id) !!}">{{ $myChanel->name }}</a></h4>
                                <p class="h2">
                                    <a href="{!! URL::to('/my_channel/detail/'.$myChanel->id) !!}">
                                        {!! HTML::image('img/front/days_counting_img_channel.png') !!}<span>{!! $myChanel->total_msg !!}</span>
                                    </a>
                                </p>

                                <a href="{!! URL::to('/my_channel/detail/'.$myChanel->id) !!}" class="btn btn-primary">{{ trans('front/dashboard.manage') }}</a>
                            </div>
                        </li>
                        <?php
                        }
                        }
                        ?>
                        <li class="col-sm-4">
                            <div class="days_preparing">
                                <h1 class="add_plus">{!! link_to_route('my_channel.create', '+', [], ['class' => '']) !!}</h1>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 side_dashboard_content col-right_tab">
                <h3>{{ trans('front/dashboard.recent_activity') }}</h3>
                <div class="search">
                    {!! Form::open(['url' => $Form_action, 'method' => 'get','enctype'=>"multipart/form-data", 'class' => '','id' =>'']) !!}
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    <input type="search" placeholder="{{ trans('front/dashboard.search') }}" value="{!! $search !!}" name="search">
                    {!! Form::close() !!}
                </div>

                <ul>
                    <?php

                    if(isset($rec_usrs) && !empty($rec_usrs)){
                    foreach($rec_usrs as $rk1 => $rv1){
                    ?>
                    <li>
                        <div class="side_content">
                            <p>
                                <?php echo get_timeago(strtotime($rv1->created_at))?>
                                <b><?php echo $rv1->first_name;?> <?php echo $rv1->last_name;?></b>
                                    {{ trans('front/dashboard.joined_the_bot') }}
                                    <b><a href="{!! URL::to('/bot/detail/'.$rv1->bot_id) !!}"><?php echo $rv1->bot_username;?></a></b>.
                            </p>
                        </div>
                    </li>
                    <?php
                    }
                    }
                    ?>
                </ul>

            <?php
            function get_timeago( $ptime )
            {
                $estimate_time = time() - $ptime;

                if( $estimate_time < 1 )
                {
                    return trans('front/dashboard.less_than_1_second_ago');
                }

                $condition = array(
                        12 * 30 * 24 * 60 * 60  =>  trans('front/dashboard.year'),
                        30 * 24 * 60 * 60       =>  trans('front/dashboard.month'),
                        24 * 60 * 60            =>  trans('front/dashboard.day'),
                        60 * 60                 =>  trans('front/dashboard.hour'),
                        60                      =>  trans('front/dashboard.minute'),
                        1                       =>  trans('front/dashboard.second')
                );

                foreach( $condition as $secs => $str )
                {
                    $d = $estimate_time / $secs;

                    if( $d >= 1 )
                    {
                        $r = round( $d );
                        return trans('front/dashboard.about').' ' . $r . ' ' . $str . ' ' . trans('front/dashboard.ago');
                    }
                }
            }

            ?>

            <!--<div class="view_log"><a href="{!! URL::to('/recent_activity') !!}">{{ trans('front/dashboard.view_log') }}</a></div>-->
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            	<img id="imgLoadChannel" src="{{URL::asset('img/balls.gif')}}" class="loading2_img" style="display:none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('front/dashboard.send_a_message') }}</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => '','id' =>'send_channel_msg']) !!}

                    <input type="hidden" id="chat_id" name="chat_id" />
                    <input type="hidden" id="bot_id" name="botID" />
                    
                    <!--
                    <select id="botID" name="botID" class="form-control">
                        <option value="">Select bot</option>
                        <?php
						/*
                        if (isset($bots) && !empty($bots)) {
                        foreach ($bots as $b1 => $bv1) {
                        ?>
                        <option value="<?php echo $bv1->id; ?>"><?php echo $bv1->username;?></option>
                        <?php
                        }
                        }
						*/
                        ?>
                    </select>

                    <br>
                    -->

                    <textarea id="channel_msg" name="channel_msg" class="form-control" cols="20" rows="5" placeholder="{{ trans('front/dashboard.enter_message') }}"></textarea>
                    
                    <label> OR </label>
                    
                    <input type="file" name="channel_image" id="channel_image" accept="image/*"  />
                    <br />

                    <input id="sendChannelBtn" type="submit" name="submit" value="{{ trans('front/dashboard.send') }}" class="btn btn-primary"  />


                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>



    <!-- Modal -->
    <div id="myModalBot" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
            	<img id="imgLoad" src="{{URL::asset('img/balls.gif')}}" class="loading2_img" style="display:none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('front/dashboard.send_a_message') }}</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => '','id' =>'send_msg_bot']) !!}

                    <input type="hidden" name="b_bot_id" id="b_bot_id" />

                    <textarea id="bot_msg" class="form-control" name="bot_msg" cols="20" rows="5" placeholder="{{ trans('front/dashboard.enter_message') }}"></textarea>

                    <br>
                    
                    <label> OR </label>
                    
                    <input type="file" name="bot_image" id="bot_image" accept="image/*"  />
                    <br />
                    
                    <input id="sendBotBtn" type="submit" name="submit" value="{{ trans('front/dashboard.send') }}" class="btn btn-primary"  />

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
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(function(){
                        var data_arr = JSON.parse(resp);
                        drawChart(data_arr);
						$('.loading_img').css('display','none');
                    });
                }
            });

        }

    </script>

    <!--<div class="row">

  {!! link_to_route('bot.create', trans('front/bots.create'), [], ['class' => 'btn btn-success btn-block btn']) !!}

            </div>-->
    <style>
        #status_dropdown.form-horizontal.panel {
            float: right;
        }
        .week {
            float: left;
        }
    </style>
@stop

