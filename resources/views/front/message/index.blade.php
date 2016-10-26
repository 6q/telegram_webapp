@extends('front.template')
@section('main')


  <div class="col-sm-3 col-sm-offset-1  col-lg-2 col-lg-offset-1 ">
        <h1 class="logo">
            <a href="{!! URL::to('/dashboard') !!}">
                {!! HTML::image('img/front/logo.png') !!}
            </a>
        </h1>
        
        <h3>{{ trans('front/message.summary') }}</h3>
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
            <p>{{ trans('front/message.add_new_bot_chanel') }}</p>
        </div>
    </div>

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3 col-message">
     
      @include('front.top')  
      
      <div class="my_account">
        <h4>{{ trans('front/message.message') }}</h4>
      </div>
      
    
      
      <div class="col-lg-12">
        <?php
          if(isset($bots) && !empty($bots)){
            foreach($bots as $k1 => $v1){
             ?>
                <div class="col-plan">
                  <h2><?php echo $v1->username;?></h2>
                  <table>
                      <thead>
                        <tr>
                          <th>{{ trans('front/message.bot_user') }}</th>
                          <th>{{ trans('front/message.created') }} </th>
                          <th>{{ trans('front/message.text') }}</th>
                          <th>{{ trans('front/message.reply') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        if(!empty($v1->message)){
                          foreach($v1->message as $mk1 => $mv1){
                            ?>
                                <tr>
                                  <td><?php echo $mv1->bot_user;?></td>
                                  <td><?php echo $mv1->forward_date;?></td>
                                  <td><?php echo $mv1->text;?></td>
                                  <td>
                                    <?php 
                        if(file_exists(public_path().'/uploads/'.$mv1->reply_message) && !empty($mv1->reply_message)){
                            ?>
                            {!! HTML::image('uploads/'.$mv1->reply_message) !!}
                            <?php
                        }
                        else{
                            echo $mv1->reply_message;
                        }
                        ?>
                                  </td>
                                </tr>
                            <?php
                          }
                        }
                        else{
                          ?>
                            <tr>
                              <td colspan="4">{{ trans('front/message.no_record') }}</td>
                            </tr>
                          <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              <?php
            }
          }
        ?>
</div>
      
  </div>

<style>
  .thumb {
    width: 20%;
  }
</style>
@stop