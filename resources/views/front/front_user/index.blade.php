@extends('front.template')
@section('main')

{!! HTML::style('css/front/simplePagination.css') !!}
{!! HTML::script('js/front/jquery.simplePagination.js') !!}

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      <div class="my_account">
        <h4>{!! trans('front/fornt_user.my_account') !!}</h4>
        <div class="modify_icon">
          {!! link_to_route_img('front_user.edit', "<span>".trans('front/fornt_user.modify_account')."</span>".HTML::image('img/front/modify_icon.png'), [Auth::user()->id], ['class' => '']) !!}
        </div>
      </div>
      
      <div class="col-lg-7"><div class="col-my-bots">
        <h5>{{ trans('front/fornt_user.my_bots') }}</h5>
        <?php
        	//echo '<pre>';print_r($data);die;
		?>
        <div class="bots_content">
        	<table id="bots_content">
            <thead
        		<tr><td></td></tr>
            </thead>
            <tbody>
        <?php
          if(!empty($data)){
            foreach($data as $d1 => $dv1){
				$planName = (isset($dv1['user_subscription']['plan_name']) && !empty($dv1['user_subscription']['plan_name']))?$dv1['user_subscription']['plan_name']:'';
				
				$expDate = (isset($dv1['user_subscription']['expiry_date']) && !empty($dv1['user_subscription']['expiry_date']))?$dv1['user_subscription']['expiry_date']:'';
				
              ?>
               <tr>
              <td>
                <h6><a href="{!! URL::to('/bot/detail/'.$dv1['bot']['id']) !!}"><?php echo $dv1['bot']['username'];?></a></h6>
                <ul>
                  <li>
                    <p><?php echo $planName; ?></p>
                    <a href="{!! URL::to('/bot/bot_delete/'.$dv1['bot']['id']) !!}" onclick="return confirm('Are you sure want to delete this bot?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                    
                    <a href="{!! URL::to('/bot/bot_subscription_cancel/'.$dv1['bot']['id']) !!}" onclick="return confirm('Are you sure want to cancel the subscription');">{{ trans('front/fornt_user.subscription_cancel') }}</a>
                    
                    <!--<a href="#" onclick="return confirm('Are you sure want to delete this bot?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>-->
                  </li>

                  <li><p>{{ trans('front/fornt_user.automatic_renewal') }}:<?php if(!empty($expDate)){echo date('d/m/Y',strtotime($expDate));}?></p></li>
                </ul>
                </td>
                </tr>
              <?php
            }
          }
          else{
            ?>
             <tr>
              <td>
          <ul>
            <li>{{ trans('front/fornt_user.no_record_found') }}</li>
          </ul>
          </td>
                </tr>
            <?php
          }
        ?>
        </tbody>
        </table>
        <div id="bots_contentNavPosition"></div>
        </div>
        
      
        <h5>{{ trans('front/fornt_user.my_channel') }}</h5>
        <h5>{!! link_to_route('my_channel.create', '+', [], ['class' => '']) !!}</h5>
        
        <div class="bots_content">
        <table id="channel_content">
        	<thead><tr><td></td></tr></thead>
            <tbody>
          <?php
            if(!empty($chanel_data)){
              foreach($chanel_data as $ck1 => $cv1){
                ?>
                <tr>
                    <td>
                  <h6><?php echo $cv1['channel']['name'];?></h6>
                  <ul>
                    <li>
                      <p></p>
                      <a href="{!! URL::to('/my_channel/channel_delete/'.$cv1['channel']['id']) !!}" onclick="return confirm('Are you sure want to delete this channel?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                      
                      <a href="{!! URL::to('/my_channel/channel_subscription_cancel/'.$cv1['channel']['id']) !!}" onclick="return confirm('Are you sure want to cancel the subscription');">{{ trans('front/fornt_user.subscription_cancel') }}</a>
                    </li>
                    <li><p>{{ trans('front/fornt_user.automatic_renewal') }}:<?php echo date('d/m/Y',strtotime($cv1['user_subscription']['expiry_date']));?></p></li>
                </ul>
                </td>
                </tr>
                <?php
              }
            }
          else{
            ?>
            <tr>
            <td>
          <ul>
            <li>{{ trans('front/fornt_user.no_record_found') }}</li>
          </ul>
          </td>
                </tr>
            <?php
          }
          ?>
          </tbody>
          </table>
          <div id="channel_contentNavPosition"></div>
        </div>
        
        </div>
      </div>
      
      <div class="col-lg-5">
        <div class="col-plan">
          <h2>{{ trans('front/fornt_user.plan_subscription') }}</h2>
          <table id="bot_plan_sub">
            <thead>
              <tr>
                <th>{{ trans('front/fornt_user.bots') }}</th>
                <th>{{ trans('front/fornt_user.plan') }} </th>
                <th>{{ trans('front/fornt_user.cost') }} </th>
                <th>{{ trans('front/fornt_user.status') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($data)){
                  foreach($data as $d2 => $v2){
					  $price = (isset($v2['user_subscription']['price']) && !empty($v2['user_subscription']['price']))?$v2['user_subscription']['price']:'';
					  
					  $pname = (isset($v2['user_subscription']['plan_name']) && !empty($v2['user_subscription']['plan_name']))?$v2['user_subscription']['plan_name']:'';
					  
					  $status = (isset($v2['user_subscription']['status']) && !empty($v2['user_subscription']['status']))?$v2['user_subscription']['status']:'';
                    ?>
                        <tr>
                          <td><?php echo $v2['bot']['username'];?></td>
                          <td><?php echo $pname;?></td>
                          <td><?php if(!empty($price)){echo '€'.$price;}?></td>
                          <td><?php echo $status;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="4">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
          <div id="bot_plan_subNavPosition"></div>
        </div>
        
        <div class="col-plan">
          <h2>{{ trans('front/fornt_user.billing_transactions') }}</h2>
          <table id="bot_billing">
            <thead>
              <tr>
                <th>{{ trans('front/fornt_user.transaction_date') }}</th>
                <th>{{ trans('front/fornt_user.description') }} </th>
                <th>{{ trans('front/fornt_user.type') }} </th>
                <th>{{ trans('front/fornt_user.amount') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($transactions)){
                  foreach($transactions as $t1 => $t2){
                    ?>
                        <tr>
                          <td><?php echo $t2->created_at;?></td>
                          <td><?php echo $t2->Description;?></td>
                          <td><?php echo $t2->types;?></td>
                          <td><?php echo '€'.$t2->amount;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="4">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
          <div id="bot_billingNavPosition"></div>
        </div>
        
        <!------------ Channel ------------------->
         <div class="col-plan">
          <h2>{{ trans('front/fornt_user.chanel_plan_subscription') }}</h2>
          <table id="channel_plans">
            <thead>
              <tr>
                <th>{{ trans('front/fornt_user.channels') }}</th>
                <th>{{ trans('front/fornt_user.plan') }} </th>
                <th>{{ trans('front/fornt_user.cost') }} </th>
                <th>{{ trans('front/fornt_user.status') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($chanel_data)){
                  foreach($chanel_data as $c2 => $cv2){
                    ?>
                        <tr>
                          <td><?php echo $cv2['channel']['name'];?></td>
                          <td><?php echo $cv2['user_subscription']['Plan']['name'];?></td>
                          <td><?php echo '€'.$cv2['user_subscription']['price'];?></td>
                          <td><?php echo $cv2['user_subscription']['status'];?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="4">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
          <div id="channel_plansNavPosition"></div>
        </div>
        
        <div class="col-plan">
          <h2>{{ trans('front/fornt_user.chanel_billing_transactions') }}</h2>
          <table id="channel_billing">
            <thead>
              <tr>
                <th>{{ trans('front/fornt_user.transaction_date') }}</th>
                <th>{{ trans('front/fornt_user.description') }} </th>
                <th>{{ trans('front/fornt_user.type') }} </th>
                <th>{{ trans('front/fornt_user.amount') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($chanel_transactions)){
                  foreach($chanel_transactions as $ct1 => $ct2){
                    ?>
                        <tr>
                          <td><?php echo $ct2->created_at;?></td>
                          <td><?php echo $ct2->Description;?></td>
                          <td><?php echo $ct2->types;?></td>
                          <td><?php echo '€'.$ct2->amount;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="4">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
          <div id="channel_billingNavPosition"></div>
        </div>
        <!----------------------------------------->
        
</div>
      
  </div>
  
  
  <script>
  	jQuery(function($) {
		var pageParts = $("#bots_content tbody tr");
		var numPages = pageParts.length;
		var perPage = 2;
		pageParts.slice(perPage).hide();
		
		$("#bots_contentNavPosition").pagination({
			items: numPages,
			itemsOnPage: perPage,
			cssStyle: "light-theme",
			onPageClick: function(pageNum) {
				var start = perPage * (pageNum - 1);
				var end = start + perPage;
				pageParts.hide().slice(start, end).show();
			}
		});
	});
	
	jQuery(function($) {
		var pageParts = $("#channel_content tbody tr");
		var numPages = pageParts.length;
		var perPage = 2;
		pageParts.slice(perPage).hide();
		
		$("#channel_contentNavPosition").pagination({
			items: numPages,
			itemsOnPage: perPage,
			cssStyle: "light-theme",
			onPageClick: function(pageNum) {
				var start = perPage * (pageNum - 1);
				var end = start + perPage;
				pageParts.hide().slice(start, end).show();
			}
		});
	});
	
	
	jQuery(function($) {
		var pageParts = $("#bot_plan_sub tbody tr");
		var numPages = pageParts.length;
		var perPage = 2;
		pageParts.slice(perPage).hide();
		
		$("#bot_plan_subNavPosition").pagination({
			items: numPages,
			itemsOnPage: perPage,
			cssStyle: "light-theme",
			onPageClick: function(pageNum) {
				var start = perPage * (pageNum - 1);
				var end = start + perPage;
				pageParts.hide().slice(start, end).show();
			}
		});
	});
	
	jQuery(function($) {
		var pageParts = $("#bot_billing tbody tr");
		var numPages = pageParts.length;
		var perPage = 2;
		pageParts.slice(perPage).hide();
		
		$("#bot_billingNavPosition").pagination({
			items: numPages,
			itemsOnPage: perPage,
			cssStyle: "light-theme",
			onPageClick: function(pageNum) {
				var start = perPage * (pageNum - 1);
				var end = start + perPage;
				pageParts.hide().slice(start, end).show();
			}
		});
	});
	
	jQuery(function($) {
		var pageParts = $("#channel_plans tbody tr");
		var numPages = pageParts.length;
		var perPage = 2;
		pageParts.slice(perPage).hide();
		
		$("#channel_plansNavPosition").pagination({
			items: numPages,
			itemsOnPage: perPage,
			cssStyle: "light-theme",
			onPageClick: function(pageNum) {
				var start = perPage * (pageNum - 1);
				var end = start + perPage;
				pageParts.hide().slice(start, end).show();
			}
		});
	});
	
	
	jQuery(function($) {
		var pageParts = $("#channel_billing tbody tr");
		var numPages = pageParts.length;
		var perPage = 2;
		pageParts.slice(perPage).hide();
		
		$("#channel_billingNavPosition").pagination({
			items: numPages,
			itemsOnPage: perPage,
			cssStyle: "light-theme",
			onPageClick: function(pageNum) {
				var start = perPage * (pageNum - 1);
				var end = start + perPage;
				pageParts.hide().slice(start, end).show();
			}
		});
	});

	</script>
  
@stop