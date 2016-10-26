@extends('front.template')
@section('main')


  <div class="col-sm-3 col-sm-offset-1  col-lg-2 col-lg-offset-1 ">
        <h1 class="logo">
            <a href="{!! URL::to('/dashboard') !!}">
                {!! HTML::image('img/front/logo.png') !!}
            </a>
        </h1>
        
        <h3>{{ trans('front/page.summary') }}</h3>
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
            <p>{{ trans('front/page.add_new_bot_chanel') }}</p>
        </div>
    </div>

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3 col-message">
     
      @include('front.top')  
      
      <div class="my_account">
        <h4>{{ trans('front/page.detail') }}</h4>
      </div>
      
    
      
      <div class="col-lg-12">
        <?php
          if(isset($pageData) && !empty($pageData)){
             ?>
                <div class="col-plan">
                  <h2><?php echo $pageData[0]->title;?></h2>
                  <div class="page_data">
                    <?php echo $pageData[0]->content;?>
                  </div>
                </div>
              <?php
            }
        ?>
</div>
      
  </div>

<style>
  .thumb {
    width: 20%;
  }
  
  .page_data {
  padding: 10px;
}
  .page_data img {
  border-radius: 0% !important;
  max-width: 80% !important;
  width: 80% !important;
}
</style>
@stop