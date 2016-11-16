@extends('front.template')
@section('main')

{!! HTML::style('css/front/simplePagination.css') !!}
{!! HTML::script('js/front/jquery.simplePagination.js') !!}


    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      <div class="my_account col-user">
        <ul>
        <li><p>{{ trans('front/bots.name') }}</p> : <h4>{!! $bots[0]->username !!}</h4></li>
        <li><p>{{ trans('front/bots.bot_token') }}</p> : <h4>{!! $bots[0]->bot_token !!}</h4></li>
        <li>
        <?php
          if(isset($bots[0]->bot_image) && !empty($bots[0]->bot_image)){
          ?>
              <p>{{ trans('front/bots.image') }}</p> : {!! HTML::image('uploads/'.$bots[0]->bot_image) !!}
          <?php
          }
        ?></li>
           </ul>
        <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}" class="btn btn-primary">{!! trans('front/dashboard.edit_bot') !!}</a> 
        <a href="{!! URL::to('/command/create/'.$bots[0]->id) !!}" class="btn btn-primary">{!! trans('front/dashboard.create_command') !!}</a>
        
        <a href="javascript:void(0);" class="btn btn-primary" onclick="mypopup_botfunction('<?php echo $bots[0]->id;?>');">{{ trans('front/dashboard.send_message') }}</a>
      </div>


      
    
      
      <div class="col-lg-12">
        <div class="col-plan col-lg-6">
          <h2>{{ trans('front/bots.autoresponse') }}</h2>
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
          <h2>{{ trans('front/bots.contact_form') }}</h2>
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
          <h2>{{ trans('front/bots.galleries') }}</h2>
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
          <h2>{{ trans('front/bots.channels') }}</h2>
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
  	jQuery(function($) {
		var pageParts = $("#botAutoresponse tbody tr");
		var numPages = pageParts.length;
		var perPage = 5;
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
		var perPage = 5;
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
		var perPage = 5;
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
		var perPage = 5;
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
		var perPage = 5;
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
		var perPage = 5;
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
  
  <script type="text/javascript"><!--

  	var pager_botAutoresponse = new Pager('botAutoresponse', 4);
	pager_botAutoresponse.init(); 
	pager_botAutoresponse.showPageNav('pager_botAutoresponse', 'botAutoresponseNavPosition'); 
	pager_botAutoresponse.showPage('pager_botAutoresponse',1);
	
	var pager_botContactForm = new Pager('botContactForm', 4);
	pager_botContactForm.init(); 
	pager_botContactForm.showPageNav('pager_botContactForm', 'botContactFormNavPosition'); 
	pager_botContactForm.showPage('pager_botContactForm',1);
	
	var pager_botGallery = new Pager('botGallery', 4);
	pager_botGallery.init(); 
	pager_botGallery.showPageNav('pager_botGallery', 'botGalleryNavPosition'); 
	pager_botGallery.showPage('pager_botGallery',1);
	
	/*
	var pager_botChannels = new Pager('botChannels', 4);
	pager_botChannels.init(); 
	pager_botChannels.showPageNav('pager_botChannels', 'botChannelsNavPosition'); 
	pager_botChannels.showPage('pager_botChannels',1);
	*/
    //-->
	
	
	
	
	
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
  

<style>
  .thumb {
    width: 20%;
  }
</style>
@stop