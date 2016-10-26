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
            <p>{{ trans('front/fornt_user.add_new_bot') }}</p>
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
      
      {!! Form::open(['url' => 'front_user/change_password', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'','onsubmit' => 'return passwordValidate();']) !!}
      
      {!! Form::hidden('id', $user['id'], array('id' => 'idd','value' =>$user['id'] )) !!}
      
    
      
        <div id="row2">
          <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/fornt_user.telegram') }}</span></h4>
            <h5>{{ trans('front/fornt_user.update_pass') }}</h5>
          </div>
          
          <div class="buying">
              <div class="create_bot">
                
                <div class="crete_bot_form">
                  <ul>
                    <li>
                      <span>{{ trans('front/fornt_user.new_password') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label id="new_pass">{!! Form::control('password', 0, 'new_password', $errors,'','') !!}</label>
                    </li>
                    
                    <li>
                      <span>{{ trans('front/fornt_user.confirm_password') }} {!! HTML::image('img/front/icon.png') !!}</span>
                      <label id="cpass">{!! Form::control('password', 0, 'confirm_password', $errors,'',''	) !!}</label>
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

<script>
  function passwordValidate(){
    var new_password = $('#new_password').val();
    var confirm_password = $('#confirm_password').val();
    var chk = true;
    if(new_password == ''){
      chk = false;
      $('#new_pass .form-group').addClass('has-error');
    }
    else{
      $('#new_pass .form-group').removeClass('has-error');
    }
    
    if(confirm_password == ''){
      chk = false;
      $('#cpass .form-group').addClass('has-error');
    }
    else{
      $('#cpass .form-group').removeClass('has-error');
    }
    
    if(new_password != confirm_password){
      chk = false;
      $('#cpass .form-group').addClass('has-error');
    }
    
    if(chk){
      return true;
    }
    else{
      return false;
    }
    
  }
</script>

<style>
.col-summary {
    max-height: 159px;
    overflow: auto;
}
</style>
@stop