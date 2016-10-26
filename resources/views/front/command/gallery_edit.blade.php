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
        <h5>{!! trans('front/command.create_bot_command') !!}</h5>
      </div>

	<div class="bot_command">        
        <div class="bot_command_content">
          <h2>{!! trans('front/command.select_type') !!}</h2>
        </div>
        
        <div id="ul_form" class="bot_command_form">
			{!! Form::open(['url' => 'command/gallery_edit/'.$gallery[0]->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateGalleryForm();']) !!}
            
            {!! Form::hidden('bot_id', $gallery[0]->type_id, array('id' => '')) !!}
			{!! Form::hidden('id', $gallery[0]->id, array('id' => 'gallery_form')) !!}
			
			<input type="hidden" name="imagesSortVal" id="imagesSortVal" />
			
            	<ul class="show_hide_ul">
					<li> 
					<span>{!! trans('front/command.submenu_heading_text') !!}</span>
					<label id="galery_heading">
					  {!! Form::control_new('text', 0, 'gallery_submenu_heading_text', $errors,'',$gallery[0]->gallery_submenu_heading_text) !!}
					</label>
				  </li>
					
				  <li> 
						<span>{!! trans('front/command.introduction_headline') !!}</span>
						<label id="intro_heading">
						  {!! Form::control_new('text', 0, 'introduction_headline', $errors,'',$gallery[0]->gallery_submenu_heading_text) !!}
						</label>
				 </li>
					
				<li class="upload_image"><span>{!! trans('front/command.upload_image') !!}</span> 
					<div class="upload">
						<span id="fileuploader">{!! trans('front/command.upload') !!}</span>
					</div>
                    
                    <div class="upload_content">
						<ul id="preview">
							<?php
								$i = 0;
								if(isset($gallery_images) && !empty($gallery_images)){
									foreach($gallery_images as $k1 => $v1){
										?>
										<li id="<?php echo $i; ?>"><a href="javascript:void(0);" class="close_button" onclick="rmv('<?php echo $i; ?>')">X</a><span><img src="{{ URL::to('/') }}/uploads/<?php echo $v1->image; ?>" /></span><input type="text" name="title[<?php echo $v1->image.'_'.$i; ?>]" value="<?php echo $v1->title; ?>" /></li>
										<?php
										$i++;
									}
								}
							?>
						</ul>
                    </div>
                    
                    </li>
					
					<li class="input_submit"><input type="submit" value="{!! trans('front/command.submit') !!}"></li>
					
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
	
	#preview li {
  float: left;
  width: 30%;
}
	
	#preview img {
  height: 175px;
  max-width: 281px;
  vertical-align: top;
  width: 100%;
}
</style>

<script>
$(document).ready(function()
{
  var count = '<?php echo $i; ?>'; 
  var totalUpImg = '<?php echo $plan[0]->gallery_images-count($gallery_images); ?>';	
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


<script>
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
</script>

@stop