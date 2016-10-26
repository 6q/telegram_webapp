@extends('front.template')
@section('main')

<!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->

{!! HTML::script('js/front/jquery.nice-select.js') !!}
{!! HTML::style('css/front/nice-select.css') !!}


<!--<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.10/uploadfile.css" rel="stylesheet">-->
{!! HTML::script('js/jquery.uploadfile.min.js') !!}
{!! HTML::script('js/jquery-ui.js') !!}


<script>
	$(document).ready(function(){
		$('#selectBox').niceSelect();
	});
</script>

  <div class="col-sm-3 col-sm-offset-1  col-lg-2 col-lg-offset-1 ">
        <h1 class="logo">
            <a href="{!! URL::to('/dashboard') !!}">
                {!! HTML::image('img/front/logo.png') !!}
            </a>
        </h1>
        
        <h3>{{ trans('front/command.summary') }}</h3>
        <ul>
            <li>
				<p>
					<a href="{!! URL::to('/front_user') !!}"><span>{!! count($total_bots) !!}</span>{{ trans('front/dashboard.bots') }}</a>
				</p>
			</li>

			<li>
				<p><a href="{!! URL::to('/front_user') !!}"><span>{!! count($total_chanels) !!}</span>{{ trans('front/dashboard.channels') }}</a></p>
			</li>
        </ul>
        
        <div class="new_bot_channel">
            <a class="bot_button" href="{!! URL::to('/bot/create') !!}">{!! HTML::image('img/front/plus.png') !!}</a>
            <p>{{ trans('front/command.add_new_bot_chanel') }}</p>
        </div>
    </div>

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      <div class="my_account telegram">
        <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{!! trans('front/command.telegram') !!}</span></h4>
        <h5>{!! trans('front/command.update_bot_command') !!}</h5>
      </div>

	<div class="bot_command">        
        <div class="bot_command_content">
          <h2>{!! trans('front/command.autoresponse') !!}</h2>
        </div>
        
        <div id="ul_form" class="bot_command_form">
          {!! Form::open(['url' => 'command/autoresponse_edit/'.$autoresponses[0]->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateAutoresponse();']) !!}
            
			{!! Form::hidden('id', $autoresponses[0]->id, array('id' => 'autoresponse')) !!}
			{!! Form::hidden('bot_id', $autoresponses[0]->type_id, array('id' => '')) !!}
            
            <ul class="show_hide_ul">
              <li> 
                <span>{!! trans('front/command.submenu_heading_text') !!}</span>
                <label id="auto">
                  {!! Form::control_new('text', 0, 'autoresponse_submenu_heading_text', $errors,'',$autoresponses[0]->submenu_heading_text) !!}
                </label>
              </li>
              
              <li> 
                <span>{!! trans('front/command.autoresponse_msg') !!}</span>
                <label id="msg">
                  {!! Form::control_new('textarea', 0, 'autoresponse_msg', $errors,'',$autoresponses[0]->autoresponse_msg) !!}
                </label>
                
                <label>
                  {!! trans('front/command.or') !!}
                </label>
              </li>
              
              <li class="browse_content"> 
                <label>
					<input type="file" name="image" id="image"><span>{{ trans('front/command.browse') }}</span>
					<input type="hidden" name="old_img" id="old_img" value="{!! $autoresponses[0]->image !!}" />
					
					<?php
						if(isset($autoresponses[0]->image) && !empty($autoresponses[0]->image)){
							?>
								<p>{!! HTML::image('uploads/'.$autoresponses[0]->image) !!}</p>
							<?php
						}
					?>
				</label>
              </li>
              
              <li class="input_submit"><input type="submit" value="{!! trans('front/command.submit') !!}"></li>
              
            </ul>
          
		 {!! Form::close() !!}
        </div>
      </div>
</div>





<script>
	function add_more(){
		var i = $('#add_more').attr('data-rel');
		i = parseInt(i)+1;
		
		var html = '<ul><li><span>{!! trans("front/command.question_heading") !!}</span><label id="ques"><div class=""><input type="text" name="ques_heading['+i+']" placeholder="" id="ques_heading" class="ques_heading form-control"></div></label></li><li class="type_response"><span> {!! trans("front/command.type_of_response_expected") !!} </span><div class="box"><select name="type_response['+i+']" id="selectBox'+i+'"><option value="text">Text</option><option value="image">Image</option></select></div></li></ul>';
		
		$('#add_more').attr('data-rel',i);
		$('#res').append(html);
		$('#selectBox'+i).niceSelect();
	}
	
	
	
	function validateAutoresponse(){
		var chk = 1;
		var autoresponse_submenu_heading_text = $('#autoresponse_submenu_heading_text').val();
		var autoresponse_msg = $('#autoresponse_msg').val();
		var chk_img = $('#image').val();
		var old_img = $('#old_img').val();
	
		if(autoresponse_submenu_heading_text == ''){
			chk = 0;
			$('#auto div').addClass('has-error');
		}
		else{
			$('#auto div').removeClass('has-error');
		}
		
		
		if(old_img == '' && autoresponse_msg == '' && chk_img == ''){
			chk = 0;
			$('#msg div').addClass('has-error');
		}
		else{
			$('#msg div').removeClass('has-error');
		}
		
		
		if(chk){
			return true;
		}
		else{
			return false;
		}
	}
  
</script>


<style>
	 .bot_command_form input {
		padding: 16px;
	  }
	
	.form-control {
	  height: auto;
	}
	
	.browse_content input[type="file"] {
	  left: 0;
	  opacity: 0;
	  padding: 10px 0;
	  position: absolute;
	  top: 0;
	  width: 102px;
	  z-index: 9;
		cursor:pointer;
	}
	  .browse_content {
	  position: relative;
	}
	  .browse_content span {
	  background: rgba(0, 0, 0, 0) linear-gradient(0deg, #e3e3e3, #ededed, #f7f7f7) repeat scroll 0 0;
	  border: 1px solid #c7c7c7;
	  border-radius: 5px;
	  color: #a0a0a0;
	  display: inline-block;
	  font-weight: normal;
	  padding: 12px 19px;
	  text-transform: capitalize;
	  width: auto;
	}
</style>

@stop