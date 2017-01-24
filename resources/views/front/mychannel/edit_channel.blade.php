@extends('front.template')
@section('main')


<div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

    @include('front.top')  

    {!! Form::open(['url' => 'my_channel/update_channel/'.$channel->id, 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'payment-form']) !!}
    
    {!! Form::hidden('id', $channel->id, array('id' => 'bot')) !!}

    <div>
        <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/MyChannel.telegram') }}</span></h4>
            <h5>{{ trans('front/MyChannel.create') }}</h5>
        </div>

        <div class="buying">
            <div class="create_bot">
                <div class="crete_bot_form">
                    <ul>
                    	{!! Form::control('hidden',0,'botID',$errors,'',$bots[0]->id) !!}
                    	<!--<li>
                        	<label id="bName">
                            <div class="form-group">
                                <select id="botID" name="botID" class="form-control">
                                    <option value="">Select bot</option>
                                    <?php
									/*
                                    if (isset($bots) && !empty($bots)) {
                                    foreach ($bots as $b1 => $bv1) {
                                    ?>
                                    <option value="<?php echo $bv1->id; ?>"><?php echo $bv1->username;?></option>
                                    <?php
                                    }
                                    }
									*/
                                    ?>
                                </select>
                               </div> 
                    	</label>
                        </li>-->
                        
                        <li>
                            <span>{{ trans('front/MyChannel.name') }} {!! HTML::image('img/front/icon.png') !!}</span>
                            <label id="uName">{!! Form::control('text', 0, 'name', $errors,'',$channel->name) !!}</label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.description') }} {!! HTML::image('img/front/icon.png') !!}</span>
                            <label>{!! Form::control('textarea', 0, 'description', $errors,'',$channel->description) !!}</label>
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


@stop