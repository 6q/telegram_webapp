@extends('front.template')
@section('main')

	{!! HTML::script('js/jquery.payment.js') !!}

	<script>
        jQuery(function($) {
            $('#card_exp').payment('formatCardExpiry');
        });
	</script>

	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript">

        $(document).ready(function(){
            Stripe.setPublishableKey('<?php echo env('STRIPE_APP_PUBLIC_KEY')?>');
        });

        function stripeResponseHandler(status, response){
            if (response.error)
            {
                // re-enable the submit button
                $('.submit-button').removeAttr("disabled");

                // show hidden div
                // show the errors on the form
                $(".payment-errors").html(response.error.message);
                return false;
            }
            else
            {
                // token contains id, last4, and card type
                var token = response['id'];
                $('#stripeToken').val(token);
                muFunction(4);
            }
        }
	</script>


	<div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

		@include('front.top')

		{!! Form::open(['url' => 'bot', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'payment-form']) !!}

		{!! Form::hidden('plan_id', 'plan_id', array('id' => 'plan_idd')) !!}
		{!! Form::hidden('plan_price', 'plan_price', array('id' => 'plan_pricee')) !!}
		{!! Form::hidden('stripeToken', 'stripeToken', array('id' => 'stripeToken')) !!}

		<div id="row1">
			<div class="my_account telegram">
				<h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/bots.telegram') }}</span></h4>
				<h5>{{ trans('front/bots.our_plans') }}</h5>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 els_plans" style="padding:20px 50px;">
				<p>
					{{ trans('front/bots.our_plans_1') }}
				</p>
				<p>
					{{ trans('front/bots.our_plans_2') }}
				</p>
				<p>
					{{ trans('front/bots.our_plans_3') }}
				</p>
			</div>
			<div class="buying">
				<div class="buying_table">

                    <?php // echo '<pre>';print_r($plans);echo '</pre>';?>

					<div class="row_1 heading">

                        <?php
                        if(isset($plans) && !empty($plans)){
                        foreach($plans as $p1 => $pv1){
                        $planId = $pv1->id;
                        $planName = $pv1->name;
                        $planPrice = $pv1->price;
                        $planTimePeriod = $pv1->duration;

                        $autoresponses = $pv1->autoresponses;
                        $contact_forms = $pv1->contact_forms;
                        $image_gallery = $pv1->image_gallery;
                        $manual_message_interval = $pv1->manual_message_interval;

                        switch ($pv1->name) {
							case "FREE":
								$color = "grey";
								break;
							case "AMTU":
								$color = "grey";
								break;
							case "PREMIUM":
								$color = "red";
								break;
							case "BASIC":
								$color = "blue";
								break;
							case "STANDARD":
								$color = "blue";
								break;
							case "PRO":
								$color = "green";
								break;
                        }


                        ?>
                        <? if ($pv1->name == "AMTU") { ?>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<? } else { ?>
								<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
								<? } ?>
							<!-- PRICE ITEM -->
							<div class="panel price panel-<?=$color?>"  onclick="muFunctionPlan('<?php echo $planId;?>','<?php echo $planName;?>','<?php echo $planPrice;?>','<?php echo $planTimePeriod;?>','2');" style="cursor:pointer">
								<div class="panel-heading  text-center">
									<h3>
                                    <? if ($pv1->name != "FREE") { ?>
										<?php echo $pv1->name;?>
                                    <?} else echo trans('front/bots.free'); ?>
									</h3>
								</div>
								<div class="panel-body text-center">
									<p class="lead" style="font-size:16px">
										<strong>
                                            <? if ($pv1->price>0) { ?>
											<span><?php echo $pv1->price;?></span>
											<small>
												€/<?php
                                                if($pv1->duration == 3){
                                                echo trans('front/bots.each_quarter');
                                                }
                                                else{
                                                echo ' '.trans('front/bots.every').' '.$pv1->duration.' '.trans('front/bots.months');
                                                }
                                                ?>
											</small>
                                            <?} else echo trans('front/bots.free'); ?>
										</strong>
									</p>
								</div>
								<ul class="list-group list-group-flush text-center">
									<li class="list-group-item">
										<b><?php echo $pv1->autoresponses<999 ? $pv1->autoresponses : "&infin;"; ?></b> {{ trans('front/bots.autoresponses') }}
										<i class="icon-ok text-info"></i>
									</li>
									<li class="list-group-item">
										<b>
                                            <?php
                                            if($pv1->contact_forms == 0){
                                            echo 'NO';
                                            }
                                            else{
                                             echo $pv1->contact_forms<999 ? $pv1->contact_forms : "&infin;";
                                            }
                                            ?>
										</b>
										{{ trans('front/bots.contact_forms') }} <i class="icon-ok text-info"></i>
									</li>
									<li class="list-group-item">
										<b><?php echo $pv1->image_gallery<999 ? $pv1->image_gallery : "&infin;"; ?></b> {{ trans('front/bots.photo_galleries') }} <i class="icon-ok text-info"></i>
									</li>
									<li class="list-group-item">
										<b><?php echo $pv1->gallery_images<999 ? $pv1->gallery_images : "&infin;"; ?></b> {{ trans('front/bots.images_per_gallery') }} <i class="icon-ok text-info"></i>
									</li>
									<li class="list-group-item">
										<b><?php echo $pv1->bot_commands<999 ? $pv1->bot_commands : "&infin;"; ?></b> {{ trans('front/bots.bot_commands') }} <i class="icon-ok text-info"></i>
									</li>
									<li class="list-group-item">
										<? if ($pv1->manual_message>999) {
                                        	echo trans('front/bots.no_limit');
										} else {
											echo '<b>'.$pv1->manual_message.'</b> ';

											switch ($manual_message_interval) {
												case "day":
													echo trans('front/bots.per_day');
													break;
												case "month":
													echo trans('front/bots.per_month');
													break;
												case "week":
													echo trans('front/bots.per_week');
													break;
											}
										}
                                        ?>
										<i class="icon-ok text-info"></i></li>
									<li class="list-group-item">
										{{ trans('front/bots.editable_options') }} <i class="icon-ok text-success"></i>
									</li>
								</ul>
								<div class="panel-footer">
									<a href="javascript:void(0);" onclick="muFunctionPlan('<?php echo $planId;?>','<?php echo $planName;?>','<?php echo $planPrice;?>','<?php echo $planTimePeriod;?>','2');" class="btn btn-lg btn-block btn-<?=$color;?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
								</div>
							</div>
							<!-- /PRICE ITEM -->
						</div>
                        <?php
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
		</div>

		<div id="row2" style="display:none;">
			<div class="my_account telegram">
				<h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/bots.telegram') }}</span></h4>
				<h5>{{ trans('front/bots.create') }}</h5>
			</div>

			<div class="buying">
				<div class="create_bot">
					<div class="how-to-create">
						<p>{{ trans('front/bots.how_to_create') }}</p>
						<div class="click_button"><span><a href="javascript:void(0);" onclick="mypopupinfo('BotUserNameModal');">{{ trans('front/bots.click_here') }}</a></span></div>
					</div>



					<div class="crete_bot_form">
						<ul>
							<li>
								<span>{{ trans('front/bots.bot_username') }}</span>
								<label id="uName">{!! Form::control('text', 0, 'username', $errors,'','','required') !!}</label>
								<p><small>* {{ trans('front/bots.bot_username_help') }}</small></p>
							</li>

							<li>
								<span>{{ trans('front/bots.bot_access_token') }}</span>
								<label id="aToken">{!! Form::control('text', 0, 'bot_token', $errors,'','','required') !!}</label>
							</li>
						</ul>

					</div>
					<div class="crete_bot_form">
						<ul>

							<li>
								<span>{{ trans('front/bots.start_message') }}</span>
								<label class="lead emoji-picker-container text-area">
									{!! Form::control('textarea', 0, 'start_message', $errors,'',trans('front/bots.welcome_default_message'),"data-emojiable='true' placeholder='".trans('front/bots.welcome_default_message')."' maxlength='250' required") !!}
								</label>
							</li>
							<li>
								<span>{{ trans('front/bots.list_all_commands') }}</span>
								<label  class="lead">
									{!! Form::control('text', 0, 'comanda', $errors, '',trans('front/bots.list_command'), " placeholder='".trans('front/bots.list_command')."' maxlength='50' required") !!}
								</label>

								<p><small>* {{ trans('front/bots.list_command_help') }}</small></p>
							</li>
							<li>
								<span>{{ trans('front/bots.bot_error_msg') }}</span>
								<label id="aError_msg"  class="lead emoji-picker-container">
									{!! Form::control('text', 0, 'error_msg', $errors,'',trans('front/bots.error_default_message'),"data-emojiable='true' placeholder='".trans('front/bots.error_default_message')."' maxlength='50' required") !!}
								</label>
							</li>
						</ul>

					</div>

					<div class="crete_bot_form">
						<ul>
							<li class="example_information col-sm-6">
								<span>{{ trans('front/bots.name_of_autoresponses_button') }}</span>
								<label  class="lead emoji-picker-container" id="boto_autorespostes">
									{!! Form::control('text', 0, 'autoresponse', $errors,'',trans('front/bots.default_information'),"data-emojiable='true' placeholder='".trans('front/bots.default_information')."' maxlength='20' required") !!}
								</label>
							</li>

							<li class="example_information col-sm-6">
								<span>{{ trans('front/bots.intortext_of_autoresponses_button') }}</span>
								<label  class="lead emoji-picker-container">
									{!! Form::control('text', 0, 'intro_autoresponses', $errors,'',trans('front/bots.default_information_description'),"data-emojiable='true' placeholder='".trans('front/bots.default_information_description')."' maxlength='120' required") !!}
								</label>
							</li>
						</ul>
					</div>
					<div class="crete_bot_form">
						<ul>
							<li class="example_contact col-sm-6">
								<span>{{ trans('front/bots.name_of_contact_forms_button') }}</span>
								<label  class="lead emoji-picker-container" id="boto_formularis">
									{!! Form::control('text', 0, 'contact_form', $errors,'',trans('front/bots.default_contact'),"data-emojiable='true' placeholder='".trans('front/bots.default_contact')."' maxlength='20' required") !!}
								</label>
							</li>

							<li class="example_contact col-sm-6">
								<span>{{ trans('front/bots.intortext_of_contact_forms_button') }}</span>
								<label  class="lead emoji-picker-container">
									{!! Form::control('text', 0, 'intro_contact_form', $errors,'',trans('front/bots.default_contact_description'),"data-emojiable='true' placeholder='".trans('front/bots.default_contact_description')."' maxlength='120' required") !!}
								</label>
							</li>
						</ul>
					</div>
					<div class="crete_bot_form">
						<ul>
							<li class="example_our_photos col-sm-6">
								<span>{{ trans('front/bots.name_of_galleries_button') }}</span>
								<label  class="lead emoji-picker-container" id="boto_galeries">
									{!! Form::control('text', 0, 'galleries', $errors,'',trans('front/bots.default_galleries'),"data-emojiable='true' placeholder='".trans('front/bots.default_galleries')."' maxlength='20' required") !!}
								</label>
							</li>

							<li class="example_our_photos col-sm-6">
								<span>{{ trans('front/bots.introtext_of_galleries_button') }}</span>
								<label  class="lead emoji-picker-container">
									{!! Form::control('text', 0, 'intro_galleries', $errors,'',trans('front/bots.default_information_description'),"data-emojiable='true' placeholder='".trans('front/bots.default_information_description')."' maxlength='120' required") !!}
								</label>
							</li>
						</ul>
					</div>
					<div class="crete_bot_form">
						<ul>
							<li class="example_our_channels col-sm-6">
								<span>{{ trans('front/bots.name_of_channels_button') }} </span>
								<label  class="lead emoji-picker-container"id="boto_canals">
									{!! Form::control('text', 0, 'channels', $errors,'',trans('front/bots.default_channels'),"data-emojiable='true' placeholder='".trans('front/bots.default_channels')."' maxlength='20' required") !!}
								</label>
							</li>

							<li class="example_our_channels col-sm-6">
								<span>{{ trans('front/bots.introtext_of_channels_button') }} </span>
								<label  class="lead emoji-picker-container">
									{!! Form::control('text', 0, 'intro_channels', $errors,'',trans('front/bots.default_channels_description'),"data-emojiable='true' placeholder='".trans('front/bots.default_channels_description')."' maxlength='120' required") !!}
								</label>
							</li>
						</ul>
					</div>

					<div class="buy_now">
						<a href="javascript:void(0);" onclick="muFunctionBack('2');">{{ trans('front/bots.back') }}</a>
						<a href="javascript:void(0);" onclick="muFunctionBotForm('3');">{{ trans('front/bots.next') }}</a>
					</div>

				<!--<div class="submit">
				  {!! Form::submit_new(trans('front/form.send')) !!}
						</div>-->

				</div>
			</div>
		</div>

		<div id="row3" style="display:none;">
			<div class="my_account telegram">
				<h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/bots.telegram') }}</span></h4>
				<h5>{{ trans('front/bots.enter_details') }}</h5>
			</div>

			<div class="buying">
				<div class="create_bot">
					<div class="create_bot_form">
						<fieldset>
							<!-- Form Name -->
							<legend>{{ trans('front/bots.billing_details') }}</legend>

							<!-- Street -->
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ trans('front/bots.street') }}</label>
								<div class="col-sm-6" id="stretEr">
                                    <?php
                                    $street = '';
                                    if(isset($billing_details[0]->street) && !empty($billing_details[0]->street)){
                                    $street = $billing_details[0]->street;
                                    }
                                    ?>
									{!! Form::control('text', 0, 'street', $errors,'',$street) !!}
								</div>
							</div>

							<!-- Country -->
							<div class="form-group">
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.country') }}</label>
								<div class="col-sm-6" id="countryEr">
                                    <?php
                                    $country_id = '';
                                    if(isset($billing_details[0]->country_id) && !empty($billing_details[0]->country_id)){
                                    $country_id = $billing_details[0]->country_id;
                                    }
                                    ?>

									<select id="country" name="country" class="form-control">
										<option value=""></option>
                                        <?php
                                        if(!empty($country)){
                                        foreach($country as $k1 => $v1){
                                        $select = '';
                                        if($country_id == $v1->id){
                                        $select = 'selected="selected"';
                                        }
                                        echo '<option '.$select.' value="'.$v1->id.'">'.$v1->name.'</option>';
                                        }
                                        }
                                        ?>
									</select>
								</div>
							</div>

							<!-- State -->
							<div class="form-group">
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.state') }}</label>
								<div class="col-sm-6" id="stateEr">
									<select id="state" name="state" class="form-control">
                                        <?php
                                        $state_id = '';
                                        if(isset($billing_details[0]->state_id) && !empty($billing_details[0]->state_id)){
                                        $state_id = $billing_details[0]->state_id;
                                        }

                                        $stateHtml = '<option value=""></option>';
                                        if (!empty($states)) {
                                        foreach ($states as $k1 => $v1) {
                                        $select = '';
                                        if($state_id == $v1->id){
                                        $select = 'selected="selected"';
                                        }
                                        $stateHtml .= '<option '.$select.' value="' . $v1->id . '">' . $v1->name . '</option>';
                                        }
                                        }

                                        echo $stateHtml;
                                        ?>
									</select>
								</div>
							</div>

							<!-- City -->
							<div class="form-group">
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.city') }}</label>
								<div class="col-sm-6" id="cityEr">
                                    <?php
                                    $city = '';
                                    if(isset($billing_details[0]->city) && !empty($billing_details[0]->city)){
                                    $city = $billing_details[0]->city;
                                    }
                                    ?>
									{!! Form::control('text', 0, 'city', $errors,'',$city) !!}
								</div>
							</div>

							<!-- Postcal Code -->
							<div class="form-group">
                                <?php
                                $zip = '';
                                if(isset($billing_details[0]->zipcode) && !empty($billing_details[0]->zipcode)){
                                $zip = $billing_details[0]->zipcode;
                                }
                                ?>
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.postal_code') }}</label>
								<div class="col-sm-6" id="zipEr">
									{!! Form::control('text', 0, 'zip', $errors,'',$zip) !!}
								</div>
							</div>


							<!-- Email -->
							<div class="form-group">
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.email') }}</label>
								<div class="col-sm-6" id="emailEr">
									{!! Form::control_stripe_email('text', 0, 'email', $errors,'',$email) !!}
								</div>
							</div>
						</fieldset>

						<fieldset>
							<legend>{{ trans('front/bots.card_details') }}</legend>

							<!-- Card Holder Name -->
							<div class="form-group">
								<label class="col-sm-4 control-label"  for="textinput">{{ trans('front/bots.card_holder_name') }}</label>
								<div class="col-sm-6" id="cardnameEr">
									{!! Form::control('text', 0, 'cardholdername', $errors) !!}
								</div>
							</div>

							<!-- Card Number -->
							<div class="form-group">
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.card_number') }}</label>
								<div class="col-sm-6" id="cardEr">
									{!! Form::control('text', 0, 'cardnumber', $errors) !!}
								</div>
								<small class="text-muted"></small>
							</div>

							<!-- Expiry-->
							<div class="form-group">
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.card_exp_date') }}</label>
								<div class="col-sm-6" id="cardExp">
									{!! Form::control('text', 0, 'card_exp', $errors) !!}
								</div>
							</div>

							<!-- CVV -->
							<div class="form-group">
								<label class="col-sm-4 control-label" for="textinput">{{ trans('front/bots.card_cvv') }}</label>
								<div class="col-sm-3" id="cardCv">
									{!! Form::control('text', 0, 'cvv', $errors) !!}
								</div>
							</div>

							<div class="form-group">
								<span class='payment-errors'></span>
							</div>

						</fieldset>
						<div class="buy_now">
							<a href="javascript:void(0);" onclick="muFunctionBack('3');">{{ trans('front/bots.back') }}</a>
							<a href="javascript:void(0);" onclick="muFunctionCard('4');">{{ trans('front/bots.next') }}</a>
						</div>

					</div>
				</div>
			</div>


		</div>

		<div id="row4" style="display:none;">
			<div class="my_account telegram">
				<h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/bots.telegram') }}</span></h4>
				<h5>{{ trans('front/bots.card_detals') }}</h5>
			</div>
			<div class="buying">
				<div class="create_bot">
					<div class="crete_bot_form">
						<ul>
							<li>
								<span>Plan Name</span>
								<label id="plan_name"></label>
							</li>

							<li>
								<span>Price</span>
								<label id="plan_price"></label><p>$</p>
							</li>

							<li>
								<span>Time period</span>
								<label id="plan_time_period"></label>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="buying">
				<div class="create_bot">
					<div class="crete_bot_form">
						<ul>
							<li>
								<span>{{ trans('front/bots.bot_username') }}</span>
								<label id="bot_usname"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.bot_access_token') }}</span>
								<label id="bot_acces_tkn"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.bot_error_msg') }}</span>
								<label id="bot_error_msg"></label>
							</li>

						<!--<li>
				  <span>{{ trans('front/bots.description') }}</span>
				  <label id="description"></label>
				</li>-->

							<li>
								<span>{{ trans('front/bots.start_message') }}</span>
								<label id="start_messages"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.name_of_autoresponses_button') }}</span>
								<label id="name_a_btn"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.name_of_contact_forms_button') }}</span>
								<label id="name_c_btn"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.name_of_galleries_button') }}</span>
								<label id="name_g_btn"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.name_of_channels_button') }}</span>
								<label id="name_ch_btn"></label>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="buying">
				<div class="create_bot">
					<div class="crete_bot_form">
						<ul>
							<li>
								<span>{{ trans('front/bots.street') }}</span>
								<label id="sstreet"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.city') }}</span>
								<label id="citty"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.state') }}</span>
								<label id="sstate"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.postal_code') }}</span>
								<label id="ppostal_code"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.country') }}</span>
								<label id="ccountry"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.email') }}</span>
								<label id="eemail"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.card_holder_name') }}</span>
								<label id="card_hld_name"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.card_number') }}</span>
								<label id="card_noo"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.card_exp_date') }}</span>
								<label id="card_exp_d"></label>
							</li>

							<li>
								<span>{{ trans('front/bots.card_cvv') }}</span>
								<label id="cvv_cvv2"></label>
							</li>
						</ul>
					</div>
				</div>
			</div>


			<div class="buy_now">
				<a href="{!! URL::to('/bot/create') !!}">{{ trans('front/bots.cancel') }}</a>
			</div>
			<div class="submit">
				{!! Form::submit_new(trans('front/form.send')) !!}
			</div>
		</div>

		{!! Form::close() !!}

	</div>

	<div id="alertMsg" style="display:none;">
		<div id="resp" class="alert-new alert-success-new alert-dismissible" role="alert">
		</div>
	</div>


	<!-- Modal -->
	<div id="nickNameModal" class="modal fade" role="dialog" style="display:none";>
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{!! trans('front/bots.bot_nick_name') !!}</h4>
				</div>
				<div class="modal-body">
					<p><?php echo $nickName[0]->content;?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>

	<div id="BotUserNameModal" class="modal fade" role="dialog" style="display:none";>
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{!! trans('front/bots.name') !!}</h4>
				</div>
				<div class="modal-body">
					<p><?php echo $botUserName[0]->content;?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>


	<div id="BotAccessTokenModal" class="modal fade" role="dialog" style="display:none";>
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{!! trans('front/bots.bot_token') !!}</h4>
				</div>
				<div class="modal-body">
					<p><?php echo $botAccessToken[0]->content;?></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>



	<script>
        jQuery(document).ready(function() {
            // Initializes and creates emoji set from sprite sheet
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: '/lib/img',
                popupButtonClasses: 'fa fa-smile-o'
            });
            // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
            // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
            // It can be called as many times as necessary; previously converted input fields will not be converted again
            window.emojiPicker.discover();
        });
        $(document).ready(function(e) {
            $('#auto_resp').html($('#boto_autorespostes').find('div.emoji-wysiwyg-editor').html());
            $('#conntact_fbutton').html($('#boto_formularis').find('div.emoji-wysiwyg-editor').html());
            $('#gallery_imgs').html($('#boto_galeries').find('div.emoji-wysiwyg-editor').html());
            $('#chnl_btn').html($('#boto_canals').find('div.emoji-wysiwyg-editor').html());

            $('#boto_autorespostes').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#auto_resp').html($('#boto_autorespostes').find('div.emoji-wysiwyg-editor').html());
            });

            $('#boto_formularis').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#conntact_fbutton').html($('#boto_formularis').find('div.emoji-wysiwyg-editor').html());
            });

            $('#boto_galeries').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#gallery_imgs').html($('#boto_galeries').find('div.emoji-wysiwyg-editor').html());
            });

            $('#boto_canals').find('div.emoji-wysiwyg-editor').keyup(function(e) {
                $('#chnl_btn').html($('#boto_canals').find('div.emoji-wysiwyg-editor').html());
            });

        });

        function muFunctionPlan(plan_id, plan_name,plan_price,duration,id){
            $('#plan_name').html(plan_name);
            $('#plan_price').html(plan_price);
            $('#plan_time_period').html(duration);

            $('#plan_idd').val(plan_id);
            $('#plan_pricee').val(plan_price);

            muFunction(id);
        }

        function muFunctionBotForm(id){
            var bot_uname = $('#username').val();
            var bot_access_token = $('#bot_token').val();
            //var bot_description = $('#bot_description').val();
            var bot_start_msg = $('#start_message').val();
            var bot_error_msg = $('#error_msg').val();
            var bot_name_of_a_btn = $('#autoresponse').val();
            var bot_name_of_c_btn = $('#contact_form').val();
            var bot_name_of_g_btn = $('#galleries').val();
            var bot_name_of_ch_btn = $('#channels').val();



            var chk=1;
            if(bot_uname == ''){
                $('#uName .form-group').addClass('has-error');
                chk = 0;
            }
            else{
                $('#uName .form-group').removeClass('has-error');
            }

            if(bot_access_token == ''){
                $('#aToken .form-group').addClass('has-error');
                chk = 0;
            }
            else{
                $('#aToken .form-group').removeClass('has-error');
            }

            if(bot_error_msg == ''){
                $('#aError_msg .form-group').addClass('has-error');
                chk = 0;
            }
            else{
                $('#aError_msg .form-group').removeClass('has-error');
            }

            if(chk == 0){
                $(window).scrollTop(300);
                return false;
            }

            $('#bot_usname').html(bot_uname);
            $('#bot_acces_tkn').html(bot_access_token);
            $('#bot_error_msg').html(bot_error_msg);
            // $('#description').html(bot_description);
            $('#start_messages').html(bot_start_msg);

            $('#name_a_btn').html(bot_name_of_a_btn);
            $('#name_c_btn').html(bot_name_of_c_btn);
            $('#name_g_btn').html(bot_name_of_g_btn);
            $('#name_ch_btn').html(bot_name_of_ch_btn);

            var token = $('input[name=_token]').val();

            $.ajax({
                url: '<?php echo URL::to('/bot/setweb_hook')?>',
                headers: {'X-CSRF-TOKEN': token},
                data: {bot_uname: bot_uname, bot_access_token: bot_access_token},
                type:'POST',
                success: function (resp) {
                    if(resp == 1)
                    {
                        var check_price = $('#plan_pricee').val();
                        if(check_price > 0){
                            muFunction(id);
                        }
                        else{
                            $('#payment-form').submit();
                            //muFunction(4,2);
                        }
                    }
                    else{
                        $('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Some error occured. Please check bot token and username');
                        $('.alert-new').css('display','block');
                        $('#alertMsg').css('display','block');
                        return false;
                    }
                },
                error: function (request, status, error) {
                    $('#resp').html('<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>Some error occured. Please check bot token and username');
                    $('.alert-new').css('display','block');
                    $('#alertMsg').css('display','block');
                    return false;
                }
            });


        }


        function muFunctionCard(id){

            var street = $('#street').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var postal_code = $('#zip').val();
            var country = $('#country').val();
            var email = $('#email').val();
            var card_name = $('#cardholdername').val();
            var card_no = $('#cardnumber').val();
            var card_expDate = $('#card_exp').val();
            var card_cvv = $('#cvv').val();


            var cardType = $.payment.cardType($('#cardnumber').val());
            if(cardType != null || cardType != 'undefined'){
                $('.text-muted').html('[<span class="cc-brand">'+cardType+'</span>]');
            }

            var chk=1;

            if(street == ''){
                chk = 0;
                $('#stretEr .form-group').addClass('has-error');
            }
            else{
                $('#stretEr .form-group').removeClass('has-error');
            }

            if(city == ''){
                chk = 0;
                $('#cityEr .form-group').addClass('has-error');
            }
            else{
                $('#cityEr .form-group').removeClass('has-error');
            }


            if(state == ''){
                chk = 0;
                $('#stateEr .form-group').addClass('has-error');
            }
            else{
                $('#stateEr .form-group').removeClass('has-error');
            }

            if(postal_code == ''){
                chk = 0;
                $('#zipEr .form-group').addClass('has-error');
            }
            else{
                $('#zipEr .form-group').removeClass('has-error');
            }

            if(country == ''){
                chk = 0;
                $('#countryEr .form-group').addClass('has-error');
            }
            else{
                $('#countryEr .form-group').removeClass('has-error');
            }


            if(email == ''){
                chk = 0;
                $('#emailEr .form-group').addClass('has-error');
            }
            else if(!isValidEmailAddress(email)){
                chk = 0;
                $('#emailEr .form-group').addClass('has-error');
            }
            else{
                $('#emailEr .form-group').removeClass('has-error');
            }


            if(card_name == ''){
                chk = 0;
                $('#cardnameEr .form-group').addClass('has-error');
            }
            else{
                $('#cardnameEr .form-group').removeClass('has-error');
            }



            if($.payment.validateCardNumber(card_no)){
                $('#cardEr .form-group').removeClass('has-error');
            }
            else{
                chk = 0;
                $('#cardEr .form-group').addClass('has-error');
            }


            if($.payment.validateCardCVC(card_cvv, cardType)){
                $('#cardCv .form-group').removeClass('has-error');
            }
            else{
                chk = 0;
                $('#cardCv .form-group').addClass('has-error');
            }

            if($.payment.validateCardExpiry($('#card_exp').payment('cardExpiryVal'))){
                $('#cardExp .form-group').removeClass('has-error');
            }
            else{
                chk = 0;
                $('#cardExp .form-group').addClass('has-error');
            }

            if(chk == 1){
                $('#sstreet').html(street);
                $('#citty').html(city);
                $('#sstate').html(state);
                $('#ppostal_code').html(postal_code);
                $('#ccountry').html(country);
                $('#eemail').html(email);
                $('#card_hld_name').html(card_name);
                $('#card_noo').html(card_no);
                $('#card_exp_d').html(card_expDate);
                $('#cvv_cvv2').html(card_cvv);

                exp_month = card_expDate.split("/");

                Stripe.card.createToken({
                    number: card_no,
                    cvc: card_cvv,
                    exp_month: parseInt(exp_month[0]),
                    exp_year: parseInt(exp_month[1]),
                    name: card_name,
                    address_line1: street,
                    address_city: city,
                    address_zip: postal_code,
                    address_state: state,
                    address_country: country
                }, stripeResponseHandler);

                // muFunction(id);

            }
            else{
                return false;
            }
        }

        function muFunction(id,backId = ''){
            if(id == 2){
                $('.chat_box').css('display','block');
            }
            else{
                $('.chat_box').css('display','none');
            }
            var last_id = parseInt(id)-1;
            $('#row'+last_id).css('display','none');

            if(backId != ''){
                $('#row'+backId).css('display','none');
            }

            $('#row'+id).css('display','block');
        }

        function muFunctionBack(id){
            var last_id = parseInt(id)-1;
            if(last_id == 2){
                $('.chat_box').css('display','block');
            }
            else{
                $('.chat_box').css('display','none');
            }
            $('#row'+last_id).css('display','block');
            $('#row'+id).css('display','none');
        }



        function isValidEmailAddress(emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

            return pattern.test(emailAddress);
        }



	</script>


	<script>
        $(document).ready(function(){
            $('#country').change(function(){
                var countryId = $('#country').val();
                var token = $('input[name=_token]').val();

                url = '/bot/get_state/'+countryId;
                data = '';

                $.ajax({
                    url: url,
                    headers: {'X-CSRF-TOKEN': token},
                    data: data,
                    type: 'GET',
                    datatype: 'JSON',
                    success: function (resp) {
                        $('#state').html(resp);
                    }
                });

            });
        });


        function mypopupinfo(id){
            $('#'+id).modal();
        }
	</script>


@stop