@extends('front.template')
@section('main')

<!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->
{!! HTML::script('js/front/paging.js') !!}

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
        	<tr><td></td></tr>
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
        </table>
        <ul id="bots_contentNavPosition" class="pagination"></ul>
        </div>
        
      
        <h5>{{ trans('front/fornt_user.my_channel') }}</h5>
        <h5>{!! link_to_route('my_channel.create', '+', [], ['class' => '']) !!}</h5>
        
        <div class="bots_content">
        <table id="channel_content">
        	<tr><td></td></tr>
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
          </table>
          <ul id="channel_contentNavPosition" class="pagination"></ul>
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
          <ul id="bot_plan_subNavPosition" class="pagination"></ul>
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
          <ul id="bot_billingNavPosition" class="pagination"></ul>
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
          <ul id="channel_plansNavPosition" class="pagination"></ul>
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
          <ul id="channel_billingNavPosition" class="pagination"></ul>
        </div>
        <!----------------------------------------->
        
</div>
      
  </div>
  
  <script type="text/javascript"><!--

  	var pager_bots_content = new Pager('bots_content', 2); 
	pager_bots_content.init(); 
	pager_bots_content.showPageNav('pager_bots_content', 'bots_contentNavPosition'); 
	pager_bots_content.showPage('pager_bots_content',1);
	
	var pager_channel_content = new Pager('channel_content', 2); 
	pager_channel_content.init(); 
	pager_channel_content.showPageNav('pager_channel_content', 'channel_contentNavPosition'); 
	pager_channel_content.showPage('pager_channel_content',1);
	
	var pager_bot_plan_sub = new Pager('bot_plan_sub', 5); 
	pager_bot_plan_sub.init(); 
	pager_bot_plan_sub.showPageNav('pager_bot_plan_sub', 'bot_plan_subNavPosition'); 
	pager_bot_plan_sub.showPage('pager_bot_plan_sub',1);
	
	
	var pager_bot_billing = new Pager('bot_billing', 5); 
	pager_bot_billing.init(); 
	pager_bot_billing.showPageNav('pager_bot_billing', 'bot_billingNavPosition'); 
	pager_bot_billing.showPage('pager_bot_billing',1);
	
	var pager_channel_plans = new Pager('channel_plans', 5); 
	pager_channel_plans.init(); 
	pager_channel_plans.showPageNav('pager_channel_plans', 'channel_plansNavPosition'); 
	pager_channel_plans.showPage('pager_channel_plans',1);
	
	var pager_channel_billing = new Pager('channel_billing', 5); 
	pager_channel_billing.init(); 
	pager_channel_billing.showPageNav('pager_channel_billing', 'channel_billingNavPosition'); 
	pager_channel_billing.showPage('pager_channel_billing',1);
	
    //-->
  </script>
  
@stop