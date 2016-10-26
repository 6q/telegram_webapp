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
				<?php
        if(!empty($questions)){
          foreach($questions as $k1 => $v1){
          ?>
            <tr>
              <th>{!! trans('back/bot.ques') !!}</th>
              <td>
                  <?php echo $v1->ques_heading ?>
              </td>
            </tr>
          <?php
          }
        }
      ?>	
		</table>
	</div>



@stop

@section('scripts')

@stop