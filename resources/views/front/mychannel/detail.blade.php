@extends('front.template')
@section('main')

{!! HTML::style('css/front/simplePagination.css') !!}
{!! HTML::script('js/front/jquery.simplePagination.js') !!}





<div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

    @include('front.top')
	<div id="bot_statistics">
		<script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            function drawChart(data_arr) {
                var data = google.visualization.arrayToDataTable(data_arr);

                var options_fullStacked = {
                    title: '',
                    chartArea:{left:0,top:0,bottom:10,width:"100%",height:"100%",background:"#000"},
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
                    legend: { position: 'bottom' },

                };


                var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                chart.draw(data, options_fullStacked);
            }
		</script>
		<div class="status">

			{!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'status_dropdown']) !!}
			<div class="week">
				<select id="chart_time" onchange="getCharts()">
					<option value="10_days" selected>{{ trans('front/dashboard.ten_days') }}</option>
					<option value="30_days">{{ trans('front/dashboard.thirty_days') }}</option>
					<option value="90_days">{{ trans('front/dashboard.ninety_days') }}</option>
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
			<img src="{{URL::asset('img/front/channel.png')}}">
		</div>
		<div class="col-lg-10">
			<ul>
				<li style="font-size:20px"><h4>{!! $chanels[0]->name !!}</h4></li>
				<li>
					<b>{{ trans('front/MyChannel.share_link') }}:</b> <a href="{!! $chanels[0]->share_link !!}" target="_blank">{!! $chanels[0]->share_link !!}</a>
				</li>

				<li>
				</li>
			</ul>
			<br>
			<p>
				<a href="javascript:void(0);" class="btn btn-primary" onclick="mypopupfunction('<?php echo $chanels[0]->id;?>');"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{ trans('front/dashboard.send_message') }}</a>
				<a href="{!! URL::to('/my_channel/update_channel/'.$chanels[0]->id) !!}" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> {!! trans('front/dashboard.edit_channel') !!}</a>
			</p>
		</div>
	</div>



    
    <div class="col-lg-12">
         <div class="col-plan">
          <h2>{{ trans('front/MyChannel.messages_activity') }}</h2>
          <table id="channelMessages">
            <thead>
              <tr>
                <th>{{ trans('front/MyChannel.channel_name') }}</th>
                <th>{{ trans('front/MyChannel.send_message') }} </th>
                <th>{{ trans('front/MyChannel.send_date') }} </th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($chanelMesg)){
                  foreach($chanelMesg as $d2 => $v2){
                    ?>
                        <tr>
                          <td><?php echo $v2->channel_name;?></td>
                          <td><?php echo $v2->message;?></td>
                          <td><?php echo $v2->send_date;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/MyChannel.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
          <div id="channelMessagesNavPosition"></div>
        </div>
    </div>


</div>

<div id="myModal" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
            	<img id="imgLoad" src="{{URL::asset('img/balls.gif')}}" class="loading2_img" style="display:none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('front/dashboard.send_a_message') }}</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => '','id' =>'send_channel_msg']) !!}

                    <input type="hidden" id="chat_id" name="chat_id" />
                    <input type="hidden" id="bot_id" name="botID" value="<?php echo $chanels[0]->bot_id; ?>" />

					<!--
                    <select id="botID" name="botID" class="form-control">
                        <option value="">Select bot</option>
                        <?php /*
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

					<p class="lead emoji-picker-container">
						<textarea id="bot_msg" class="form-control textarea-control" name="channel_msg" cols="20" rows="5" placeholder="{{ trans('front/dashboard.enter_message') }}" data-emojiable="true"></textarea>
					</p>

                    <label> {{ trans('front/dashboard.and_or_attach_an_image') }} </label>
                    
                    <input type="file" name="channel_image" id="channel_image" accept="image/*"  />
                    <br />

                    <input type="submit" name="submit" value="{{ trans('front/dashboard.send') }}" class="btn btn-primary"  />


                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

<script type="text/javascript">
	
	$(document).ready(function(){
		getCharts();
	});
	
	function getCharts(){
		$('.loading_img').css('display','block');
		var id = '<?php echo $chanels[0]->id;?>';
		var chart_time = $('#chart_time').val();
		var token = $('input[name=_token]').val();
	
		$.ajax({
			url: '<?php echo URL::to('/my_channel/getchannelcharts')?>',
			headers: {'X-CSRF-TOKEN': token},
			data: {channel_id:id,chart_time:chart_time},
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
	
	
	jQuery(function($) {
		var pageParts = $("#channelMessages tbody tr");
		var numPages = pageParts.length;
		var perPage = 10;
		pageParts.slice(perPage).hide();
		
		$("#channelMessagesNavPosition").pagination({
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
	
	
	 function mypopupfunction(channel_id){
		//$('#botID').css('border','1px solid #ccc');
		$('#channel_msg').css('border','1px solid #ccc');
		$('#chat_id').val(channel_id);

		//$('#botID').val('');
		$('#channel_msg').val('');

		$('#myModal').modal();
	}
	
	/*
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
                    url: '<?php // echo URL::to('/dashboard/sendmessage')?>',
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
		*/
		
		
		$(document).ready(function(e) {
            $("form#send_channel_msg").submit(function(event) {
				event.preventDefault();
				
				var chk = true;
				//var botID = $('#botID').val();
    	        var channel_msg = $('#channel_msg').val();
	            var channel_id = $('#chat_id').val();
				var channel_image = $('#channel_image').val();
				
				/*
				if(botID == ''){
					chk = false;
					$('#botID').css('border','1px solid #ff0000');
				}
				else{
					$('#botID').css('border','1px solid #ccc');
				}
				*/
	
				if(channel_msg == '' && channel_image == ''){
					chk = false;
					$('#channel_msg').css('border','1px solid #ff0000');
				}
				else{
					$('#channel_msg').css('border','1px solid #ccc');
				}
				
				
				var formData = new FormData($(this)[0]);
				var token_new = $('input[name=_token]').val();
				
				if(chk){
					$('#imgLoad').css('display','block');
					$.ajax({
						url: '<?php echo URL::to('/dashboard/sendmessage')?>',
						headers: {'X-CSRF-TOKEN': token_new},
						data:formData,
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						type:'POST',
						success: function (resp) {
							$('#imgLoad').css('display','none');
							alert(resp);
							$('#myModal').modal('hide');
						},
						error: function (request, status, error) {
							$('#imgLoad').css('display','none');
							alert('Forbidden: Some error occured');
						}
					});
				}				
				return false;
            });
        });
  </script>

<style>
    .thumb {
        width: 20%;
    }
</style>
@stop