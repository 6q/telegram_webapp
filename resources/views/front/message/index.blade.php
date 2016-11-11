@extends('front.template')
@section('main')

{!! HTML::script('js/front/paging.js') !!}

    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3 col-message">
     
      @include('front.top')  
      
      <div class="my_account">
        <h4>{{ trans('front/message.message') }}</h4>
      </div>

        <ul class="nav nav-tabs nav-pills messages_bots" role="tablist">
            <?php
            if(isset($bots) && !empty($bots)){
                $i = 0;
                foreach($bots as $k1 => $v1){
                    ++$i;
            ?>
                    <li class=" <?php if ($i==1) echo 'active'?>"><a data-toggle="tab" href="#<?php echo $v1->username;?>"><?php echo $v1->username;?></a></li>
                <?php
                }
            }
            ?>
        </ul>


      <div class="col-lg-12 tab-content">
        <?php
          if(isset($bots) && !empty($bots)){
          $i = 0;
          foreach($bots as $k1 => $v1){
          ++$i;
             ?>
                <div class="col-plan tab-pane fade <?php if ($i==1) echo 'in active'?>" id="<?php echo $v1->username;?>">
                  <h2><?php echo $v1->username;?></h2>
                  <table id="message_tbl_<?php echo $i;?>">
                      <thead>
                        <tr>
                          <th>{{ trans('front/message.bot_user') }}</th>
                          <th>{{ trans('front/message.created') }} </th>
                          <th>{{ trans('front/message.text') }}</th>
                          <th>{{ trans('front/message.reply') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        if(!empty($v1->message)){
                          foreach($v1->message as $mk1 => $mv1){
                            ?>
                                <tr>
                                  <td><?php echo $mv1->bot_user;?></td>
                                  <td><?php echo $mv1->forward_date;?></td>
                                  <td><?php echo $mv1->text;?></td>
                                  <td>
                                    <?php 
                        if(file_exists(public_path().'/uploads/'.$mv1->reply_message) && !empty($mv1->reply_message)){
                            ?>
                            {!! HTML::image('uploads/'.$mv1->reply_message) !!}
                            <?php
                        }
                        else{
                            echo $mv1->reply_message;
                        }
                        ?>
                                  </td>
                                </tr>
                            <?php
                          }
                        }
                        else{
                          ?>
                            <tr>
                              <td colspan="4">{{ trans('front/message.no_record') }}</td>
                            </tr>
                          <?php
                        }
                      ?>
                    </tbody>
                  </table>
                  <ul id="MessgNavPosition_<?php echo $i;?>" class="pagination"></ul>
                </div>
                <script type="text/javascript"><!--
					var pager_message_<?php echo $i;?> = new Pager('message_tbl_<?php echo $i;?>', 4);
					pager_message_<?php echo $i;?>.init(); 
					pager_message_<?php echo $i;?>.showPageNav('pager_message_<?php echo $i;?>', 'MessgNavPosition_<?php echo $i;?>'); 
					pager_message_<?php echo $i;?>.showPage('pager_message_<?php echo $i;?>',1);
				//-->
				</script>

              <?php
            }
          }
        ?>
</div>
      
  </div>
  
  
  
<style>
  .thumb {
    width: 20%;
  }
</style>
@stop