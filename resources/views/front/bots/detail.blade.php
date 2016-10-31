@extends('front.template')
@section('main')

<!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">
     
      @include('front.top')  
      
      <div class="my_account col-user">
        <ul>
        <li><p>{{ trans('front/bots.name') }}</p> : <h4>{!! $bots[0]->username !!}</h4></li>
        <li><p>{{ trans('front/bots.bot_token') }}</p> : <h4>{!! $bots[0]->bot_token !!}</h4></li>
        <li>
        <?php
          if(isset($bots[0]->bot_image) && !empty($bots[0]->bot_image)){
          ?>
              <p>{{ trans('front/bots.image') }}</p> : {!! HTML::image('uploads/'.$bots[0]->bot_image) !!}
          <?php
          }
        ?></li>
           </ul>
        <a href="{!! URL::to('/bot/update_bot/'.$bots[0]->id) !!}" class="btn btn-primary">{!! trans('front/dashboard.edit_bot') !!}</a> <a href="{!! URL::to('/command/create/'.$bots[0]->id) !!}" class="btn btn-primary">{!! trans('front/dashboard.create_command') !!}</a>
      </div>


      
    
      
      <div class="col-lg-12">
        <div class="col-plan col-lg-6">
          <h2>{{ trans('front/bots.autoresponse') }}</h2>
          <table>
            <thead>
              <tr>
                <th>{{ trans('front/bots.submenu_heading_text') }}</th>
                <th>{{ trans('front/bots.action') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($autoResponse)){
                  foreach($autoResponse as $d2 => $v2){
                    ?>
                        <tr>
                          <td><?php echo $v2->submenu_heading_text;?></td>
                          <td>
                            <a class="btn btn-primary" href="{!! URL::to('/command/autoresponse_edit/'.$v2->id) !!}">{{ trans('front/bots.update_command') }}</a>
                          </td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        
        <div class="col-plan col-lg-6">
          <h2>{{ trans('front/bots.contact_form') }}</h2>
          <table>
            <thead>
              <tr>
                <th>{{ trans('front/bots.submenu_heading_text') }}</th>
                <th>{{ trans('front/bots.action') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($contactForm)){
                  foreach($contactForm as $d3 => $v3){
                    ?>
                        <tr>
                          <td><?php echo $v3->submenu_heading_text;?></td>
                          <td><a class="btn btn-primary" href="{!! URL::to('/command/contactform_edit/'.$v3->id) !!}">{{ trans('front/bots.update_command') }}</a></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        <div style="clear:both"></div>
        <div class="col-plan col-lg-6">
          <h2>{{ trans('front/bots.galleries') }}</h2>
          <table>
            <thead>
              <tr>
                <th>{{ trans('front/bots.submenu_heading_text') }}</th>
                <th>{{ trans('front/bots.action') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($gallery)){
                  foreach($gallery as $d4 => $v4){
                    ?>
                        <tr>
                          <td><?php echo $v4->gallery_submenu_heading_text;?></td>
                          <td><a class="btn btn-primary" href="{!! URL::to('/command/gallery_edit/'.$v4->id) !!}">{{ trans('front/bots.update_command') }}</a></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        
        <div class="col-plan col-lg-6">
          <h2>{{ trans('front/bots.channels') }}</h2>
          <table>
            <thead>
              <tr>
                <th>{{ trans('front/bots.submenu_heading_text') }}</th>
                <th>{{ trans('front/bots.action') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($chanels)){
                  foreach($chanels as $d5 => $v5){
                    ?>
                        <tr>
                          <td><?php echo $v5->chanel_submenu_heading_text;?></td>
                          <td><a class="btn btn-primary" href="{!! URL::to('/command/chanel_edit/'.$v5->id) !!}">{{ trans('front/bots.update_command') }}</a></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        <div style="clear:both"></div>
        
        <div class="col-plan">
          <h2>{{ trans('front/bots.active_user') }}</h2>
          <table>
            <thead>
              <tr>
                <th>{{ trans('front/bots.first_name') }}</th>
                <th>{{ trans('front/bots.last_name') }} </th>
                <th>{{ trans('front/bots.created_at') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($activeUser)){
                  foreach($activeUser as $auk1 => $auv1){
                    ?>
                        <tr>
                          <td><?php echo $auv1->first_name;?></td>
                          <td><?php echo $auv1->last_name;?></td>
                          <td><?php echo $auv1->created_at;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        
        <div class="col-plan">
          <h2>{{ trans('front/bots.messages_activity') }}</h2>
          <table>
            <thead>
              <tr>
                <th>{{ trans('front/bots.user') }}</th>
                <th>{{ trans('front/bots.message') }} </th>
                <th>{{ trans('front/bots.replay_message') }}</th>
                <th>{{ trans('front/bots.date') }}</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($botMessages)){
                  foreach($botMessages as $bmk1 => $bmv1){
                    ?>
                        <tr>
                          <td><?php echo $bmv1->first_name.' '.$bmv1->last_name;?></td>
                          <td><?php echo $bmv1->text;?></td>
                          <td><?php echo $bmv1->reply_message;?></td>
                          <td><?php echo $bmv1->date;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/fornt_user.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
        </div>
        
      
</div>
      
  </div>

<style>
  .thumb {
    width: 20%;
  }
</style>
@stop