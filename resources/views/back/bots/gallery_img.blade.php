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
          <th>{!! trans('back/bot.img_title') !!}</th>
          <th>{!! trans('back/bot.image') !!}</th>
        </tr>
				<?php
        if(!empty($g_images)){
          foreach($g_images as $k1 => $v1){
          ?>
            <tr>
              <td><?php echo $v1->title ?></td>
              <td>{!! HTML::image('uploads/'.$v1->image) !!}</td>
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