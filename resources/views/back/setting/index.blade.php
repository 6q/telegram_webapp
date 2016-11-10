@extends('back.template')

@section('head')

<style type="text/css">
  
  .badge {
    padding: 1px 8px 1px;
    background-color: #aaa !important;
  }

</style>

@stop

@section('main')


	@if(session()->has('ok'))
    @include('partials/error', ['type' => 'success', 'message' => session('ok')])
	@endif

  <div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			{!! trans('back/setting.view_site_setting') !!}
		</h1>
		<ol class="breadcrumb">
			<li class="active">
				<span class="fa fa-user"></span> {!! trans('back/setting.view_site_setting') !!}
			</li>
		</ol>
	</div>
</div>

	<div class="table-responsive">
    	{!! Form::open(['url' => 'setting', 'method' => 'post', 'class' => 'form-horizontal panel']) !!}	
        <table class="table">
        	
		<?php 
			if(isset($setting)){
				foreach($setting as $k1 => $v1){
					?>
                    	<tr>
                        	<td width="15%"><?php echo $v1->name; ?></td>
                            <td>{!! Form::control('text', 0, $v1->name, $errors, '',$v1->value) !!}</td>
                        </tr>
                    <?php
				}
			}
		?>
        	<tr>
            	<td>&nbsp;</td>
                <td>{!! Form::submit(trans('front/form.send')) !!}</td>
            </tr>
        </table>
        {!! Form::close() !!}
	</div>


@stop

@section('scripts')

@stop