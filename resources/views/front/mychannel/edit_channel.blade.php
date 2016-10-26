@extends('front.template')
@section('main')


<div class="col-sm-3 col-sm-offset-1  col-lg-2 col-lg-offset-1 ">
    <h1 class="logo">
        <a href="{!! URL::to('/dashboard') !!}">
            {!! HTML::image('img/front/logo.png') !!}
        </a>
    </h1>

    <h3>{{ trans('front/MyChannel.summary') }}</h3>
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
        <a class="bot_button" href="{!! URL::to('/my_channel/create') !!}">{!! HTML::image('img/front/plus.png') !!}</a>
        <p>{{ trans('front/MyChannel.add_new_bot') }}</p>
    </div>

   <div class="col-summary">
    <div class="summary_content">
        <h4>{{ trans('front/dashboard.bots') }}</h4>
        <?php
            if(isset($total_bots) && !empty($total_bots)){
                foreach($total_bots as $tbk1 => $tbv1){
                    ?>
        <p><a href="{!! URL::to('/bot/detail/'.$tbv1->id) !!}"><?php echo $tbv1->username;?></a></p>
                    <?php
                }
            }
        ?>
    </div>

    <div class="summary_content"><h4>{{ trans('front/dashboard.channels') }}</h4>
        <?php
            if(isset($total_chanels) && !empty($total_chanels)){
                foreach($total_chanels as $tck1 => $tcv1){
                    ?>
        <p><a href="{!! URL::to('/my_channel/detail/'.$tcv1->id) !!}"><?php echo $tcv1->name;?></a></p>
                    <?php
                }
            }
        ?>
    </div>
</div>
</div>

<div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

    @include('front.top')  

    {!! Form::open(['url' => 'my_channel/update_channel/'.$channel->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'payment-form']) !!}
    
    {!! Form::hidden('id', $channel->id, array('id' => 'bot')) !!}

    <div>
        <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/MyChannel.telegram') }}</span></h4>
            <h5>{{ trans('front/MyChannel.create') }}</h5>
        </div>

        <div class="buying">
            <div class="create_bot">
                <div class="crete_bot_form">
                    <ul>
                        <li>
                            <span>{{ trans('front/MyChannel.name') }} {!! HTML::image('img/front/icon.png') !!}</span>
                            <label id="uName">{!! Form::control('text', 0, 'name', $errors,'',$channel->name) !!}</label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.description') }} {!! HTML::image('img/front/icon.png') !!}</span>
                            <label>{!! Form::control('textarea', 0, 'description', $errors,'',$channel->description) !!}</label>
                        </li>
                    </ul>
                </div>
                
                <div class="submit">
                  {!! Form::submit_new(trans('front/form.send')) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

</div>


<style>
.col-summary {
    max-height: 159px;
    overflow: auto;
}
</style>

@stop