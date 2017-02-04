@extends('front.template')
@section('main')

    {!! HTML::script('js/jquery-ui.js') !!}



    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

        @include('front.top')

        <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{!! trans('front/command.telegram') !!}</span></h4>
            <h5>{!! trans('front/command.create_bot_command') !!}</h5>
        </div>

        <div class="bot_command">
            <div class="bot_command_content">
                @if ($errors->has())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif
                <h2>{!! trans('front/bots.upgrade_plan') !!}</h2>
            </div>
            
           
          {!! Form::open(['url' => 'bot/upgradeplan/'.$bot_id, 'method' => 'post', 'class' => 'form-horizontal panel','id'=>'plan_upgrade_form','enctype'=>"multipart/form-data"]) !!}  
          {!! Form::control('hidden', 0, 'bot_id', $errors, '',$bot_id) !!}
          <div class="buying">
				<div class="buying_table">

					<?php // echo '<pre>';print_r($plans);echo '</pre>';?>

					<div class="row_1 heading">

									<?php
									if(isset($plans) && !empty($plans))
									{
										foreach($plans as $p1 => $pv1)
										{
											if($pv1->id == $subscriptions[0]->plan_id){
											}
											else
											{
												$planId = $pv1->id;
												$planName = $pv1->name;
												$planPrice = $pv1->price;
												$planTimePeriod = $pv1->duration;
			
												$autoresponses = $pv1->autoresponses;
												$contact_forms = $pv1->contact_forms;
												$image_gallery = $pv1->image_gallery;
												$botCommands = $pv1->bot_commands;
			
												?>
												<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
													<!-- PRICE ITEM -->
													<div class="panel price panel-blue">
														<div class="panel-heading  text-center">
															<h3><?php echo $pv1->name;?></h3>
														</div>
														<div class="panel-body text-center">
															<p class="lead" style="font-size:16px">
																<strong>
																	<span><?php echo $pv1->price;?>€</span>
																	<small>
																		<?php
																		if($pv1->duration == 3){
																		echo ' cada trimestre';
																		}
																		else{
																		echo ' cada '.$pv1->duration.' mesos';
																		}
																		?>
																	</small>
																</strong>
															</p>
														</div>
														<ul class="list-group list-group-flush text-center">
															<li class="list-group-item">
																<b><?php echo $pv1->autoresponses;?></b> autorespostes
																<i class="icon-ok text-info"></i>
															</li>
															<li class="list-group-item">
																<b>
																	<?php
																	if($pv1->contact_forms == 0){
																	echo 'NO';
																	}
																	else{
																	echo $pv1->contact_forms;
																	}
																	?>
																</b>
																formularis de contacte <i class="icon-ok text-info"></i>
															</li>
															<li class="list-group-item">
																<b><?php echo $pv1->image_gallery ?></b> galeries de fotografies <i class="icon-ok text-info"></i>
															</li>
															<li class="list-group-item">
																<b><?php echo $pv1->gallery_images ?></b> imatges per galeria <i class="icon-ok text-info"></i>
															</li>
															<li class="list-group-item">
																<b><?php echo $pv1->bot_commands ?></b> bot commands <i class="icon-ok text-info"></i>
															</li>
															<li class="list-group-item">
																<b><?php echo $pv1->manual_message;?></b> comunicats per dia <i class="icon-ok text-info"></i></li>
															<li class="list-group-item">Benvinguda editable <i class="icon-ok text-success"></i></li>
															<li class="list-group-item">Missatge d'error editable <i class="icon-ok text-success"></i></li>
														</ul>
														<div class="panel-footer">
															<a href="javascript:void(0);" onclick="PlanUpgrade('<?php echo $planId;?>');" class="btn btn-lg btn-block btn-success">Escojer</a>
														</div>
													</div>
													<!-- /PRICE ITEM -->
												</div>
												<?php
											}
										}
									}
									else{
									?>
									{!! trans('front/bots.no_record_found') !!}
									<?php
									}
									?>



					</div>
				</div>

				<div class="buy_now">
						<a href="{!! URL::to('/dashboard') !!}">{{ trans('front/bots.back') }}</a>
				</div>
			</div>
            {!! Form::close() !!}

           
        </div>
    </div>

    <style>
        #image-holder{
            display: inline-block;
            width: 20%;
        }

        .bot_command_form input {
            padding: 16px;
        }

        textarea {
            width: 100%;
        }

        .browse_content input[type="file"] {
            left: 0;
            opacity: 0;
            padding: 10px 0;
            position: absolute;
            top: 0;
            width: 102px;
            z-index: 9;
            cursor:pointer;
        }
        .browse_content {
            position: relative;
        }
        .browse_content span {
            background: rgba(0, 0, 0, 0) linear-gradient(0deg, #e3e3e3, #ededed, #f7f7f7) repeat scroll 0 0;
            border: 1px solid #c7c7c7;
            border-radius: 5px;
            color: #a0a0a0;
            display: inline-block;
            font-weight: normal;
            padding: 12px 19px;
            text-transform: capitalize;
            width: auto;
        }

        .form-control {
            height: auto;
        }
    </style>
    
    
    <script>
    	function PlanUpgrade(planID){
			var input = $("<input>").attr("type", "hidden").attr("name", "plan_id").val(planID);
			$('#plan_upgrade_form').append($(input));
			$('#plan_upgrade_form').submit();
		}
    </script>

@stop        