@extends('front.template')
@section('main')

    {!! HTML::script('js/jquery-ui.js') !!}



    <div class="col-sm-9 col-sm-offset-3 col-lg-9 col-lg-offset-3">

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
                <h2>{!! trans('front/bots.bot_command') !!}</h2>
            </div>

            <div id="ul_form" class="bot_command_form">

                {!! Form::open(['url' => 'bot/bot_command_edit/'.$botCommands[0]->id, 'method' => 'post', 'class' => 'form-horizontal panel','enctype'=>"multipart/form-data",'onsubmit' =>'return validate();']) !!}

                {!! Form::hidden('id', $botCommands[0]->id, array('id' => 'chanel')) !!}
                {!! Form::hidden('bot_id', $botCommands[0]->bot_id, array('id' => '')) !!}

                <ul class="show_hide_ul">
                    <li>
                        <span>{!! trans('front/bots.bot_command') !!}</span>
                        <label id="bc_title" class="lead emoji-picker-container">
                            {!! Form::control('text', 0, 'title', $errors, '',$botCommands[0]->title,"data-emojiable='false' required") !!}
                        </label>
                    </li>

                    <li>
                        <span>{!! trans('front/bots.command_description') !!}</span>
                        <label id="ch_msg" class="lead emoji-picker-container text-area">
                            {!! Form::control('textarea', 0, 'command_description', $errors,'',$botCommands[0]->command_description,"data-emojiable='false' required") !!}
                        </label>

                        <label>
                            {!! trans('front/command.or') !!}
                        </label>

                    </li>

                    <li class="browse_content">
                        <label>
                            <input type="file" name="image" id="image"><span>{{ trans('front/command.browse') }}</span>

                            <input type="hidden" name="old_img" id="old_img" value="{!! $botCommands[0]->image !!}"/>

							<?php
							if(isset($botCommands[0]->image) && !empty($botCommands[0]->image)){
							?>
                            <div id="image-holder">{!! HTML::image('uploads/'.$botCommands[0]->image) !!}</div>
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
					<? if (session('statut') === 'admin' || session('statut') === 'vip') { ?>

                    <script>
						$(function () {
							$("#accordion").accordion({header: "a", collapsible: true, active: false});
						});
                    </script>
                    <div id="accordion">
                        <a style="cursor:pointer">+ {{ trans('front/command.advanced_options') }}</a>
                        <div>
                            <br>
                            <li class="webservice_type">
                                <span>  {{ trans('front/command.add_webservice') }}</span>

                                <div class="box">
                                    <select id="webservice_type" name="webservice_type">
										<?php
										$sel1 = '';
										if ($botCommands[0]->webservice_type == 0) {
											$sel1 = "selected='selected'";
										}

										$sel2 = '';
										if ($botCommands[0]->webservice_type == 1) {
											$sel2 = "selected='selected'";
										}

										$sel3 = '';
										if ($botCommands[0]->webservice_type == 2) {
											$sel3 = "selected='selected'";
										}
										?>
                                        <option value="0" <?php echo $sel1; ?> > {{ trans('front/command.no') }}</option>
                                        <option value="1" <?php echo $sel2; ?> > {{ trans('front/command.yes') }}</option>
                                        <option value="2" <?php echo $sel3; ?> > {{ trans('front/command.yes_with_variable') }}</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <span>Webservice URL</span>
                                <label id="bc_title" class="lead emoji-picker-container">
                                    {!! Form::control('url', 0, 'webservice_url', $errors, '',$botCommands[0]->webservice_url,"data-emojiable='false'") !!}
                                </label>
                                <small>* {{ trans('front/command.if_variable') }}</small>

                            </li>

							<? /*
			                    if (filter_var($botCommands[0]->webservice_url, FILTER_VALIDATE_URL) ) {
				                    echo "<h3>Resultat Webservice</h3>";
				                    $xml = simplexml_load_file($botCommands[0]->webservice_url);
				                    //echo $xml->asXML();
				                    if ($xml === false) {
					                    echo "Failed loading XML: ";
					                    foreach(libxml_get_errors() as $error) {
						                    echo "<br>". $error->message;
					                    }
				                    } else {
					                    //print_r($xml);
					                    $i = 0;
					                    foreach ($xml as $object)
					                    {
						                    //echo "<hr>";
						                    //print_r($object);
						                    if ($i == 1){
							                    foreach ($object as $resource) {

								                    //echo "<hr>";
								                    //print_r($resource);
								                    echo "<br><b>".$resource["name"]."</b>: ".$resource;
							                    }
						                    }
						                    ++$i;
					                    }
				                    }

			                    } */
							?>

                        </div>
                    </div>
					<? } ?>

                    <li class="input_submit buy_now new_submit">
                        <a type="button" class="btn btn-info" href="{!! URL::to('/bot/detail/'.$botCommands[0]->bot_id) !!}"><i class="fa fa-backward" aria-hidden="true"></i></a>
                        <a type="button" class="btn btn-danger"
                           onclick="return warnBeforeRedirect('{!! URL::to('/bot/bot_command_delete/'.$botCommands[0]->bot_id.'/'.$botCommands[0]->id) !!}')"><i
                                    class="fa fa-trash" aria-hidden="true"></i></a>
                        <button class="btn btn-success" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                    </li>

                </ul>

                {!! Form::close() !!}


            </div>
        </div>
    </div>

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
		$(document).ready(function (e) {
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
					$('.alert-new').css('display', 'block');
					$('#alertMsg').css('display', 'block');
				}
			});
		});

		function validate() {
			var title = $('#title').val();
			if (title != '') {
				var res = /^\/[a-z0-9]+$/i.test(title);
				if (res) {
					$('#bc_title .form-group').removeClass('has-error');
					return true;
				}
				else {
					$('#bc_title .form-group').addClass('has-error');
					return false;
				}
			}
			else {
				$('#bc_title .form-group').addClass('has-error');
				return false;
			}
			return false;
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