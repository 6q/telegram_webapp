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
 
                {!! Form::open(['url' => 'bot/bot_command/'.$bot_id, 'method' => 'post', 'class' => 'form-horizontal panel']) !!}

                {!! Form::control('hidden', 0, 'bot_id', $errors, '',$bot_id) !!}

                <ul class="show_hide_ul">
                    <li>
                        <span>{!! trans('front/bots.bot_command') !!}</span>
                        <label id="ch_heading" class="lead emoji-picker-container">
                            {!! Form::control('text', 0, 'title', $errors, '','',"data-emojiable='false' required") !!}
                        </label>
                    </li>

                    <li>
                        <span>{!! trans('front/bots.command_description') !!}</span>
                        <label id="ch_msg" class="lead emoji-picker-container text-area">
                            {!! Form::control('textarea', 0, 'command_description', $errors,'','',"data-emojiable='false' required") !!}
                        </label>
                    </li>

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

@stop        