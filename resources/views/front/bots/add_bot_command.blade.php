@extends('front.template')
@section('main')

    {!! HTML::script('js/jquery-ui.js') !!}



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
                <h2>{!! trans('front/bots.bot_command') !!}</h2>
            </div>

            <div id="ul_form" class="bot_command_form">
 
                {!! Form::open(['url' => 'bot/bot_command/'.$bot_id, 'method' => 'post', 'class' => 'form-horizontal panel','enctype'=>"multipart/form-data",'onsubmit' =>'return validate();']) !!}

                {!! Form::control('hidden', 0, 'bot_id', $errors, '',$bot_id) !!}

                <ul class="show_hide_ul">
                    <li>
                        <span>{!! trans('front/bots.bot_command') !!}</span>
                        <label id="bc_title" class="lead emoji-picker-container">
                            {!! Form::control('text', 0, 'title', $errors, '','',"data-emojiable='false' required placeholder='/command'") !!}
                        </label>
                    </li>

                    <li>
                        <span>{!! trans('front/bots.command_description') !!}</span>
                        <label id="ch_msg" class="lead emoji-picker-container text-area">
                            {!! Form::control('textarea', 0, 'command_description', $errors,'','',"data-emojiable='false' required") !!}
                        </label>
                        
                        <label>
                          {!! trans('front/command.or') !!}
                        </label>
                
                    </li>
                    
                    <li class="browse_content"> 
                        <label><input type="file" name="image" id="image"><span>{{ trans('front/command.browse') }}</span></label>
                        <div id="image-holder"> </div>    
                    </li>

                    <script>
		                $( function() {
			                $("#accordion").accordion({ header: "a", collapsible: true, active: false });
		                } );
                    </script>
                    <div id="accordion">
                        <a style="cursor:pointer">+ Opcions avançades</a>
                        <div>
                            <br>
                            <li cl
                    <li class="webservice_type">
                        <span> Afegir webservice? </span>
                        <div class="box">
                            <select id="webservice_type" name="webservice_type">

                                <option value="0">No</option>
                                <option value="1">Sí</option>
                                <option value="2">Sí, amb variable</option>
                            </select>
                        </div>
                    <li>
                        <span>Webservice URL</span>
                        <label id="bc_title" class="lead emoji-picker-container">
                            {!! Form::control('url', 0, 'webservice_url', $errors, '','',"data-emojiable='false'") !!}
                        </label>
                        <small>* Si és amb una variable que ha d'introduïr l'usuari, acabar la url amb la variable i el signe "=" obert.</small>

                    </li>
            </div>
                    <li class="input_submit"><input type="submit" value="{!! trans('front/command.submit') !!}"></li>

                </ul>

                {!! Form::close() !!}


            </div>
        </div>
    </div>

    <style>
        #image-holder{
            display: inline-block;
            width: 20%;
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
    
    <script>
		$(document).ready(function(e) {
            $("#image").on('change', function () {
				if (typeof (FileReader) != "undefined") {
					var image_holder = $("#image-holder");
					image_holder.empty();
					var reader = new FileReader();
					reader.onload = function (e) {
						$("<img />", {
							"src": e.target.result,
							"class": "thumb-image"
						}).appendTo(image_holder);
		 
					}
					image_holder.show();
					reader.readAsDataURL($(this)[0].files[0]);
				} else {
					$('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>This browser does not support FileReader.');
					$('.alert-new').css('display','block');
					$('#alertMsg').css('display','block');
				}
			});
        });
		
		
    	function validate(){
			var title = $('#title').val();
			if(title != ''){
				var res = /^\/[a-z0-9]+$/i.test(title);	
				if(res){
					$('#bc_title .form-group').removeClass('has-error');
					return true;
				}
				else{
					$('#bc_title .form-group').addClass('has-error');
					return false;
				}
			}
			else{
				$('#bc_title .form-group').addClass('has-error');
				return false;
			}
			return false;
		}
    </script>

@stop        