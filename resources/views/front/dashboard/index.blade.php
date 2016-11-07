@extends('front.template')
@section('main')

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['line']});
        function drawChart(data_arr) {
            var data = google.visualization.arrayToDataTable(data_arr);

            var options_fullStacked = {
                title: '',
                chartArea:{left:0,top:10,bottom:0,width:"100%",height:"100%"},
                curveType: 'function',
                tooltip: {
                    isHtml: true
                },
                legend: { position: 'bottom' }
            };


            var chart = new google.charts.Line(document.getElementById('chart_div'));
            chart.draw(data, options_fullStacked);
        }
    </script>

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
        @include('front.top')

        <div class="my_account">
            <h4>{!! Auth::user()->first_name.' '.Auth::user()->last_name !!},</h4>
            <p>{!! trans('front/dashboard.sub_heading') !!}</p>
        </div>


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
                            <option value="this_week" selected>{{ trans('front/dashboard.this_week') }} </option>
                            <option value="this_month">{{ trans('front/dashboard.this_month') }}</option>
                            <option value="this_year">{{ trans('front/dashboard.this_year') }}</option>
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
                    <ul class="row">
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
                                        <span class="count_users"><b  data-toggle="tooltip" title="{{ trans('front/dashboard.active_users') }}">{!! $bv1->total_usrs !!}</b></span>
                                        <span class="count_messages"><b  data-toggle="tooltip" title="{{ trans('front/dashboard.activity_last_days') }}">{!! $bv1->total_msg !!}</b></span>

                                    </a>
                                </p>
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="mypopup_botfunction('<?php echo $bv1->id;?>');">{{ trans('front/dashboard.send_message') }}</a>
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
                    <ul class="row">
                        <?php
                        if (isset($chanel) && !empty($chanel)) {
                        foreach ($chanel as $chanelKey => $myChanel) {
                        ?>
                        <li class="col-sm-4">
                            <div class="days_preparing">
                                <h4><a href="{!! URL::to('/my_channel/detail/'.$myChanel->id) !!}">{{ $myChanel->name }}</a></h4>
                                <p class="h2">
                                    <a href="{!! URL::to('/my_channel/detail/'.$myChanel->id) !!}">
                                        {!! HTML::image('img/front/days_counting_img.png') !!}<span>{!! $myChanel->total_msg !!}</span>
                                    </a>
                                </p>

                                <a href="javascript:void(0);" class="btn btn-primary" onclick="mypopupfunction('<?php echo $myChanel->id;?>');">{{ trans('front/dashboard.send_message') }}</a>
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
                                    <b><a href="{!! URL::to('/bot/detail/'.$rv1->id) !!}"><?php echo $rv1->username;?></a></b>.
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
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('front/dashboard.send_a_message') }}</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => '','id' =>'']) !!}

                    <input type="hidden" id="chat_id" />

                    <select id="botID" class="form-control">
                        <option value="">Select bot</option>
                        <?php
                        if (isset($bots) && !empty($bots)) {
                        foreach ($bots as $b1 => $bv1) {
                        ?>
                        <option value="<?php echo $bv1->id; ?>"><?php echo $bv1->username;?></option>
                        <?php
                        }
                        }
                        ?>
                    </select>

                    <br>

                    <textarea id="channel_msg" class="form-control" cols="20" rows="5" placeholder="{{ trans('front/dashboard.enter_message') }}"></textarea>

                    <br>

                    <a href="javascript:void(0);" class="btn btn-primary" onclick="sendMsg();">{{ trans('front/dashboard.send') }}</a>

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
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('front/dashboard.send_a_message') }}</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => '','id' =>'']) !!}

                    <input type="hidden" id="b_bot_id" />

                    <textarea id="bot_msg" class="form-control" cols="20" rows="5" placeholder="{{ trans('front/dashboard.enter_message') }}"></textarea>

                    <br>

                    <a href="javascript:void(0);" class="btn btn-primary" onclick="sendMsgBot();">{{ trans('front/dashboard.send') }}</a>

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

        function mypopup_botfunction(bot_id){
            $('#bot_msg').css('border','1px solid #ccc');
            $('#b_bot_id').val(bot_id);

            $('#bot_msg').val('');

            $('#myModalBot').modal();
        }


        function mypopupfunction(channel_id){
            $('#botID').css('border','1px solid #ccc');
            $('#channel_msg').css('border','1px solid #ccc');
            $('#chat_id').val(channel_id);

            $('#botID').val('');
            $('#channel_msg').val('');

            $('#myModal').modal();
        }

        function sendMsg(){
            var chk = true;
            var botID = $('#botID').val();
            var channel_msg = $('#channel_msg').val();
            var channel_id = $('#chat_id').val();

            if(botID == ''){
                chk = false;
                $('#botID').css('border','1px solid #ff0000');
            }
            else{
                $('#botID').css('border','1px solid #ccc');
            }

            if(channel_msg == ''){
                chk = false;
                $('#channel_msg').css('border','1px solid #ff0000');
            }
            else{
                $('#channel_msg').css('border','1px solid #ccc');
            }


            if(chk){
                var token_new = $('input[name=_token]').val();
                $.ajax({
                    url: '<?php echo URL::to('/dashboard/sendmessage')?>',
                    headers: {'X-CSRF-TOKEN': token_new},
                    data: {bot_id: botID, channel_msg:channel_msg,channel_id:channel_id},
                    type:'POST',
                    success: function (resp) {
                        alert(resp);
                        $('#myModal').modal('hide');
                    },
                    error: function (request, status, error) {
                        alert('Forbidden: bot is not a member of the channel chat');
                    }
                });
            }
        }


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
                    url: '<?php echo URL::to('/dashboard/sendbotmessage')?>',
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

