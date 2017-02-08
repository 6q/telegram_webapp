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

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      <div class="my_account telegram">
        <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{!! trans('front/command.telegram') !!}</span></h4>
        <h5>{!! trans('front/command.create_bot_command') !!}</h5>
      </div>

	<div class="bot_command">        
        <div class="bot_command_content">
        	@if ($errors->has())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>        
                    @endforeach
                </div>
                @endif
          <h2>{!! trans('front/command.select_type') !!}</h2>
        </div>
        
        <div id="ul_form" class="bot_command_form">
         
		 {!! Form::open(['url' => 'command/contactform_edit/'.$contact_forms[0]->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateContactForm();']) !!}
            
            {!! Form::hidden('bot_id', $contact_forms[0]->type_id, array('id' => '')) !!}
			{!! Form::hidden('id', $contact_forms[0]->id, array('id' => 'contact_form')) !!}
			
            	<ul class="show_hide_ul">
                <li> 
                	<span>{!! trans('front/command.email') !!}</span>
                	<label id="email_err"  class="lead">
                  		{!! Form::control('text', 0, 'email', $errors,'',$contact_forms[0]->email) !!}
                	</label>
              	</li>
                
              <li> 
                <span>{!! trans('front/command.submenu_heading_text') !!}</span>
                <label id="contact"  class="lead emoji-picker-container">
                  {!! Form::control('text', 0, 'submenu_heading_text', $errors,'',$contact_forms[0]->submenu_heading_text,"data-emojiable='true' required") !!}
                </label>
              </li>
              
              <li> 
                <span>{!! trans('front/command.introduction_headline') !!}</span>
                <label id="head_line"  class="lead emoji-picker-container text-area">
					{!! Form::control('text', 0, 'headline', $errors,'',$contact_forms[0]->headline,"data-emojiable='true' required") !!}
                </label>
              </li>
              
              <?php
				if(isset($contact_forms_ques) && !empty($contact_forms_ques)){
					$i = 0;http://192.168.1.4/intranet/home.php#tabsi-2
					foreach($contact_forms_ques as $k1 => $v1){
					?>                
						<li> 
							<span>{!! trans('front/command.question_heading') !!}</span>
							<label id="ques">
								<div class="">
									<input type="text" class="ques_heading form-control" name="ques_heading[<?php echo $i;?>]" placeholder="" id="ques_heading" class="" value="<?php echo $v1->ques_heading; ?>">
								</div>
							</label>
						  </li>

						  <!--<li class="type_response">
							<span> {!! trans('front/command.type_of_response_expected') !!} </span>  
							<div class="box">
							  <select id="selectBox_<?php echo $i;?>" name="type_response[<?php echo $i;?>]">
								<?php
									$sel1 = '';
									if($v1->response_type == 'text'){
										$sel1 = "selected='selected'";
									}
						
									$sel2 = '';
									if($v1->response_type == 'image'){
										$sel2 = "selected='selected'";
									}
								?>
								<option value="text" <?php echo $sel1; ?> >Text</option>
								<option value="image" <?php echo $sel2; ?> >Image</option>
							  </select>
							</div>	
						  </li>	-->
					<script>
						$(document).ready(function(){
							$('#selectBox_<?php echo $i;?>').niceSelect();
						});
					</script>
					<?php
						$i++;
					}
				}	
				else{
					$i = 0;
				?>
					<li> 
						<span>{!! trans('front/command.question_heading') !!}</span>
						<label id="ques">
							<div class="">
								<input type="text" class="ques_heading form-control" name="ques_heading[0]" placeholder="" id="ques_heading" class="">
							</div>
						</label>
					  </li>

					  <!--<li class="type_response">
						<span> {!! trans('front/command.type_of_response_expected') !!} </span>  
						<div class="box">
						  <select id="selectBox" name="type_response[0]">
							<option value="text">Text</option>
							<option value="image">Image</option>
						  </select>
						</div>	
					  </li>	-->	
				<?php
				}
			  ?>
				
				<li id="res"></li>	
			
              
              <li class="add_more"> <a id="add_more" href="javascript:void(0);" onclick="add_more()" data-rel="<?php echo $i;?>">{!! trans('front/command.add_more_ques') !!} </a> </li>
              
              <li class="input_submit buy_now">
                <a href="{!! URL::to('/bot/detail/'.$contact_forms[0]->type_id) !!}">{{ trans('front/bots.back') }}</a>
                {!! Form::submit_new(trans('front/command.submit')) !!}
              </li>
              
            </ul>
			{!! Form::close() !!}
            
          
			
        </div>
      </div>
</div>



<style>
  .bot_command_form input {
    padding: 16px;
  }
  
  textarea {
    width: 100%;
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
	
	.form-control {
  height: auto;
}
</style>

<script>
	
	function add_more(){
		var i = $('#add_more').attr('data-rel');
		i = parseInt(i)+1;
		
		var html = '<ul><li><span>{!! trans("front/command.question_heading") !!}</span><label id="ques"><div class=""><input type="text" name="ques_heading['+i+']" placeholder="" id="ques_heading" class="ques_heading form-control"></div></label></li></ul>';
		/*
		var html = '<ul><li><span>{!! trans("front/command.question_heading") !!}</span><label id="ques"><div class=""><input type="text" name="ques_heading['+i+']" placeholder="" id="ques_heading" class="ques_heading form-control"></div></label></li><li class="type_response"><span> {!! trans("front/command.type_of_response_expected") !!} </span><div class="box"><select name="type_response['+i+']" id="selectBox'+i+'"><option value="text">Text</option><option value="image">Image</option></select></div></li></ul>';
		*/
		
		$('#add_more').attr('data-rel',i);
		$('#res').append(html);
		$('#selectBox'+i).niceSelect();
	}
	
	
	function validateContactForm(){
		var chk = 1;
		var email = $('#email').val();
		var contact_submenu_heading_text = $('#submenu_heading_text').val();
		var headline = $('#headline').val();
		
		if(email == ''){
			chk = 0;
			$('#email_err div').addClass('has-error');
		}
		else if(!isValidEmailAddress(email)){
			chk = 0;
			$('#email_err div').addClass('has-error');
		}
		else{
			$('#email_err div').removeClass('has-error');
		}
		
		if(contact_submenu_heading_text == ''){
			chk = 0;
			$('#contact div').addClass('has-error');
		}
		else{
			$('#contact div').removeClass('has-error');
		}
		
		
		if(headline == ''){
			chk = 0;
			$('#head_line div').addClass('has-error');
		}
		else{
			$('#head_line div').removeClass('has-error');
		}
		
		
		$('.ques_heading').each(function( index ) {
			var qques = $(this).val();
		  	if(qques == ''){
				chk = 0;
				$(this).parent().addClass('has-error');
			}
			else{
				$(this).parent().removeClass('has-error');
			}
		});
		
		if(chk){
			return true;
		}
		else{
			return false;
		}
		
	}
	
	
	
	function isValidEmailAddress(emailAddress) {
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		
		return pattern.test(emailAddress);
	}
  
</script>

@stop