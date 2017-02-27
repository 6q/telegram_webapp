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
			<div class="week canal">
				{{ trans('front/dashboard.messages_sent_the_last') }}
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

				<a href="javascript:void(0);" class="btn btn-primary" onclick="mypopupfunction('<?php echo $chanels[0]->id;?>');"><i class="fa fa-paper-plane" aria-hidden="true"></i> {{ trans('front/dashboard.send_message') }}</a>
				<a href="{!! URL::to('/my_channel/update_channel/'.$chanels[0]->id) !!}" class="btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> {!! trans('front/dashboard.edit_channel') !!}</a>

		</div>
	</div>



    
    <div class="col-lg-12">
         <div class="col-plan">
          <h2>{{ trans('front/MyChannel.messages_activity') }}</h2>
          <div id="a_autoResp">
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
                          <td>
                              <?php
								  $imatge = str_replace("/usr/home/app.citymes.com/web/uploads/","",$v2->message);
                              if(file_exists(public_path().'/uploads/'.$imatge) && !empty($v2->message)){
                              ?>
							  <button href="#" id="link<?php echo $v2->id;?>" data-toggle="modal" data-target="#myModal" src="/uploads/<?=$imatge?>" class="imatge">
								  <i class="fa fa-camera" aria-hidden="true"></i>
							  </button>
                              <?php
                              }
                              else{
                                  echo $v2->message;
                              }
                              ?>
						  </td>
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
    	      <div id="channelMessagesNavPosition" class="light-theme simple-pagination">
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
          </div>            
			          </div>
        </div>
    </div>


</div>

<div id="alertMsg" style="display:none;">
    <div id="resp" class="alert-new alert-success-new alert-dismissible" role="alert">
    </div>
</div>

<div id="myModalChannel" class="modal fade" role="dialog" style="display:none;">
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
	
	
	function changePagination(pageId,liId,channelId)
	{
		$('#imgLoadAjax').css('display','inline-block');
		var token = $('input[name=_token]').val();
		$.ajax({
			url: '<?php echo URL::to('/my_channel/get_channel_msg')?>/'+channelId,
			headers: {'X-CSRF-TOKEN': token},
			data: {pageId: pageId,channelId:channelId},
			type:'POST',
			success: function (resp) {
				$('#imgLoadAjax').css('display','none');
				$('#a_autoResp').html(resp);
			}
		});
	}
	
	
	 function mypopupfunction(channel_id){
		//$('#botID').css('border','1px solid #ccc');
		$('#channel_msg').css('border','1px solid #ccc');
		$('#chat_id').val(channel_id);

		//$('#botID').val('');
		$('#channel_msg').val('');

		$('#myModalChannel').modal();
	}
		
		
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
							$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+resp);
							$('.alert-new').css('display','block');
							$('#alertMsg').css('display','block');
							$('#myModalChannel').modal('hide');
						},
						error: function (request, status, error) {
							$('#imgLoad').css('display','none');
							$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Forbidden: Some error occured');
							$('.alert-new').css('display','block');
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