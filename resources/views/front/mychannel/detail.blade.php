@extends('front.template')
@section('main')

<!-- http://jlinn.github.io/stripe-api-php/api/subscriptions.html -->
{!! HTML::script('js/front/paging.js') !!}

<div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3">

    @include('front.top')

    <div class="my_account">
        <h4 class="left">{!! $chanels[0]->name !!}</h4>
        <a href="{!! URL::to('/my_channel/update_channel/'.$chanels[0]->id) !!}" class="btn btn-primary right">{!! trans('front/dashboard.edit_channel') !!}</a>
        <div class="clear"></div>
    </div>

    <div class="buying">
        <div class="create_bot">
            <div class="crete_bot_form">
                <ul>
                    <li>
                        <span>{{ trans('front/MyChannel.name') }}</span>
                        <label id="chanel_name">{!! $chanels[0]->name !!}</label>
                    </li>



                    <li>
                        <span>{{ trans('front/MyChannel.description') }}</span>
                        <label id="channel_description">{!! $chanels[0]->description !!}</label>
                    </li>

                    <li>
                        <span>{{ trans('front/MyChannel.share_link') }}</span>
                        <label id="channel_share_link">{!! $chanels[0]->share_link !!}</label>
                    </li>
					
                    <li>
                    	<a href="javascript:void(0);" class="btn btn-primary" onclick="mypopupfunction('<?php echo $chanels[0]->id;?>');">{{ trans('front/dashboard.send_message') }}</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12">
         <div class="col-plan">
          <h2>{{ trans('front/MyChannel.messages_activity') }}</h2>
          <table id="channelMessages">
            <thead>
              <tr>
                <th>{{ trans('front/MyChannel.channel_name') }}</th>
                <th>{{ trans('front/MyChannel.send_message') }} </th>
                <th>{{ trans('front/MyChannel.send_date') }} </th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(!empty($chanelMesg)){
                  foreach($chanelMesg as $d2 => $v2){
                    ?>
                        <tr>
                          <td><?php echo $v2->channel_name;?></td>
                          <td><?php echo $v2->message;?></td>
                          <td><?php echo $v2->send_date;?></td>
                        </tr>
                    <?php
                  }
                }
                else{
                  ?>
                    <tr>
                      <td colspan="5">{{ trans('front/MyChannel.no_record') }}</td>
                    </tr>
                  <?php
                }
              ?>
            </tbody>
          </table>
          <ul id="channelMessagesNavPosition" class="pagination"></ul>
        </div>
    </div>


</div>

<div id="myModal" class="modal fade" role="dialog" style="display:none;">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('front/dashboard.send_a_message') }}</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'dashboard', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => '','id' =>'']) !!}

                    <input type="hidden" id="chat_id" />

                    <select id="botID" class="form-control">
                        <option value="">Select bot</option>
                        <?php
                        if (isset($bots) && !empty($bots)) {
                        foreach ($bots as $b1 => $bv1) {
                        ?>
                        <option value="<?php echo $bv1->id; ?>"><?php echo $bv1->username;?></option>
                        <?php
                        }
                        }
                        ?>
                    </select>

                    <br>

                    <textarea id="channel_msg" class="form-control" cols="20" rows="5" placeholder="{{ trans('front/dashboard.enter_message') }}"></textarea>

                    <br>

                    <a href="javascript:void(0);" class="btn btn-primary" onclick="sendMsg();">{{ trans('front/dashboard.send') }}</a>

                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

<script type="text/javascript"><!--
  	var pager_channelMessages = new Pager('channelMessages', 4);
	pager_channelMessages.init(); 
	pager_channelMessages.showPageNav('pager_channelMessages', 'channelMessagesNavPosition'); 
	pager_channelMessages.showPage('pager_channelMessages',1);
    //-->
	
	
	 function mypopupfunction(channel_id){
		$('#botID').css('border','1px solid #ccc');
		$('#channel_msg').css('border','1px solid #ccc');
		$('#chat_id').val(channel_id);

		$('#botID').val('');
		$('#channel_msg').val('');

		$('#myModal').modal();
	}
	
	
	function sendMsg(){
            var chk = true;
            var botID = $('#botID').val();
            var channel_msg = $('#channel_msg').val();
            var channel_id = $('#chat_id').val();

            if(botID == ''){
                chk = false;
                $('#botID').css('border','1px solid #ff0000');
            }
            else{
                $('#botID').css('border','1px solid #ccc');
            }

            if(channel_msg == ''){
                chk = false;
                $('#channel_msg').css('border','1px solid #ff0000');
            }
            else{
                $('#channel_msg').css('border','1px solid #ccc');
            }


            if(chk){
                var token_new = $('input[name=_token]').val();
                $.ajax({
                    url: '<?php echo URL::to('/dashboard/sendmessage')?>',
                    headers: {'X-CSRF-TOKEN': token_new},
                    data: {bot_id: botID, channel_msg:channel_msg,channel_id:channel_id},
                    type:'POST',
                    success: function (resp) {
                        alert(resp);
                        $('#myModal').modal('hide');
                    },
                    error: function (request, status, error) {
                        alert('Forbidden: bot is not a member of the channel chat');
                    }
                });
            }
        }
  </script>

<style>
    .thumb {
        width: 20%;
    }
</style>
@stop