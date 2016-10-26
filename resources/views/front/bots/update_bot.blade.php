@extends('front.template')
@section('main')



  <div class="col-sm-3 col-sm-offset-1  col-lg-2 col-lg-offset-1 ">
        <h1 class="logo">
            <a href="{!! URL::to('/dashboard') !!}">
                {!! HTML::image('img/front/logo.png') !!}
            </a>
        </h1>
        
        <h3>{{ trans('front/bots.summary') }}</h3>
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
            <p>{{ trans('front/bots.add_new_bot') }}</p>
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
      
      {!! Form::open(['url' => 'bot/update_bot/'.$bot->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'payment-form']) !!}
      
      {!! Form::hidden('id', $bot->id, array('id' => 'bot')) !!}
      
        <div id="row2">
          <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/bots.telegram') }}</span></h4>
            <h5>{{ trans('front/bots.create') }}</h5>
          </div>
          
          <div class="buying">
              <div class="create_bot">
                <div class="crete_bot_form">
                  <ul>
                    <li>
                      <span>{{ trans('front/bots.bot_nick_name') }} <a href="javascript:void(0);" onclick="mypopupinfonick_name();">{!! HTML::image('img/front/icon.png') !!}</a></span>
                      <label>{!! Form::control('text', 0, 'nick_name', $errors,'',$bot->nick_name) !!}</label>
                    </li>
                    
                    <li>
                      <span>{{ trans('front/bots.bot_image') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label>{!! Form::control('file', 0, 'bot_image', $errors) !!}<span>{{ trans('front/bots.browse') }}</span></label>
                      <span>
                        <?php
                          if(isset($bot->bot_image) && !empty($bot->bot_image)){
                          ?>
                            {!! HTML::image('uploads/'.$bot->bot_image) !!}
                          <?php
                          }
                        ?>
                      </span>
                    </li>
                    
                    <li>
                      <span>{{ trans('front/bots.description') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label>{!! Form::control('textarea', 0, 'bot_description', $errors,'',$bot->bot_description) !!}</label>
                    </li>
                    
                    <li>
                      <span>{{ trans('front/bots.start_message') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label>{!! Form::control('textarea', 0, 'start_message', $errors,'',$bot->start_message) !!}</label>
                    </li>
                  
                  </ul>
                
                </div>
                
                <div class="crete_bot_form">
                  <ul>
                    <li class="example_information">
                      <span>{{ trans('front/bots.name_of_autoresponses_button') }}</span>
                      <label>{!! Form::control('text', 0, 'autoresponse', $errors,'',$bot->autoresponse) !!}</label>
                    </li>

                    <li class="example_contact">
                      <span>{{ trans('front/bots.name_of_contact_forms_button') }}</span>
                      <label>{!! Form::control('text', 0, 'contact_form', $errors,'',$bot->contact_form) !!}</label>
                    </li>
                    
                    <li class="example_our_photos">
                      <span>{{ trans('front/bots.name_of_galleries_button') }}</span>
                      <label>{!! Form::control('text', 0, 'galleries', $errors,'',$bot->galleries) !!}</label>
                    </li>
                    
                    <li class="example_our_channels">
                      <span>{{ trans('front/bots.name_of_channels_button') }} </span>
                      <label>{!! Form::control('text', 0, 'channels', $errors,'',$bot->channels) !!}</label>
                    </li>
                  </ul>
                </div>
                
               <div class="submit">
                  {!! Form::submit_new(trans('front/bots.update')) !!}
                </div>
                
            </div>
        </div>
      </div>
      
      
      
      {!! Form::close() !!}
      
  </div>

<script>
  function mypopupinfonick_name(){
    
  }
</script>
 
@stop