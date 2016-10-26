@extends('back.template')

@section('head')

<style type="text/css">
  
  .badge {
    padding: 1px 8px 1px;
    background-color: #aaa !important;
  }
  
  .thumb {
    width: 100%;
  }
  
  .no_record{
    text-align:center;
    font-weight:bold;
  }

</style>

@stop

@section('main')

  @include('back.partials.entete', ['title' => trans('back/bot.dashboard') . link_to_route('user.index', trans('back/bot.back'), [], ['class' => 'btn btn-info pull-right']), 'icone' => 'user', 'fil' => trans('back/bot.users')])
 
  @if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif


	<div class="table-responsive">
		<table class="table">
				<tr>
					<th>{{ trans('back/bot.stripe_customer_id') }} :- </th>
          <td>{!! $bots[0]->stripe_customer_id !!}</td>
        </tr>	
        <tr>
          <th>{{ trans('back/bot.bot_username') }} :- </th>
          <td>{!! $bots[0]->username !!}</td>
        </tr>  
        <tr>
					<th>{{ trans('back/bot.token') }} :- </th>
          <td>{!! $bots[0]->bot_token !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.description') }} :- </th>
          <td>{!! $bots[0]->bot_description !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.image') }} :- </th>
          <td><?php if(!empty($bots[0]->bot_image)){?>{!! HTML::image('uploads/'.$bots[0]->bot_image) !!}<?php } ?></td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.start_message') }} :- </th>
          <td>{!! $bots[0]->start_message !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.autoresponse') }} :- </th>
           <td>{!! $bots[0]->autoresponse !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.contact_form') }} :- </th>
          <td>{!! $bots[0]->contact_form !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.galleries') }} :- </th>
          <td>{!! $bots[0]->galleries !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.channels') }} :- </th>
          <td>{!! $bots[0]->channels !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/bot.created_at') }} :- </th>
          <td>{!! $bots[0]->created_at !!}</td>
				</tr>
		</table>
	</div>

<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">{!! trans('back/bot.bot_autoresp') !!}</li>
		</ol>
	</div>
</div>

<div class="table-responsive">
		<table class="table">
			<?php
        if(!empty($autoResponse)){
          foreach($autoResponse as $ak1 => $av1){
          ?>
            <tr>
              <th><?php echo $av1->submenu_heading_text ?> :- </th>
              <td>
                  <?php 
                    if(isset($av1->autoresponse_msg) && !empty($av1->autoresponse_msg)){
                      echo $av1->autoresponse_msg;
                    }
                    else{
                      if(!empty($av1->image)){
                        ?>
                         {!! HTML::image('uploads/'.$av1->image) !!}
                        <?php
                      }
                    }
                  ?>
              </td>
            </tr>
          <?php
          }
        }
      ?>	
		</table>
	</div>


<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">{!! trans('back/bot.bot_contact_form') !!}</li>
		</ol>
	</div>
</div>


<div class="table-responsive">
		<table class="table">
			<?php
        if(!empty($contactForm)){
          foreach($contactForm as $ck1 => $cv1){
          ?>
            <tr>
              <th><?php echo $cv1->submenu_heading_text ?> :- </th>
              <td><?php echo $cv1->headline ?></td>
              <td width="20"><a href="{!! URL::to('bot/contactform_ques/'.$cv1->id) !!}" class="btn btn-success btn-block btn">{!! trans('back/bot.view_ques') !!}</a></td>
            </tr>
          <?php
          }
        }
      else{
      ?>
        <tr>
          <th>{!! trans('back/bot.no_record') !!}</th>
        </tr>
      <?php
      }
      ?>	
		</table>
	</div>


<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">{!! trans('back/bot.galleries') !!}</li>
		</ol>
	</div>
</div>

<div class="table-responsive">
		<table class="table">
			<?php
        if(!empty($gallery)){
          foreach($gallery as $gk1 => $gv1){
          ?>
            <tr>
              <th><?php echo $gv1->gallery_submenu_heading_text; ?> :- </th>
              <td><?php echo $gv1->introduction_headline ?></td>
              <td width="20"><a href="{!! URL::to('bot/gallery_img/'.$gv1->id) !!}" class="btn btn-success btn-block btn">{!! trans('back/bot.view_img') !!}</a></td>
            </tr>
          <?php
          }
        }
      else{
      ?>
        <tr>
          <th>{!! trans('back/bot.no_record') !!}</th>
        </tr>
      <?php
      }
      ?>	
		</table>
	</div>


<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">{!! trans('back/bot.channels') !!}</li>
		</ol>
	</div>
</div>

<div class="table-responsive">
		<table class="table">
			<?php
        if(!empty($chanels)){
          foreach($chanels as $chk1 => $chv1){
          ?>
            <tr>
              <th><?php echo $chv1->chanel_submenu_heading_text ?> :- </th>
              <td>
                <?php 
                if(isset($chv1->chanel_msg) && !empty($chv1->chanel_msg)){
                  echo $chv1->chanel_msg;
                }
                else{
                  if(!empty($chv1->image)){
                    ?>
                     {!! HTML::image('uploads/'.$chv1->image) !!}
                    <?php
                  }
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
          <th>{!! trans('back/bot.no_record') !!}</th>
        </tr>
      <?php
      }
      ?>	
		</table>
	</div>



<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">{!! trans('back/bot.messages') !!}</li>
		</ol>
	</div>
</div>

<div class="table-responsive">
		<table class="table">
			<?php
        if(!empty($messages)){
          foreach($messages as $mk1 => $mv1){
          ?>
            <tr>
              <th><?php echo $mv1->text ?> :- </th>
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
          <th>{!! trans('back/bot.no_record') !!}</th>
        </tr>
      <?php
      }
      ?>	
		</table>
	</div>



<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">{!! trans('back/bot.bot_users') !!}</li>
		</ol>
	</div>
</div>

<div class="table-responsive">
		<table class="table">
       <tr>
         <th>{!! trans('back/bot.first_name') !!}</th>
         <th>{!! trans('back/bot.last_name') !!}</th>
         <th>{!! trans('back/bot.created_at') !!}</th>
       </tr>
			<?php
        if(!empty($bot_uesers)){
          foreach($bot_uesers as $bk1 => $bv1){
          ?>
            <tr>
              <th><?php echo $bv1->first_name; ?></th>
              <td><?php echo $bv1->last_name; ?></td>
              <td><?php echo $bv1->created_at; ?></td>
            </tr>
          <?php
          }
        }
      else{
      ?>
        <tr>
          <th colspan="3">{!! trans('back/bot.no_record') !!}</th>
        </tr>
      <?php
      }
      ?>	
		</table>
	</div>




@stop

@section('scripts')

@stop