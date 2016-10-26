@extends('front.template')
@section('main')


  <div class="col-sm-3 col-sm-offset-1  col-lg-2 col-lg-offset-1 ">
        <h1 class="logo">
            <a href="{!! URL::to('/dashboard') !!}">
                {!! HTML::image('img/front/logo.png') !!}
            </a>
        </h1>
        
        <h3>{{ trans('front/fornt_user.summary') }}</h3>
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
            <p>{{ trans('front/fornt_user.add_new_bot_chanel') }}</p>
        </div>
    </div>

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      <div class="my_account">
        <h4>Messages</h4>
      </div>
      
    
      
      <div class="col-lg-12 col-profiletab">
        <ul>
        <?php
          if(isset($rec_msg) && !empty($rec_msg)){
            foreach($rec_msg as $k1 => $v1){
            ?>
            <li>
                <div class="side_content">
                    <h4><?php echo $v1->text;?></h4>
                    <p>
                        <?php 
                        if(file_exists(public_path().'/uploads/'.$v1->reply_message) && !empty($v1->reply_message)){
                            ?>
                            {!! HTML::image('uploads/'.$v1->reply_message) !!}
                            <?php
                        }
                        else{
                            echo $v1->reply_message;
                        }
                        ?>
                    </p>
                    <div class="side_time"><?php echo get_timeago(strtotime($v1->forward_date))?></div>
                </div>
              </li>
            <?php
            }
          }
        ?>
        </ul>
        
        <?php
                function get_timeago( $ptime )
                {
                    $estimate_time = time() - $ptime;
               
                    if( $estimate_time < 1 )
                    {
                        return 'less than 1 second ago';
                    }

                    $condition = array(
                                12 * 30 * 24 * 60 * 60  =>  'year',
                                30 * 24 * 60 * 60       =>  'month',
                                24 * 60 * 60            =>  'day',
                                60 * 60                 =>  'hour',
                                60                      =>  'minute',
                                1                       =>  'second'
                    );

                    foreach( $condition as $secs => $str )
                    {
                        $d = $estimate_time / $secs;

                        if( $d >= 1 )
                        {
                            $r = round( $d );
                            return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
                        }
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