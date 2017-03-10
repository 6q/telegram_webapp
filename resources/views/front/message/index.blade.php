@extends('front.template')
@section('main')

    {!! HTML::style('css/front/simplePagination.css') !!}
    {!! HTML::script('js/front/jquery.simplePagination.js') !!}

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

        {!! Form::open(['url' => 'messages', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'']) !!}
        {!! Form::close() !!}
        <div class="col-lg-12 tab-content">
			<?php
			if(isset($bots) && !empty($bots)){
			$i = 0;
			foreach($bots as $k1 => $v1){
			++$i;
			?>
            <div class="col-plan tab-pane fade <?php if ($i==1) echo 'in active'?>" id="<?php echo $v1->username;?>">
                <h2><?php echo $v1->username;?></h2>
                <div id="a_resp_<?php echo $v1->username;?>">
                    <table id="message_tbl_<?php echo $i;?>">
                        <thead>
                        <tr>
                            <th>{{ trans('front/message.bot_user') }}</th>
                            <th>{{ trans('front/message.created') }} </th>
                            <th>{{ trans('front/message.text') }}</th>
                            <th class="mobile_hide">{{ trans('front/message.reply') }}</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
						if(!empty($v1->message)){
						foreach($v1->message as $mk1 => $mv1){
						?>
                        <tr>
                            <td><?php echo $mv1->bot_user;?></td>
                            <td>
								<?php
								$date = new DateTime($mv1->forward_date);
								echo $date->format('d-m-Y')."<br>".$date->format('H:i:s');
								?>
                            </td>
                            <td><?php
	                            echo strlen($mv1->text) > 100 ? substr($mv1->text,0,100)."..." : $mv1->text;
                            ?></td>
                            <td class="mobile_hide">
								<?php
								if(strlen($mv1->reply_message) < 1000 && file_exists(public_path().'/uploads/'.$mv1->reply_message) && !empty($mv1->reply_message)){
								?>
                                {!! HTML::image('uploads/'.$mv1->reply_message) !!}
								<?php
								}
								else{
									echo strlen($mv1->reply_message) > 100 ? substr($mv1->reply_message,0,100)."..." : $mv1->reply_message;
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
                    <div id="MessgNavPosition_<?php echo $i;?>" class="light-theme simple-pagination">
						<?php
						$lastpage_tb = 0;
						if($total_msg[$k1] > 0)
						{
							$prev_tb = $page - 1;
							$next_tb = $page + 1;
							$lastpage_tb = ceil($total_msg[$k1]/$limit);
							$lpm1_tb = $lastpage_tb - 1;
						}

						$pagination_tb = '';
						if($lastpage_tb >= 1)
						{
							$pagination_tb = '<ul>';

							if ($page > 1)
								$pagination_tb.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePagination_TB('."'0','first','".$v1->id."', '".$v1->username."'".')")">&lt;</a>';
							else
								$pagination_tb.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


							if ($lastpage_tb < 7 + ($adjacents * 2))
							{
								for ($counter_tb = 1; $counter_tb <= $lastpage_tb; $counter_tb++)
								{
									if ($counter_tb == $page)
										$pagination_tb.= '<li class="active"><span class="current">'.$counter_tb.'</span></li>';
									else
										$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$v1->id."', '".$v1->username."'".')">'.$counter_tb.'</a></li>';
								}
							}
							elseif($lastpage_tb > 5 + ($adjacents * 2))
							{
								if($page < 1 + ($adjacents * 2))
								{
									for ($counter_tb = 1; $counter_tb < 4 + ($adjacents * 2); $counter_tb++)
									{
										if ($counter_tb == $page)
											$pagination_tb.= '<li class="active"><span class="current">'.$counter_tb.'</span>';
										else
											$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$v1->id."', '".$v1->username."'".')">'.$counter_tb.'</a></li>';
									}
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lpm1_tb."','".$lpm1_tb."_no', '".$v1->id."', '".$v1->username."'".')">'.$lpm1_tb.'</a></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lastpage_tb."','".$lastpage_tb."_no', '".$v1->id."', '".$v1->username."'".')">'.$lastpage_tb.'</a></li>';
								}
								elseif($lastpage_tb - ($adjacents * 2) > $page && $page > ($adjacents * 2))
								{
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'1','1_no', '".$v1->id."', '".$v1->username."'".')">1</a></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'2','2_no', '".$v1->id."', '".$v1->username."'".')">2</a></li>';
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									for ($counter_tb = $page - $adjacents; $counter_tb <= $page + $adjacents; $counter_tb++)
									{
										if ($counter_tb == $page)
											$pagination_tb.= '<li class="active"><span class="current">'.$counter_tb.'</span>';
										else
											$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$v1->id."', '".$v1->username."'".')">'.$counter_tb.'</a></li>';
									}
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lpm1_tb."','".$lpm1_tb."_no','".$v1->id."', '".$v1->username."'".')">'.$lpm1_tb.'</a></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lastpage_tb."','".$lastpage_tb."_no', '".$v1->id."', '".$v1->username."'".')">'.$lastpage_tb.'</a></li>';
								}
								else
								{
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'1','1_no','".$v1->id."', '".$v1->username."'".')">1</a></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'2','2_no', '".$v1->id."', '".$v1->username."'".')">2</a></li>';
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									for ($counter_tb = $lastpage_tb - (2 + ($adjacents * 2)); $counter_tb <= $lastpage_tb; $counter_tb++)
									{
										if ($counter_tb == $page)
											$pagination_tb.= '<li class="active"><span class="current">'.$counter_tb.'</span>';
										else
											$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$v1->id."', '".$v1->username."'".')">'.$counter_tb.'</a></li>';
									}
								}
							}

							if ($page < $counter_tb - 1)
							{
								$pagination_tb.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePagination_TB('."'".$next_tb."','".$next_tb."_no', '".$v1->id."', '".$v1->username."'".')">&gt;</a></li>';
							}
							else
							{
								$pagination_tb.= '<li class="active"><span class="current next">&gt;</span>';
							}

							$pagination_tb .= '</ul>';
						}

						echo $pagination_tb;
						?>
                        <img id="imgLoadAjax_CH_<?php echo $v1->username;?>" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                /*jQuery(function($) {
                 var pageParts = $("#message_tbl_<?php echo $i;?> tbody tr");
                 var numPages = pageParts.length;
                 var perPage = 20;
                 pageParts.slice(perPage).hide();

                 $("#MessgNavPosition_<?php echo $i;?>").pagination({
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
                 */
            </script>
			<?php
			}
			}
			?>
        </div>

    </div>

    <script>
		function changePagination_TB(pageId,liId,bot_id,div_id)
		{
			$('#imgLoadAjax_CH_'+div_id).css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/messages/get_messages')?>/'+bot_id,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,bot_id:bot_id},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjax_CH_'+div_id).css('display','none');
					$('#a_resp_'+div_id).html(resp);
				}
			});
		}
    </script>



    <style>
        .thumb {
            width: 20%;
        }
    </style>
@stop