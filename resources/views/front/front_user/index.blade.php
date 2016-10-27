@extends('front.template')
@section('main')

<!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->


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
        
        <div class="bots_content">
        <?php
          if(!empty($data)){
            foreach($data as $d1 => $dv1){
              ?>
                <h6><a href="{!! URL::to('/bot/detail/'.$dv1['bot']['id']) !!}"><?php echo $dv1['bot']['username'];?></a></h6>
                <ul>
                  <li>
                    <p><?php echo $dv1['user_subscription']['Plan']['name'];?></p>
                    <!--<a href="{!! URL::to('/bot/destroy/'.$dv1['bot']['id']) !!}" onclick="return confirm('Are you sure want to delete this bot?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>-->
                    <a href="#" onclick="return confirm('Are you sure want to delete this bot?');"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                  </li>

                  <li><p>{{ trans('front/fornt_user.automatic_renewal') }}:<?php echo date('d/m/Y',strtotime($dv1['user_subscription']['expiry_date']));?></p></li>
                </ul>
              <?php
            }
          }
          else{
            ?>
          <ul>
            <li>{{ trans('front/fornt_user.no_record_found') }}</li>
          </ul>
            <?php
          }
        ?>
        </div>
        
      
        <h5>{{ trans('front/fornt_user.my_channel') }}</h5>
        <h5>{!! link_to_route('my_channel.create', '+', [], ['class' => '']) !!}</h5>
        
        <div class="bots_content">
          <?php
            if(!empty($chanel_data)){
              foreach($chanel_data as $ck1 => $cv1){
                ?>
                  <h6><?php echo $cv1['channel']['name'];?></h6>
                  <ul>
                    <li>
                      <p></p>
                      <a href="#"><span aria-hidden="true" class="glyphicon glyphicon-trash"></span></a>
                    </li>
                    <li><p>{{ trans('front/fornt_user.automatic_renewal') }}:<?php echo date('d/m/Y',strtotime($cv1['user_subscription']['expiry_date']));?></p></li>
                </ul>
                <?php
              }
            }
          else{
            ?>
          <ul>
            <li>{{ trans('front/fornt_user.no_record_found') }}</li>
          </ul>
            <?php
          }
          ?>
        </div>
        
        </div>
      </div>
      
      <div class="col-lg-5">
        <div class="col-plan">
          <h2>{{ trans('front/fornt_user.plan_subscription') }}</h2>
          <table>
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
                    ?>
                        <tr>
                          <td><?php echo $v2['bot']['username'];?></td>
                          <td><?php echo $v2['user_subscription']['Plan']['name'];?></td>
                          <td><?php echo '€'.$v2['user_subscription']['price'];?></td>
                          <td><?php echo $v2['user_subscription']['status'];?></td>
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
        </div>
        
        <div class="col-plan">
          <h2>{{ trans('front/fornt_user.billing_transactions') }}</h2>
          <table>
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
        </div>
        
        <!------------ Channel ------------------->
         <div class="col-plan">
          <h2>{{ trans('front/fornt_user.chanel_plan_subscription') }}</h2>
          <table>
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
        </div>
        
        <div class="col-plan">
          <h2>{{ trans('front/fornt_user.chanel_billing_transactions') }}</h2>
          <table>
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
        </div>
        <!----------------------------------------->
        
</div>
      
  </div>
@stop