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

  @include('back.partials.entete', ['title' => trans('back/mychannel.dashboard') . link_to_route('user.index', trans('back/mychannel.back'), [], ['class' => 'btn btn-info pull-right']), 'icone' => 'user', 'fil' => trans('back/mychannel.users')])
 
  @if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif


	<div class="table-responsive">
		<table class="table">
        <tr>
          <th>{{ trans('back/mychannel.stripe_customer_id') }} :- </th>
          <td>{!! $chanels[0]->stripe_customer_id !!}</td>
        </tr>
        
        <tr>
          <th>{{ trans('back/mychannel.name') }} :- </th>
          <td>{!! $chanels[0]->name !!}</td>
        </tr>  

        <tr>
          <th>{{ trans('back/mychannel.description') }} :- </th>
          <td>{!! $chanels[0]->description !!}</td>
        </tr>
        
        <tr>
		  <th>{{ trans('back/mychannel.share_link') }} :- </th>
          <td>{!! $chanels[0]->share_link !!}</td>
        </tr>
        <tr>
					<th>{{ trans('back/mychannel.created_at') }} :- </th>
           <td>{!! $chanels[0]->created_at !!}</td>
        </tr>
		</table>
	</div>








<div class="row">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			<li class="active">{!! trans('back/mychannel.messages') !!}</li>
		</ol>
	</div>
</div>

<div class="table-responsive">
		<table class="table">
			<?php
        if(!empty($chanelMesg)){
          foreach($chanelMesg as $mk1 => $mv1){
          ?>
            <tr>
              <th><?php echo $mv1->message ?> :- </th>
             <!-- <td>
                <?php 
				/*
                if(file_exists(public_path().'/uploads/'.$mv1->reply_message) && !empty($mv1->reply_message)){
                            ?>
                            {!! HTML::image('uploads/'.$mv1->reply_message) !!}
                            <?php
                        }
                        else{
                            echo $mv1->reply_message;
                        }
				*/		
                ?>
              </td> -->
            </tr>
          <?php
          }
        }
      else{
      ?>
        <tr>
          <th>{!! trans('back/mychannel.no_record') !!}</th>
        </tr>
      <?php
      }
      ?>	
		</table>
	</div>




@stop

@section('scripts')

@stop