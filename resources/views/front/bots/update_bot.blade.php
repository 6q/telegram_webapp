@extends('front.template')
@section('main')


    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      {!! Form::open(['url' => 'bot/update_bot/'.$bot->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'payment-form']) !!}
      
      {!! Form::hidden('id', $bot->id, array('id' => 'bot')) !!}
      
        <div id="row2">
          <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/bots.telegram') }}</span></h4>
            <h5>{{ trans('front/bots.create') }}</h5>
          </div>
          
          <div class="buying">
              <div class="create_bot">
                <div class="crete_bot_form">
                  <ul>
                    <li>
                      <span>{{ trans('front/bots.bot_nick_name') }} <a href="javascript:void(0);" onclick="mypopupinfo('nickNameModal');">{!! HTML::image('img/front/icon.png') !!}</a></span>
                      <label>{!! Form::control('text', 0, 'nick_name', $errors,'',$bot->nick_name) !!}</label>
                    </li>
                    
                    <li>
                      <span>{{ trans('front/bots.bot_error_msg') }}</span>
                      <label id="aError_msg">{!! Form::control('text', 0, 'error_msg', $errors,'',$bot->error_msg) !!}</label>
                    </li>
                    
                    <!--<li>
                      <span>{{ trans('front/bots.bot_image') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label>{!! Form::control('file', 0, 'bot_image', $errors) !!}<span>{{ trans('front/bots.browse') }}</span></label>
                      <span>
                        <?php
                          if(isset($bot->bot_image) && !empty($bot->bot_image)){
                          ?>
                            {!! HTML::image('uploads/'.$bot->bot_image) !!}
                          <?php
                          }
                        ?>
                      </span>
                    </li>
                    
                    <li>
                      <span>{{ trans('front/bots.description') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label>{!! Form::control('textarea', 0, 'bot_description', $errors,'',$bot->bot_description) !!}</label>
                    </li>
                    -->
                    
                    <li>
                      <span>{{ trans('front/bots.start_message') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label>{!! Form::control('textarea', 0, 'start_message', $errors,'',$bot->start_message) !!}</label>
                    </li>
                  
                  </ul>
                
                </div>
                
                <div class="crete_bot_form">
                  <ul>
                    <li class="example_information">
                      <span>{{ trans('front/bots.name_of_autoresponses_button') }}</span>
                      <label>{!! Form::control('text', 0, 'autoresponse', $errors, '',$bot->autoresponse) !!}</label>
                    </li>

                    <li class="example_contact">
                      <span>{{ trans('front/bots.name_of_contact_forms_button') }}</span>
                      <label>{!! Form::control('text', 0, 'contact_form', $errors,'',$bot->contact_form) !!}</label>
                    </li>
                    
                    <li class="example_our_photos">
                      <span>{{ trans('front/bots.name_of_galleries_button') }}</span>
                      <label>{!! Form::control('text', 0, 'galleries', $errors,'',$bot->galleries) !!}</label>
                    </li>
                    
                    <li class="example_our_channels">
                      <span>{{ trans('front/bots.name_of_channels_button') }} </span>
                      <label>{!! Form::control('text', 0, 'channels', $errors,'',$bot->channels) !!}</label>
                    </li>
                  </ul>
                </div>
                
               <div class="submit">
                  {!! Form::submit_new(trans('front/bots.update')) !!}
                </div>
                
            </div>
        </div>
      </div>
      
      
      
      {!! Form::close() !!}
      
  </div>
  
  
    <!-- Modal -->
<div id="nickNameModal" class="modal fade" role="dialog" style="display:none";>
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{!! trans('front/bots.bot_nick_name') !!}</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $nickName[0]->content;?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
	$(document).ready(function(e) {
		$('.chat_box').css('display','block');
		$('#auto_resp').html('<?php echo $bot->autoresponse; ?>');
		$('#conntact_fbutton').html('<?php echo $bot->contact_form; ?>');
		$('#gallery_imgs').html('<?php echo $bot->galleries; ?>');
		$('#chnl_btn').html('<?php echo $bot->channels; ?>');
		
        $('#autoresponse').keyup(function(e) {
            $('#auto_resp').html($('#autoresponse').val());
        });
		
		$('#contact_form').keyup(function(e) {
            $('#conntact_fbutton').html($('#contact_form').val());
        });
		
		$('#galleries').keyup(function(e) {
            $('#gallery_imgs').html($('#galleries').val());
        });
		
		$('#channels').keyup(function(e) {
            $('#chnl_btn').html($('#channels').val());
        });
		
    });
	
  function mypopupinfo(id){
    $('#'+id).modal();
  }
</script>
 
@stop