@extends('front.template')
@section('main')

    <!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->

    {!! HTML::script('js/front/jquery.nice-select.js') !!}
    {!! HTML::style('css/front/nice-select.css') !!}


    <!--<link href="http://hayageek.github.io/jQuery-Upload-File/4.0.10/uploadfile.css" rel="stylesheet">-->
    {!! HTML::script('js/jquery.uploadfile.min.js') !!}
    {!! HTML::script('js/jquery-ui.js') !!}


    <script>
		$(document).ready(function () {
			$('#selectBox').niceSelect();
		});
    </script>


    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

        @include('front.top')

        <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{!! trans('front/command.telegram') !!}</span>
            </h4>
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
                <h2>{!! trans('front/command.channels') !!}</h2>
            </div>

            <div id="ul_form" class="bot_command_form">

                {!! Form::open(['url' => 'command/chanel_edit/'.$chanel[0]->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'command_form','onsubmit' =>'return validateChanel();']) !!}

                {!! Form::hidden('id', $chanel[0]->id, array('id' => 'chanel')) !!}
                {!! Form::hidden('bot_id', $chanel[0]->type_id, array('id' => '')) !!}

                <ul class="show_hide_ul">
                    <li>
                        <span>{!! trans('front/command.submenu_heading_text') !!}</span>
                        <label id="ch_heading" class="lead emoji-picker-container">
                            {!! Form::control('text', 0, 'chanel_submenu_heading_text', $errors,'',$chanel[0]->chanel_submenu_heading_text,"data-emojiable='true' required") !!}
                        </label>
                    </li>

                    <li>
                        <span>{!! trans('front/command.chanel_msg') !!}</span>
                        <label id="ch_msg" class="lead emoji-picker-container text-area">
                            {!! Form::control('textarea', 0, 'chanel_msg', $errors,'',$chanel[0]->chanel_msg,"data-emojiable='true' required") !!}
                        </label>

                        <label>
                            {!! trans('front/command.or') !!}
                        </label>
                    </li>

                    <li class="browse_content">
                        <label>
                            <input type="file" name="chanel_image"
                                   id="chanel_image"><span>{{ trans('front/command.browse') }}</span>
                            <input type="hidden" name="old_img" id="old_img" value="{!! $chanel[0]->image !!}"/>

							<?php
							if(isset($chanel[0]->image) && !empty($chanel[0]->image)){
							?>
                            <div id="image-holder">{!! HTML::image('uploads/'.$chanel[0]->image) !!}</div>
							<?php
							}
							else{
							?>
                            <div id="image-holder"></div>
							<?php
							}
							?>
                        </label>


                    </li>

                    <li class="input_submit buy_now">
                        <a href="{!! URL::to('/bot/detail/'.$chanel[0]->type_id) !!}">{{ trans('front/bots.back') }}</a>
                        {!! Form::submit_new(trans('front/command.submit')) !!}
                    </li>
                    <li style="text-align:right">
                        <a class="btn btn-danger"
                           onclick="return warnBeforeRedirect('{!! URL::to('/command/chanel_delete/'.$chanel[0]->type_id.'/'.$chanel[0]->id) !!}')"><i
                                    class="fa fa-trash" aria-hidden="true"></i></a>

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

    <script>
		$(document).ready(function (e) {
			$("#chanel_image").on('change', function () {
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
					$('.alert-new').css('display', 'block');
					$('#alertMsg').css('display', 'block');
				}
			});
		});
    </script>


    <style>
        #image-holder {
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
            cursor: pointer;
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

		function validateChanel() {
			var chk = 1;
			var chanel_submenu_heading_text = $('#chanel_submenu_heading_text').val();
			var chanel_msg = $('#chanel_msg').val();
			var chanel_image = $('#chanel_image').val();
			var old_img = $('#old_img').val();

			if (chanel_submenu_heading_text == '') {
				chk = 0;
				$('#ch_heading div').addClass('has-error');
			}
			else {
				$('#ch_heading div').removeClass('has-error');
			}

			if (old_img == '' && chanel_msg == '' && chanel_image == '') {
				chk = 0;
				$('#ch_msg div').addClass('has-error');
			}
			else {
				$('#ch_msg div').removeClass('has-error');
			}

			if (chk) {
				return true;
			}
			else {
				return false;
			}
		}

    </script>
    <script>
		$(document).ready(function () {

		});

		function warnBeforeRedirect(linkURL) {
			swal({
				html: true,
				title: "{{ trans('front/bots.are_you_sure') }}",
				text: "{{ trans('front/bots.you_are_going_to_delete') }}",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "{{ trans('front/bots.cancel') }}",
			}, function () {
				// Redirect the user
				window.location.href = linkURL;
			});
		}

    </script>
@stop