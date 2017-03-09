@extends('front.template')
@section('main')


    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

        @include('front.top')

        {!! Form::open(['url' => 'bot/update_bot/'.$bot->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'payment-form']) !!}

        {!! Form::hidden('id', $bot->id, array('id' => 'bot')) !!}

        <div id="row2">
            <div class="my_account telegram">
                <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/bots.telegram') }}</span></h4>
                <h5>{{ trans('front/bots.edit') }}</h5>
            </div>

            <div class="buying">
                <div class="create_bot">
                    <div class="crete_bot_form">
                        <ul>

                            <li>
                                <span>{{ trans('front/bots.start_message') }}</span>
                                <label class="lead emoji-picker-container text-area">
                                    {!! Form::control('textarea', 0, 'start_message', $errors,'',$bot->start_message,"data-emojiable='true' placeholder='".trans('front/bots.welcome_default_message')."' maxlength='250' required") !!}
                                </label>
                            </li>

                            <li>
                                <span>{{ trans('front/bots.list_all_commands') }}</span>
                                <label  class="lead">
                                    {!! Form::control('text', 0, 'comanda', $errors,'',$bot->comanda, " placeholder='".trans('front/bots.list_command')."' maxlength='50' required") !!}
                                </label>

                                <p><small>* {{ trans('front/bots.list_command_help') }}</small></p>
                            </li>

                            <li>
                                <span>{{ trans('front/bots.bot_error_msg') }}</span>
                                <label id="aError_msg"  class="lead emoji-picker-container">
                                    {!! Form::control('text', 0, 'error_msg', $errors,'',$bot->error_msg,"data-emojiable='true' placeholder='".trans('front/bots.error_default_message')."' maxlength='50' required") !!}
                                </label>
                            </li>





                        </ul>

                    </div>

                    <div class="crete_bot_form" id="main_buttons">
                        <ul>
                            <li class="example_information col-sm-6">
                                <span>{{ trans('front/bots.name_of_autoresponses_button') }}</span>
                                <label  class="lead emoji-picker-container" id="boto_autorespostes">
                                    {!! Form::control('text', 0, 'autoresponse', $errors,'',$bot->autoresponse,"data-emojiable='true' placeholder='Informació' maxlength='20' required") !!}
                                </label>
                            </li>

                            <li class="example_information col-sm-6">
                                <span>{{ trans('front/bots.intortext_of_autoresponses_button') }}</span>
                                <label  class="lead emoji-picker-container">
                                    {!! Form::control('text', 0, 'intro_autoresponses', $errors,'',$bot->intro_autoresponses,"data-emojiable='true' placeholder='Selecciona la informació que desitgis' maxlength='120' required") !!}
                                </label>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="crete_bot_form">
                        <ul>
                            <li class="example_contact col-sm-6">
                                <span>{{ trans('front/bots.name_of_contact_forms_button') }}</span>
                                <label  class="lead emoji-picker-container" id="boto_formularis">
                                    {!! Form::control('text', 0, 'contact_form', $errors,'',$bot->contact_form,"data-emojiable='true' placeholder='Contactar' maxlength='20' required") !!}
                                </label>
                            </li>

                            <li class="example_contact col-sm-6">
                                <span>{{ trans('front/bots.intortext_of_contact_forms_button') }}</span>
                                <label  class="lead emoji-picker-container">
                                    {!! Form::control('text', 0, 'intro_contact_form', $errors,'',$bot->intro_contact_form,"data-emojiable='true' placeholder='Escull amb qui desitges contactar' maxlength='120' required") !!}
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="crete_bot_form">
                        <ul>
                            <li class="example_our_photos col-sm-6">
                                <span>{{ trans('front/bots.name_of_galleries_button') }}</span>
                                <label  class="lead emoji-picker-container" id="boto_galeries">
                                    {!! Form::control('text', 0, 'galleries', $errors,'',$bot->galleries,"data-emojiable='true' placeholder='Fotografies' maxlength='20' required") !!}
                                </label>
                            </li>

                            <li class="example_our_photos col-sm-6">
                                <span>{{ trans('front/bots.introtext_of_galleries_button') }}</span>
                                <label  class="lead emoji-picker-container">
                                    {!! Form::control('text', 0, 'intro_galleries', $errors,'',$bot->intro_galleries,"data-emojiable='true' placeholder='Escull una galeria de fotografies' maxlength='120' required") !!}
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="crete_bot_form">
                        <ul>
                            <li class="example_our_channels col-sm-6">
                                <span>{{ trans('front/bots.name_of_channels_button') }} </span>
                                <label  class="lead emoji-picker-container"id="boto_canals">
                                    {!! Form::control('text', 0, 'channels', $errors,'',$bot->channels,"data-emojiable='true' placeholder='Canals' maxlength='15' required") !!}
                                </label>
                            </li>

                            <li class="example_our_channels col-sm-6">
                                <span>{{ trans('front/bots.introtext_of_channels_button') }} </span>
                                <label  class="lead emoji-picker-container">
                                    {!! Form::control('text', 0, 'intro_channels', $errors,'',$bot->intro_channels,"data-emojiable='true' placeholder='Escull un dels nostres canals' maxlength='120' required") !!}
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="submit buy_now">
                        <a href="{!! URL::to('/bot/detail/'.$bot->id) !!}">{{ trans('front/bots.back') }}</a>
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
                    <p><?php if(isset($nickName[0]->content) && !empty($nickName[0]->content)){echo $nickName[0]->content;}?></p>
                </div>
            </div>

        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            // Initializes and creates emoji set from sprite sheet
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: '/lib/img',
                popupButtonClasses: 'fa fa-smile-o'
            });
            // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
            // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
            // It can be called as many times as necessary; previously converted input fields will not be converted again
            window.emojiPicker.discover();
        });
        $(document).ready(function(e) {


            $('.chat_box').css('display','block');
            $('#auto_resp').html("<?php echo $bot->autoresponse; ?>");
            $('#conntact_fbutton').html("<?php echo $bot->contact_form; ?>");
            $('#gallery_imgs').html("<?php echo $bot->galleries; ?>");
            $('#chnl_btn').html("<?php echo $bot->channels; ?>");


            $('#boto_autorespostes').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#auto_resp').html($('#boto_autorespostes').find('div.emoji-wysiwyg-editor').html());
            });

            $('#boto_formularis').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#conntact_fbutton').html($('#boto_formularis').find('div.emoji-wysiwyg-editor').html());
            });

            $('#boto_galeries').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#gallery_imgs').html($('#boto_galeries').find('div.emoji-wysiwyg-editor').html());
            });

            $('#boto_canals').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#chnl_btn').html($('#boto_canals').find('div.emoji-wysiwyg-editor').html());
            });


        });

        function mypopupinfo(id){
            $('#'+id).modal();
        }
    </script>

@stop