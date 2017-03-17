@extends('front.template')
@section('main')

<!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->

{!! HTML::script('js/front/jquery.nice-select.js') !!}
{!! HTML::style('css/front/nice-select.css') !!}


<link href="{{ URL::to('/') }}/css/front/uploadfile.css" rel="stylesheet">
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
			{!! Form::open(['url' => 'command/gallery_edit/'.$gallery[0]->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateGalleryForm();']) !!}
            
            {!! Form::hidden('bot_id', $gallery[0]->type_id, array('id' => '')) !!}
			{!! Form::hidden('id', $gallery[0]->id, array('id' => 'gallery_form')) !!}
			
			<input type="hidden" name="imagesSortVal" id="imagesSortVal" />
			
            	<ul class="show_hide_ul">
					<li> 
					<span>{!! trans('front/command.submenu_heading_text') !!}</span>
					<label id="galery_heading" class="lead emoji-picker-container">
					  {!! Form::control('text', 0, 'gallery_submenu_heading_text', $errors,'',$gallery[0]->gallery_submenu_heading_text,"data-emojiable='true' required") !!}
					</label>
				  </li>
					
				  <li> 
						<span>{!! trans('front/command.gallery_introduction_headline') !!}</span>
						<label id="intro_heading" class="lead emoji-picker-container text-area">
						  {!! Form::control('text', 0, 'introduction_headline', $errors,'',$gallery[0]->gallery_submenu_heading_text,"data-emojiable='true' required") !!}
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
										<li id="<?php echo $i; ?>">
											<a href="javascript:void(0);" class="close_button" onclick="rmv('<?php echo $i; ?>')">X</a><span><img src="{{ URL::to('/') }}/uploads/<?php echo $v1->image; ?>" /></span>
											<b>{!! trans('front/command.button') !!}</b>: <input type="text" name="title[<?php echo $v1->image.'_'.$i; ?>]" value="<?php echo $v1->title; ?>" placeholder="{!! trans('front/command.button') !!}" required/><br>
											<b>{!! trans('front/command.description') !!}</b>: <br><textarea type="text" name="description[<?php echo $v1->image.'_'.$i; ?>]" placeholder="{!! trans('front/command.description') !!}" maxlength="200"><?php echo $v1->description; ?></textarea>
										</li>
										<?php
										$i++;
									}
								}
							?>
						</ul>
                    </div>
                    
                    </li>
					
					<li class="input_submit buy_now">
                    <a href="{!! URL::to('/bot/detail/'.$gallery[0]->type_id) !!}">{{ trans('front/bots.back') }}</a>
                    {!! Form::submit_new(trans('front/command.submit')) !!}
                  </li>
					
			  </ul>
          	{!! Form::close() !!}
          
        </div>
      </div>
</div>

<div id="alertMsg" style="display:none;">
    <div id="resp" class="alert-new alert-success-new alert-dismissible" role="alert">
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
				$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Image upload limit is '+totalUpImg);
				$('.alert-new').css('display','block');
				$('#alertMsg').css('display','block');
				return false;
			}
        },
        onSuccess: function (files, data, xhr){
			$('#preview').append('<li id="'+count+'"><a href="javascript:void(0);" class="close_button" onclick="rmv('+count+')">X</a><span><img src="{{ URL::to('/') }}/uploads/'+data+'" /></span><b>{!! trans("front/command.button") !!}</b>: <input type="text" name="title['+data+'_'+count+']" placeholder="{!! trans('front/command.button') !!}" required/><br><b>{!! trans('front/command.description') !!}</b>: <br><textarea type="text" name="description['+data+'_'+count+']" placeholder="{!! trans('front/command.description') !!}" maxlength="200"></textarea></li>');
			
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
		
		 $("#preview li").each(function( i ) {
			 var li_id = $(this).attr('id');
			 var inp_name_img = $('#'+li_id+' input').val();
			 if(inp_name_img == ''){
				 $('#'+li_id+' input').css('border','1px solid #ff0000');
				 chk = 0;
			 }
			 else{
			 	$('#'+li_id+' input').css('border','1px solid #a6a6a6');
			 }
		 });
		
		if(chk){
			return true;
		}
		else{
			return false;
		}
	}
</script>

@stop