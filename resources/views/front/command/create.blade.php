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
          <h2>{!! trans('front/command.select_type') !!}</h2>
          <ul class="row select_type">
            <li class="col-xs-6 col-sm-3">
              <?php
               if(isset($plan[0]->autoresponses) && count($totalAutoresponses) >= $plan[0]->autoresponses){
                ?>
                  <a class="tab_section" id="tab_1" href="javascript:void(0);" onclick="myFunctionShowAlert(1);"> 
                    <span>
                      {!! HTML::image('img/front/s1.png','',array('class' => 'select')) !!}
                      {!! HTML::image('img/front/1.png','',array('class' => 'unselect')) !!}
                    </span>
                    <h4>{!! trans('front/command.autoresponse') !!}</h4>
                  </a>
                <?php
                }
                else{
                ?>
                  <a class="tab_section" id="tab_1" href="javascript:void(0);" onclick="myFunctionShow(1);"> 
                    <span>
                      {!! HTML::image('img/front/s1.png','',array('class' => 'select')) !!}
                      {!! HTML::image('img/front/1.png','',array('class' => 'unselect')) !!}
                    </span>
                    <h4>{!! trans('front/command.autoresponse') !!}</h4>
                  </a>  
                <?php
                }
              ?>
            </li>
            
            <li class="col-xs-6 col-sm-3">
			<?php
				if(isset($plan[0]->contact_forms) && count($totalContact_forms) >= $plan[0]->contact_forms){
				?>
					<a class="tab_section" id="tab_2" href="javascript:void(0);" onclick="myFunctionShowAlert(2);"> 
						<span>
						  {!! HTML::image('img/front/s2.png','',array('class' => 'select')) !!}
						  {!! HTML::image('img/front/2.png','',array('class' => 'unselect')) !!}
						</span>  
						<h4>{!! trans('front/command.contact_form') !!}</h4>
					  </a>
				<?php
				}
				else{
			?>	
              <a class="tab_section" id="tab_2" href="javascript:void(0);" onclick="myFunctionShow(2);"> 
                <span>
                  {!! HTML::image('img/front/s2.png','',array('class' => 'select')) !!}
                  {!! HTML::image('img/front/2.png','',array('class' => 'unselect')) !!}
                </span>  
                <h4>{!! trans('front/command.contact_form') !!}</h4>
              </a>
				<?php }?>
            </li>
            
            <li class="col-xs-6 col-sm-3">
				<?php
					if(isset($plan[0]->image_gallery) && count($totalGallery) >= $plan[0]->image_gallery){
					?>
						<a class="tab_section" id="tab_3" href="javascript:void(0);" onclick="myFunctionShowAlert(3);"> 
							<span>
							  {!! HTML::image('img/front/s3.png','',array('class' => 'select')) !!}
							  {!! HTML::image('img/front/3.png','',array('class' => 'unselect')) !!}
							</span>
							<h4>{!! trans('front/command.galleries') !!}</h4>
						  </a>
					<?php
					}
					else{
					?>
						<a class="tab_section" id="tab_3" href="javascript:void(0);" onclick="myFunctionShow(3);"> 
							<span>
							  {!! HTML::image('img/front/s3.png','',array('class' => 'select')) !!}
							  {!! HTML::image('img/front/3.png','',array('class' => 'unselect')) !!}
							</span>
							<h4>{!! trans('front/command.galleries') !!}</h4>
						  </a>
					<?php
					}
				?>
            </li>
            
            <li class="col-xs-6 col-sm-3">
              <a class="tab_section" id="tab_4" href="javascript:void(0);" onclick="myFunctionShow(4);"> 
                <span>
                  {!! HTML::image('img/front/s4.png','',array('class' => 'select')) !!}
                  {!! HTML::image('img/front/4.png','',array('class' => 'unselect')) !!}
                </span>
                <h4>{!! trans('front/command.channels') !!}</h4>
              </a>
            </li>
          </ul>
        </div>
        
        <div id="ul_form" class="bot_command_form">
          {!! Form::open(['url' => 'command', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateAutoresponse();']) !!}
            
            {!! Form::hidden('bot_id', $botId, array('id' => '')) !!}
			{!! Form::hidden('autoresponse', 0, array('id' => 'autoresponse')) !!}
            
            <ul class="show_hide_ul" id="row_1">
              <li> 
                <span>{!! trans('front/command.submenu_heading_text') !!}</span>
                <label id="auto">
                  {!! Form::control_new('text', 0, 'autoresponse_submenu_heading_text', $errors) !!}
                </label>
              </li>
              
              <li> 
                <span>{!! trans('front/command.autoresponse_msg') !!}</span>
                <label id="msg">
                  {!! Form::control_new('textarea', 0, 'autoresponse_msg', $errors) !!}
                </label>
                
                <label>
                  {!! trans('front/command.or') !!}
                </label>
              </li>
              
              <li class="browse_content"> 
                <label><input type="file" name="image" id="image"><span>{{ trans('front/command.browse') }}</span></label>
                
                
              </li>
              
              <li class="input_submit"><input type="submit" value="{!! trans('front/command.submit') !!}"></li>
              
            </ul>
          
		 {!! Form::close() !!}
			
			
		 {!! Form::open(['url' => 'command', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateContactForm();']) !!}
            
            {!! Form::hidden('bot_id', $botId, array('id' => '')) !!}
			{!! Form::hidden('contact_form', 0, array('id' => 'contact_form')) !!}
			
            	<ul class="show_hide_ul" id="row_2">
              <li> 
                <span>{!! trans('front/command.submenu_heading_text') !!}</span>
                <label id="contact">
                  {!! Form::control_new('text', 0, 'contact_submenu_heading_text', $errors) !!}
                </label>
              </li>
              
              <li> 
                <span>{!! trans('front/command.introduction_headline') !!}</span>
                <label id="head_line">
					{!! Form::control_new('text', 0, 'headline', $errors) !!}
                </label>
              </li>
              
              
				
				 <li> 
					<span>{!! trans('front/command.question_heading') !!}</span>
					<label id="ques">
						<div class="">
					  		<input type="text" class="ques_heading form-control" name="ques_heading[0]" placeholder="" id="ques_heading" class="">
						</div>
					</label>
				  </li>

				  <li class="type_response">
					<span> {!! trans('front/command.type_of_response_expected') !!} </span>  
					<div class="box">
					  <select id="selectBox" name="type_response[0]">
						<option value="text">Text</option>
						<option value="image">Image</option>
					  </select>
					</div>	
				  </li>
				
				<li id="res"></li>	
			
              
              <li class="add_more"> <a id="add_more" href="javascript:void(0);" onclick="add_more()" data-rel="0">{!! trans('front/command.add_more_ques') !!} </a> </li>
              <li class="input_submit"><input type="submit" value="{!! trans('front/command.submit') !!}"></li>
            </ul>
			{!! Form::close() !!}
            
			
			
			{!! Form::open(['url' => 'command', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateGalleryForm();']) !!}
            
            {!! Form::hidden('bot_id', $botId, array('id' => '')) !!}
			{!! Form::hidden('gallery_form', 0, array('id' => 'gallery_form')) !!}
			
			<input type="hidden" name="imagesSortVal" id="imagesSortVal" />
			
            	<ul class="show_hide_ul" id="row_3">
					<li> 
					<span>{!! trans('front/command.submenu_heading_text') !!}</span>
					<label id="galery_heading">
					  {!! Form::control_new('text', 0, 'gallery_submenu_heading_text', $errors) !!}
					</label>
				  </li>
					
				  <li> 
						<span>{!! trans('front/command.introduction_headline') !!}</span>
						<label id="intro_heading">
						  {!! Form::control_new('text', 0, 'introduction_headline', $errors) !!}
						</label>
				 </li>
					
				<li class="upload_image"><span>{!! trans('front/command.upload_image') !!}</span> 
					<div class="upload">
						<span id="fileuploader">{!! trans('front/command.upload') !!}</span>
					</div>
                    
                    <div class="upload_content">
						<ul id="preview">
						</ul>
                    </div>
                    
                    </li>
					
					<li class="input_submit"><input type="submit" value="{!! trans('front/command.submit') !!}"></li>
					
			  </ul>
          	{!! Form::close() !!}
          
          
			
			
			{!! Form::open(['url' => 'command', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateChanel();']) !!}
            
            {!! Form::hidden('bot_id', $botId, array('id' => '')) !!}
			{!! Form::hidden('chanel', 0, array('id' => 'chanel')) !!}
            
            <ul class="show_hide_ul" id="row_4">
              <li> 
                <span>{!! trans('front/command.submenu_heading_text') !!}</span>
                <label id="ch_heading">
                  {!! Form::control_new('text', 0, 'chanel_submenu_heading_text', $errors) !!}
                </label>
              </li>
              
              <li> 
                <span>{!! trans('front/command.chanel_msg') !!}</span>
                <label id="ch_msg">
                  {!! Form::control_new('textarea', 0, 'chanel_msg', $errors) !!}
                </label>
                
                <label>
                  {!! trans('front/command.or') !!}
                </label>
              </li>
              
              <li class="browse_content"> 
                <label><input type="file" name="chanel_image" id="chanel_image"><span>{{ trans('front/command.browse') }}</span></label>
                
                
              </li>
              
              <li class="input_submit"><input type="submit" value="{!! trans('front/command.submit') !!}"></li>
              
            </ul>
          
		 {!! Form::close() !!}
			
			
			<div id="alert_1" class="showMsg alert_show"><h2>{!! trans('front/command.up_grade') !!}</h2></div>
			<div id="alert_2" class="showMsg alert_show"><h2>{!! trans('front/command.up_grade') !!}</h2></div>
			<div id="alert_3" class="showMsg alert_show"><h2>{!! trans('front/command.up_grade') !!}</h2></div>
			<div id="alert_4" class="showMsg alert_show"><h2>{!! trans('front/command.up_grade') !!}</h2></div>
        </div>
      </div>
</div>

<script>
$(document).ready(function()
{
  var count = 1; 
  var totalUpImg = '<?php echo $plan[0]->gallery_images; ?>';	
  var token = $('input[name=_token]').val();	
	$("#fileuploader").uploadFile({
		formData: {'_token':token},
		url:'<?php echo URL::to('/command/upload')?>',
		fileName:"myfile",
		returnType: "json",
		onSelect : function(e, data) {
            if(e.length > totalUpImg){
				alert('Image upload limit is '+totalUpImg);
				return false;
			}
        },
        onSuccess: function (files, data, xhr){
			$('#preview').append('<li id="'+count+'"><a href="javascript:void(0);" class="close_button" onclick="rmv('+count+')">X</a><span><img src="{{ URL::to('/') }}/uploads/'+data+'" /></span><input type="text" name="title['+data+'_'+count+']" value="" /></li>');
			
			count = parseInt(count)+1;
		},
		showDelete: false,
		afterUploadAll: function (obj)
		{
			$(".ajax-file-upload-statusbar").remove();
		}
	});
});
	
	
function rmv(id){
	$('#preview #'+id).remove();
}

	
$(function(){
	$( "#preview" ).sortable({
		update: function(event, ui) {
		   var productOrder = $(this).sortable('toArray').toString();
			$('#imagesSortVal').val(productOrder);
		}
	});
});
	
</script>


<style>
  .show_hide_ul, .alert_show{
    display:none;
  }
  
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

<?php
  if(isset($plan[0]->autoresponses) && count($totalAutoresponses) >= $plan[0]->autoresponses){
	?>
      <script>
         $(document).ready(function(){
          myFunctionShowAlert(1);
        });
      </script>  
    <?php
  }
  else{
    ?>
      <script>
         $(document).ready(function(){
          myFunctionShow(1);
        });
      </script>  
        <?php
  }
?>
<script>
function myFunctionShowAlert(id){
    $('.tab_section').removeClass('active');
    $('#tab_'+id).addClass('active');
	
	$('.showMsg').addClass('alert_show');
	$('#alert_'+id).removeClass('alert_show');
	$('#ul_form ul').addClass('show_hide_ul');
  }
	
function myFunctionShow(id){
	$('.showMsg').addClass('alert_show');
    $('#ul_form ul').addClass('show_hide_ul');
	$('.nice-select > ul').removeClass('show_hide_ul');
	
	if(id == 1){
		$('#autoresponse').val(1);
	}
	else{
		$('#autoresponse').val(0);
	}  
	
	if(id == 2){
		$('#contact_form').val(1);
	}
	else{
		$('#contact_form').val(0);
	}
	  
	  
	if(id == 3){
		$('#gallery_form').val(1);
	}
	else{
		$('#gallery_form').val(0);
	}
	
	if(id == 4){
		$('#chanel').val(1);
	}
	else{
		$('#chanel').val(0);
	}
	  
    $('.tab_section').removeClass('active');
    $('#tab_'+id).addClass('active');
    $('#row_'+id).removeClass('show_hide_ul');
	$('.upload_content ul').removeClass('show_hide_ul');
  }
	
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
		
		if(autoresponse_submenu_heading_text == ''){
			chk = 0;
			$('#auto div').addClass('has-error');
		}
		else{
			$('#auto div').removeClass('has-error');
		}
		
		
		if(autoresponse_msg == '' && chk_img == ''){
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
	
	
	function validateContactForm(){
		var chk = 1;
		var contact_submenu_heading_text = $('#contact_submenu_heading_text').val();
		var headline = $('#headline').val();
		
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
	
	
	
	function validateGalleryForm(){
		var chk = 1;
		var gallery_submenu_heading_text = $('#gallery_submenu_heading_text').val();
		var introduction_headline = $('#introduction_headline').val();
		
		if(gallery_submenu_heading_text == ''){
			chk = 0;
			$('#galery_heading div').addClass('has-error');
		}
		else{
			$('#galery_heading div').removeClass('has-error');
		}
		
		if(introduction_headline == ''){
			chk = 0;
			$('#intro_heading div').addClass('has-error');
		}
		else{
			$('#intro_heading div').removeClass('has-error');
		}
		
		if(chk){
			return true;
		}
		else{
			return false;
		}
	}
	
	
	function validateChanel(){
		var chk = 1;
		var chanel_submenu_heading_text = $('#chanel_submenu_heading_text').val();
		var chanel_msg = $('#chanel_msg').val();
		var chanel_image = $('#chanel_image').val();

		if(chanel_submenu_heading_text == ''){
			chk = 0;
			$('#ch_heading div').addClass('has-error');
		}
		else{
			$('#ch_heading div').removeClass('has-error');
		}
		
		if(chanel_msg == '' && chanel_image == ''){
			chk = 0;
			$('#ch_msg div').addClass('has-error');
		}
		else{
			$('#ch_msg div').removeClass('has-error');
		}
		
		if(chk){
			return true;
		}
		else{
			return false;
		}
	}
  
</script>

@stop