@extends('front.template')
@section('main')

{!! HTML::style('css/front/simplePagination.css') !!}
{!! HTML::script('js/front/jquery.simplePagination.js') !!}

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});

	function drawChart(data_arr) {
		var data = google.visualization.arrayToDataTable(data_arr);

		var options_fullStacked = {
			title: '',
			chartArea:{left:40,top:5,bottom:60,width:"100%",height:"100%"},
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


    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      <div class="my_account col-user">
          <div class="col-lg-2">
              <img src="{{URL::asset('img/front/bot.png')}}">
          </div>
          <div class="col-lg-10">
              <ul>
                  <li style="font-size:20px"><h4>{!! $bots[0]->username !!}</h4></li>
                  <li><b>{{ trans('front/bots.bot_token') }}:</b> {!! $bots[0]->bot_token !!}</li>
              </ul>
              <br>
              <p>
                  <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}" class="btn btn-primary">{!! trans('front/dashboard.edit_bot') !!}</a>
                  <a href="{!! URL::to('/command/create/'.$bots[0]->id) !!}" class="btn btn-primary">{!! trans('front/dashboard.create_command') !!}</a>
                  <a href="javascript:void(0);" class="btn btn-primary" onclick="mypopup_botfunction('<?php echo $bots[0]->id;?>');">{{ trans('front/dashboard.send_message') }}</a>
              </p>
          </div>
      </div>
      
      
      <div class="my_account">
      	<div class="col-lg-12 col-dash">
      		<div class="status">
        
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
      </div>

      
    
      
      <div class="col-lg-12">
        <div class="col-plan col-lg-6">
          <h2><?php echo $bots[0]->autoresponse; ?> ({{ trans('front/bots.autoresponses') }})</h2>
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
                            <a class="btn btn-primary" href="{!! URL::to('/command/autoresponse_edit/'.$v2->id) !!}">{{ trans('front/bots.update_command') }}</a>
                            <a class="btn btn-primary" href="{!! URL::to('/command/autoresponse_delete/'.$v2->type_id.'/'.$v2->id) !!}" onclick="return confirm('Are you sure want to delete this command?');">{{ trans('front/bots.delete_command') }}</a>
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
            </tbody>
          </table>
          <div id="botAutoresponseNavPosition"></div>
        </div>
        
        <div class="col-plan col-lg-6">
          <h2><?php echo $bots[0]->contact_form; ?> ({{ trans('front/bots.contact_form') }})</h2>
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
                          <td><a class="btn btn-primary" href="{!! URL::to('/command/contactform_edit/'.$v3->id) !!}">{{ trans('front/bots.update_command') }}</a>
                          <a class="btn btn-primary" href="{!! URL::to('/command/contactform_delete/'.$v3->type_id.'/'.$v3->id) !!}" onclick="return confirm('Are you sure want to delete this contact form?');">{{ trans('front/bots.delete_command') }}</a>
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
            </tbody>
          </table>
          <div id="botContactFormNavPosition"></div>
        </div>
        <div style="clear:both"></div>
        <div class="col-plan col-lg-6">
          <h2><?php echo $bots[0]->galleries; ?> ({{ trans('front/bots.galleries') }})</h2>
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
                          	<a class="btn btn-primary" href="{!! URL::to('/command/gallery_edit/'.$v4->id) !!}">{{ trans('front/bots.update_command') }}</a>
                          	<a class="btn btn-primary" href="{!! URL::to('/command/gallery_delete/'.$v4->type_id.'/'.$v4->id) !!}" onclick="return confirm('Are you sure want to delete this gallery?');">{{ trans('front/bots.delete_command') }}</a>
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
            </tbody>
          </table>
          <div id="botGalleryNavPosition"></div>
        </div>
        
        <div class="col-plan col-lg-6">
          <h2><?php echo $bots[0]->channels; ?> ({{ trans('front/bots.channels') }})</h2>
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
                          	<a class="btn btn-primary" href="{!! URL::to('/command/chanel_edit/'.$v5->id) !!}">{{ trans('front/bots.update_command') }}</a>
                          	<a class="btn btn-primary" href="{!! URL::to('/command/chanel_delete/'.$v5->type_id.'/'.$v5->id) !!}" onclick="return confirm('Are you sure want to delete this channel?');">{{ trans('front/bots.delete_command') }}</a>
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
            </tbody>
          </table>
          <div id="botChannelsNavPosition"></div>
        </div>
        <div style="clear:both"></div>
        
        <div class="col-plan">
          <h2>{{ trans('front/bots.active_user') }}</h2>
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
            </tbody>
          </table>
          <div id="activeUserNavPosition"></div>
        </div>
        
        <div class="col-plan">
          <h2>{{ trans('front/bots.messages_activity') }}</h2>
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
                          <td><?php echo $bmv1->text;?></td>
                          <td><?php echo $bmv1->reply_message;?></td>
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
            </tbody>
          </table>
          <div id="messageNavPosition"></div>
        </div>
        
      
</div>
      
  </div>
  
  <div id="myModalBot" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
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
	
	
  	jQuery(function($) {
		var pageParts = $("#botAutoresponse tbody tr");
		var numPages = pageParts.length;
		var perPage = 4;
		pageParts.slice(perPage).hide();
		
		$("#botAutoresponseNavPosition").pagination({
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
	
	jQuery(function($) {
		var pageParts = $("#botGallery tbody tr");
		var numPages = pageParts.length;
		var perPage = 4;
		pageParts.slice(perPage).hide();
		
		$("#botGalleryNavPosition").pagination({
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
	
	
	jQuery(function($) {
		var pageParts = $("#botChannels tbody tr");
		var numPages = pageParts.length;
		var perPage = 4;
		pageParts.slice(perPage).hide();
		
		$("#botChannelsNavPosition").pagination({
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
	
	
  	jQuery(function($) {
		var pageParts = $("#activeUser tbody tr");
		var numPages = pageParts.length;
		var perPage = 10;
		pageParts.slice(perPage).hide();
		
		$("#activeUserNavPosition").pagination({
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
	
	
  	jQuery(function($) {
		var pageParts = $("#message_activity tbody tr");
		var numPages = pageParts.length;
		var perPage = 10;
		pageParts.slice(perPage).hide();
		
		$("#messageNavPosition").pagination({
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
							alert(resp);
							$('#myModalBot').modal('hide');
						},
						error: function (request, status, error) {
							alert('Forbidden: Some error occured');
						}
					});
				}				
				return false;
			});
        });
  </script>
<script>
    $(document).ready(function(e) {
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
@stop