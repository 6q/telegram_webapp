@extends('front.template')
@section('main')

{!! HTML::script('js/jquery.payment.js') !!}

<script>
    jQuery(function ($) {
        $('#card_exp').payment('formatCardExpiry');
    });
</script>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">

    $(document).ready(function () {
        Stripe.setPublishableKey('<?php echo env('STRIPE_APP_PUBLIC_KEY') ?>');
    });

    function stripeResponseHandler(status, response) {
        if (response.error)
        {
            // re-enable the submit button
            $('.submit-button').removeAttr("disabled");

            // show hidden div
            // show the errors on the form
            $(".payment-errors").html(response.error.message);
            return false;
        } else
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

    {!! Form::open(['url' => 'my_channel', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'payment-form-chanel']) !!}

    {!! Form::hidden('plan_id', 'plan_id', array('id' => 'plan_idd')) !!}
    {!! Form::hidden('plan_price', 'plan_price', array('id' => 'plan_pricee')) !!}
    {!! Form::hidden('stripeToken', 'stripeToken', array('id' => 'stripeToken')) !!}
    {!! Form::hidden('botID',$bots[0]->id,array('id' => 'botID')) !!}

    <div id="row1">
        <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/MyChannel.telegram') }}</span></h4>
            <h5>{{ trans('front/MyChannel.our_plans') }}</h5>
        </div>

        <div class="buying">
            <div class="buying_table">

                <?php // echo '<pre>';print_r($plans);echo '</pre>'; ?>

                <div class="row_1 heading">

                    <?php
                    if (isset($plans) && !empty($plans)) {
                        foreach ($plans as $p1 => $pv1) {
                            $planId = $pv1->id;
                            $planName = $pv1->name;
                            $planPrice = $pv1->price;
                            $planTimePeriod = $pv1->duration;

                            $autoresponses = $pv1->autoresponses;
                            $contact_forms = $pv1->contact_forms;
                            $image_gallery = $pv1->image_gallery;
                            ?>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
                                                echo ' '.trans('front/bots.each_quarter');
                                                }
                                                else{
                                                echo ' '.trans('front/bots.every').' '.$pv1->duration.' '. trans('front/bots.months');
                                                }
                                                ?>
                                            </small>
                                        </strong>
                                    </p>
                                </div>
                                <ul class="list-group list-group-flush text-center">
                                    <li class="list-group-item">
                                        <b><?php echo $pv1->manual_message; ?></b> {{ trans('front/MyChannel.manual_message_per_day') }}
                                    </li>
                                </ul>
                                <div class="panel-footer">
                                    <a href="javascript:void(0);" onclick="muFunctionPlan('<?php echo $planId;?>','<?php echo $planName;?>','<?php echo $planPrice;?>','<?php echo $planTimePeriod;?>','2');" class="btn btn-lg btn-block btn-success"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <!-- /PRICE ITEM -->
                        </div>
                            <?php
                        }
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
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/MyChannel.telegram') }}</span></h4>
            <h5>{{ trans('front/MyChannel.create') }}</h5>
        </div>

        <div class="buying">
            <div class="create_bot">
                <div class="how-to-create">
                    <p>{{ trans('front/MyChannel.how_to_create') }}</p>
                    <div class="click_button"><span><a  href="javascript:void(0);" onclick="mypopupinfo('ChannelNameModal');">{{ trans('front/MyChannel.click_here') }}</a></span></div>
                </div>



                <div class="crete_bot_form">
                    <ul>
                    	
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
                                    <option value="<?php echo $bv1->id; ?>" data-rel="<?php echo $bv1->username;?>"><?php echo $bv1->username;?></option>
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
                            <span>{{ trans('front/MyChannel.name') }}</span>
                            <label id="uName">{!! Form::control('text', 0, 'name', $errors) !!}</label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.share_link') }}</span>
                            <label id="uShareLink">{!! Form::control('text', 0, 'share_link', $errors) !!}</label>
                        </li>

                    </ul>

                </div>



                <div class="buy_now">
                    <a href="javascript:void(0);" onclick="muFunctionBack('2');">{{ trans('front/MyChannel.back') }}</a>
                    <a href="javascript:void(0);" onclick="muFunctionBotForm('3');">{{ trans('front/MyChannel.next') }}</a>
                </div>                

                <!--<div class="submit">
                  {!! Form::submit_new(trans('front/form.send')) !!}
                </div>-->

            </div>
        </div>
    </div>

    <div id="row3" style="display:none;">
        <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/MyChannel.telegram') }}</span></h4>
            <h5>{{ trans('front/MyChannel.enter_details') }}</h5>
        </div>

        <div class="buying">
            <div class="create_bot">
                <div class="create_bot_form">
                    <fieldset>
                        <!-- Form Name -->
                        <legend>{{ trans('front/MyChannel.billing_details') }}</legend>

                        <!-- Street -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label">{{ trans('front/MyChannel.street') }}</label>
                            <div class="col-sm-6" id="stretEr">
                                {!! Form::control('text', 0, 'street', $errors) !!}
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.country') }}</label>
                            <div class="col-sm-6" id="countryEr"> 
                                <select id="country" name="country" class="form-control">
                                    <option value="">Select Country</option>
                                    <?php
                                    if (!empty($country)) {
                                        foreach ($country as $k1 => $v1) {
                                            echo '<option value="' . $v1->id . '">' . $v1->name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  

                        <!-- State -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.state') }}</label>
                            <div class="col-sm-6" id="stateEr">
                                <select id="state" name="state" class="form-control">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.city') }}</label>
                            <div class="col-sm-6" id="cityEr">
                                {!! Form::control('text', 0, 'city', $errors) !!}
                            </div>
                        </div>   

                        <!-- Postcal Code -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.postal_code') }}</label>
                            <div class="col-sm-6" id="zipEr">
                                {!! Form::control('text', 0, 'zip', $errors) !!}
                            </div>
                        </div>


                        <!-- Email -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.email') }}</label>
                            <div class="col-sm-6" id="emailEr">
                                {!! Form::control_stripe_email('text', 0, 'email', $errors,'',$email) !!}
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>{{ trans('front/MyChannel.card_details') }}</legend>

                        <!-- Card Holder Name -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label"  for="textinput">{{ trans('front/MyChannel.card_holder_name') }}</label>
                            <div class="col-sm-6" id="cardnameEr">
                                {!! Form::control('text', 0, 'cardholdername', $errors) !!}
                            </div>
                        </div>

                        <!-- Card Number -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.card_number') }}</label>
                            <div class="col-sm-6" id="cardEr">
                                {!! Form::control('text', 0, 'cardnumber', $errors) !!}
                            </div>
                            <small class="text-muted"></small>
                        </div>

                        <!-- Expiry-->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.card_exp_date') }}</label>
                            <div class="col-sm-6" id="cardExp">
                                {!! Form::control('text', 0, 'card_exp', $errors) !!}
                            </div>
                        </div>

                        <!-- CVV -->
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="textinput">{{ trans('front/MyChannel.card_cvv') }}</label>
                            <div class="col-sm-3" id="cardCv">
                                {!! Form::control('text', 0, 'cvv', $errors) !!}
                            </div>
                        </div> 

                        <div class="form-group">
                            <span class='payment-errors'></span>
                        </div>    

                    </fieldset>
                    <div class="buy_now">
                        <a href="javascript:void(0);" onclick="muFunctionBack('3');">{{ trans('front/MyChannel.back') }}</a>
                        <a href="javascript:void(0);" onclick="muFunctionCard('4');">{{ trans('front/MyChannel.next') }}</a>
                    </div>

                </div>
            </div>
        </div>


    </div>

    <div id="row4" style="display:none;">
        <div class="my_account telegram">
            <h4>{!! HTML::image('img/front/telegrtam_icon.png') !!}<span>{{ trans('front/MyChannel.telegram') }}</span></h4>
            <h5>{{ trans('front/MyChannel.card_detals') }}</h5>
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
                            <span>{{ trans('front/MyChannel.bot_name') }}</span>
                            <label id="bot_name"></label>
                        </li>
                        
                        <li>
                            <span>{{ trans('front/MyChannel.name') }}</span>
                            <label id="chanel_name"></label>
                        </li>



                        <li>
                            <span>{{ trans('front/MyChannel.description') }}</span>
                            <label id="channel_description"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.share_link') }}</span>
                            <label id="channel_share_link"></label>
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
                            <span>{{ trans('front/MyChannel.street') }}</span>
                            <label id="sstreet"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.city') }}</span>
                            <label id="citty"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/bots.state') }}</span>
                            <label id="sstate"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.postal_code') }}</span>
                            <label id="ppostal_code"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.country') }}</span>
                            <label id="ccountry"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/bots.email') }}</span>
                            <label id="eemail"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.card_holder_name') }}</span>
                            <label id="card_hld_name"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.card_number') }}</span>
                            <label id="card_noo"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.card_exp_date') }}</span>
                            <label id="card_exp_d"></label>
                        </li>

                        <li>
                            <span>{{ trans('front/MyChannel.card_cvv') }}</span>
                            <label id="cvv_cvv2"></label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="buy_now">
            <a href="{!! URL::to('/my_channel/create') !!}">{{ trans('front/MyChannel.cancel') }}</a>
        </div>    
        <div class="submit">
            {!! Form::submit_new(trans('front/form.send')) !!}
        </div>
    </div>

    {!! Form::close() !!}

</div>

  <!-- Modal -->
<div id="ChannelNameModal" class="modal fade" role="dialog" style="display:none";>
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{!! trans('front/MyChannel.name') !!}</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $channelName[0]->content;?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="ChannelDecriptionModal" class="modal fade" role="dialog" style="display:none";>
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{!! trans('front/MyChannel.description') !!}</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $channelName[0]->content;?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="ChannelShareLink" class="modal fade" role="dialog" style="display:none";>
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{!! trans('front/MyChannel.description') !!}</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $channelName[0]->content;?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script>
    function muFunctionPlan(plan_id, plan_name, plan_price, duration, id) {
        $('#plan_name').html(plan_name);
        $('#plan_price').html(plan_price);
        $('#plan_time_period').html(duration);

        $('#plan_idd').val(plan_id);
        $('#plan_pricee').val(plan_price);

        muFunction(id);
    }

    function muFunctionBotForm(id) {
        var bot_id = $('#botID').val();
		var element = $("option:selected", $('#botID'));
		var bot_name = element.attr('data-rel');

		var name = $('#name').val();
        var channel_description = $('#description').val();
        var share_link = $('#share_link').val();

        var chk = 1;
        if (bot_id == '') {
            $('#bName .form-group').addClass('has-error');
            chk = 0;
        } else {
            $('#bName .form-group').removeClass('has-error');
        }
		
		if (name == '') {
            $('#uName .form-group').addClass('has-error');
            chk = 0;
        } else {
            $('#uName .form-group').removeClass('has-error');
        }

        if (channel_description == '') {
            $('#aToken .form-group').addClass('has-error');
            chk = 0;
        } else {
            $('#aToken .form-group').removeClass('has-error');
        }
        if (share_link == '') {
            $('#uShareLink .form-group').addClass('has-error');
            chk = 0;
        } else {
            $('#uShareLink .form-group').removeClass('has-error');
        }

        if (chk == 0) {
            $(window).scrollTop(300);
            return false;
        }
		
		$('#bot_name').html(bot_name);
        $('#chanel_name').html(name);
        $('#channel_description').html(channel_description);
        $('#channel_share_link').html(share_link);

		var check_price = $('#plan_pricee').val();
		
		if(check_price > 0){
			muFunction(id);
		}
		else{
			$('#payment-form-chanel').submit();
		}
    }


    function muFunctionCard(id) {

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
        if (cardType != null || cardType != 'undefined') {
            $('.text-muted').html('[<span class="cc-brand">' + cardType + '</span>]');
        }

        var chk = 1;

        if (street == '') {
            chk = 0;
            $('#stretEr .form-group').addClass('has-error');
        } else {
            $('#stretEr .form-group').removeClass('has-error');
        }

        if (city == '') {
            chk = 0;
            $('#cityEr .form-group').addClass('has-error');
        } else {
            $('#cityEr .form-group').removeClass('has-error');
        }


        if (state == '') {
            chk = 0;
            $('#stateEr .form-group').addClass('has-error');
        } else {
            $('#stateEr .form-group').removeClass('has-error');
        }

        if (postal_code == '') {
            chk = 0;
            $('#zipEr .form-group').addClass('has-error');
        } else {
            $('#zipEr .form-group').removeClass('has-error');
        }

        if (country == '') {
            chk = 0;
            $('#countryEr .form-group').addClass('has-error');
        } else {
            $('#countryEr .form-group').removeClass('has-error');
        }


        if (email == '') {
            chk = 0;
            $('#emailEr .form-group').addClass('has-error');
        } else if (!isValidEmailAddress(email)) {
            chk = 0;
            $('#emailEr .form-group').addClass('has-error');
        } else {
            $('#emailEr .form-group').removeClass('has-error');
        }


        if (card_name == '') {
            chk = 0;
            $('#cardnameEr .form-group').addClass('has-error');
        } else {
            $('#cardnameEr .form-group').removeClass('has-error');
        }



        if ($.payment.validateCardNumber(card_no)) {
            $('#cardEr .form-group').removeClass('has-error');
        } else {
            chk = 0;
            $('#cardEr .form-group').addClass('has-error');
        }


        if ($.payment.validateCardCVC(card_cvv, cardType)) {
            $('#cardCv .form-group').removeClass('has-error');
        } else {
            chk = 0;
            $('#cardCv .form-group').addClass('has-error');
        }

        if ($.payment.validateCardExpiry($('#card_exp').payment('cardExpiryVal'))) {
            $('#cardExp .form-group').removeClass('has-error');
        } else {
            chk = 0;
            $('#cardExp .form-group').addClass('has-error');
        }

        if (chk == 1) {
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

        } else {
            return false;
        }
    }

    function muFunction(id) {
        var last_id = parseInt(id) - 1;
        $('#row' + last_id).css('display', 'none');
        $('#row' + id).css('display', 'block');
    }

    function muFunctionBack(id) {
        var last_id = parseInt(id) - 1;
        $('#row' + last_id).css('display', 'block');
        $('#row' + id).css('display', 'none');
    }



    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;

        return pattern.test(emailAddress);
    }
	
	
	
	function mypopupinfo(id){
        $('#'+id).modal();
    }



</script>

<script>
    $(document).ready(function () {
        $('#country').change(function () {
            var countryId = $('#country').val();
            var token = $('input[name=_token]').val();

            url = '/bot/get_state/' + countryId;
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
</script>


<!-- Entête de page -->
<!--
 <div class="row">
       <div class="col-lg-12">
               <h1 class="page-header">
                       {{ trans('front/bots.create') }}
               </h1>
               
       </div>
</div>

   {!! Form::open(['url' => 'bot', 'method' => 'post', 'class' => 'form-horizontal panel']) !!}	
       <div class="col-sm-12" id="bot_creation_form">
               
                       {!! Form::control('text', 0, 'username', $errors, trans('front/bots.name')) !!}
                       {!! Form::control('text', 0, 'bot_token', $errors, trans('front/bots.bot_token')) !!}
                       {!! Form::control('file', 0, 'bot_image', $errors, trans('front/bots.bot_image')) !!}
                       {!! Form::control('textarea', 0, 'bot_description', $errors, trans('front/bots.bot_description')) !!}
                       {!! Form::control('textarea', 0, 'start_message', $errors, trans('front/bots.start_message')) !!}
                       
                       <h2>{{ trans('front/bots.menus_text') }}</h2>
                       
                       {!! Form::control('text', 0, 'autoresponse', $errors, trans('front/bots.autoresponse')) !!}
                       {!! Form::control('text', 0, 'contact_form', $errors, trans('front/bots.contact_form')) !!}
                       {!! Form::control('text', 0, 'galleries', $errors, trans('front/bots.galleries')) !!}
                       {!! Form::control('text', 0, 'channels', $errors, trans('front/bots.channels')) !!}
                       
                       {!! Form::button(trans('front/form.send')) !!}
       </div>
       
       <div class="col-sm-12" id="bot_plan_form" style="display:none;">
       </div>
       
       
       {!! Form::close() !!}
-->
@stop