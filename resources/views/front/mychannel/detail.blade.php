@extends('front.template')
@section('main')

<!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->


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
        <a class="bot_button" href="{!! URL::to('/my_channel/create') !!}">{!! HTML::image('img/front/plus.png') !!}</a>
        <p>{{ trans('front/fornt_user.add_new_bot_chanel') }}</p>
    </div>
</div>

<div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

    @include('front.top')

    <div class="my_account">
        <h4>{!! $chanels[0]->name !!}</h4>
    </div>


    <div class="buying">
        <div class="create_bot">
            <div class="crete_bot_form">
                <ul>
                    <li>
                        <span>{{ trans('front/MyChannel.name') }}</span>
                        <label id="chanel_name">{!! $chanels[0]->name !!}</label>
                    </li>



                    <li>
                        <span>{{ trans('front/MyChannel.description') }}</span>
                        <label id="channel_description">{!! $chanels[0]->description !!}</label>
                    </li>

                    <li>
                        <span>{{ trans('front/MyChannel.share_link') }}</span>
                        <label id="channel_share_link">{!! $chanels[0]->share_link !!}</label>
                    </li>


                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12">
         <div class="col-plan">
          <h2>{{ trans('front/MyChannel.messages_activity') }}</h2>
          <table>
            <thead>
              <tr>
                <th>{{ trans('front/MyChannel.channel_name') }}</th>
                <th>{{ trans('front/MyChannel.send_message') }} </th>
                <th>{{ trans('front/MyChannel.send_date') }} </th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($chanelMesg)){
                  foreach($chanelMesg as $d2 => $v2){
                    ?>
                        <tr>
                          <td><?php echo $v2->channel_name;?></td>
                          <td><?php echo $v2->message;?></td>
                          <td><?php echo $v2->send_date;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/MyChannel.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
    </div>


</div>

<style>
    .thumb {
        width: 20%;
    }

.col-summary {
    max-height: 159px;
    overflow: auto;
}
</style>
@stop