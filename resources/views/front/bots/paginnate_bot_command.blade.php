<table id="botChannels">
	<thead>
		<tr>
    		<th>{!! trans('front/bots.bot_command') !!}</th>
    		<th>{!! trans('front/bots.command_description') !!}</th>
            <th>{!! trans('front/bots.image') !!}</th>
            <th>{{ trans('front/bots.action') }}</th>
		</tr>
	</thead>
	
    <tbody>
		<?php
        	if(!empty($botCommands))
			{
				foreach($botCommands as $d6 => $v6)
				{
		?>
					<tr>
    					<td><?php echo $v6->title;?></td>
    					<td><?php echo $v6->command_description;?></td>
                        <td>
							<?php
                                if(isset($v6->image) && !empty($v6->image)){
                                    ?>
                                        {!! HTML::image('uploads/'.$v6->image,'',array( 'width' => 70, 'height' => 70 )) !!}
                                    <?php
                                }
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-warning" href="{!! URL::to('/bot/bot_command_edit/'.$v6->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a class="btn btn-danger" href="{!! URL::to('/bot/bot_command_delete/'.$v6->bot_id.'/'.$v6->id) !!}" onclick="return confirm('Are you sure want to delete this command?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
					</tr>
				<?php
				}
			}
			else{
		?>
					<tr>
    					<td colspan="5">{{ trans('front/bots.no_record_command') }}</td>
					</tr>
		<?php
				}
		?>
		<tr>
			<td colspan="5" class="paginacio">
				<div class="botonou">
					<a href="{!! URL::to('bot/bot_command/'.$bots[0]->id) !!}"  class="btn btn-success <?php if(isset($planDetails[0]->bot_commands) && !empty($planDetails[0]->bot_commands) && $total_bot_commands == $planDetails[0]->bot_commands) echo "disabled"; ?>">{!! trans('front/bots.bot_add_command') !!}</a>
				</div>


                <?php if(isset($planDetails[0]->bot_commands) && !empty($planDetails[0]->bot_commands) && $planDetails[0]->bot_commands<999){
                    echo '<div class="info_test"> '.$total_bot_commands.' / '.$planDetails[0]->bot_commands.' </div>';
                } ?>

				<div id="botCommandNavPosition" class="light-theme simple-pagination">
                    <?php
                    $page = $current_page;
                    if($total_bot_commands > 0)
                    {
                        $prev_bc = $page - 1;
                        $next_bc = $page + 1;
                        $lastpage_bc = ceil($total_bot_commands/$limit);
                        $lpm1_bc = $lastpage_bc - 1;
                    }

                    $pagination_bc = '';
                    if($lastpage_bc >= 1)
                    {
                        $pagination_bc = '<ul>';

                        if ($page > 1)
                            $pagination_bc.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationBC('."'0','first','".$botid."'".')")">&lt;</a>';
                        else
                            $pagination_bc.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                        if ($lastpage_bc < 7 + ($adjacents * 2))
                        {
                            for ($counter_bc = 1; $counter_bc <= $lastpage_bc; $counter_bc++)
                            {
                                if ($counter_bc == $page)
                                    $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span></li>';
                                else
                                    $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$botid."'".')">'.$counter_bc.'</a></li>';
                            }
                        }
                        elseif($lastpage_bc > 5 + ($adjacents * 2))
                        {
                            if($page < 1 + ($adjacents * 2))
                            {
                                for ($counter_bc = 1; $counter_bc < 4 + ($adjacents * 2); $counter_bc++)
                                {
                                    if ($counter_bc == $page)
                                        $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span>';
                                    else
                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$botid."'".')">'.$counter_bc.'</a></li>';
                                }
                                $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lpm1_bc."','".$lpm1_bc."_no', '".$botid."'".')">'.$lpm1_bc.'</a></li>';
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lastpage_bc."','".$lastpage_bc."_no', '".$botid."'".')">'.$lastpage_bc.'</a></li>';
                            }
                            elseif($lastpage_bc - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                            {
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'1','1_no', '".$botid."'".')">1</a></li>';
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'2','2_no', '".$botid."'".')">2</a></li>';
                                $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                for ($counter_bc = $page - $adjacents; $counter_bc <= $page + $adjacents; $counter_bc++)
                                {
                                    if ($counter_bc == $page)
                                        $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span>';
                                    else
                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$botid."'".')">'.$counter_bc.'</a></li>';
                                }
                                $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lpm1_bc."','".$lpm1_bc."_no','".$botid."'".')">'.$lpm1_bc.'</a></li>';
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$lastpage_bc."','".$lastpage_bc."_no', '".$botid."'".')">'.$lastpage_bc.'</a></li>';
                            }
                            else
                            {
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'1','1_no','".$botid."'".')">1</a></li>';
                                $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'2','2_no', '".$botid."'".')">2</a></li>';
                                $pagination_bc.= '<li><span class="ellipse clickable">...</span></li>';
                                for ($counter_bc = $lastpage_bc - (2 + ($adjacents * 2)); $counter_bc <= $lastpage_bc; $counter_bc++)
                                {
                                    if ($counter_bc == $page)
                                        $pagination_bc.= '<li class="active"><span class="current">'.$counter_bc.'</span>';
                                    else
                                        $pagination_bc.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationBC('."'".$counter_bc."','".$counter_bc."_no', '".$botid."'".')">'.$counter_bc.'</a></li>';
                                }
                            }
                        }

                        if ($page < $counter_bc - 1)
                        {
                            $pagination_bc.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationBC('."'".$next_bc."','".$next_bc."_no', '".$botid."'".')">&gt;</a></li>';
                        }
                        else
                        {
                            $pagination_bc.= '<li class="active"><span class="current next">&gt;</span>';
                        }


                        $pagination_bc .= '</ul>';
                    }

                    echo $pagination_bc;
                    ?>
					<img id="imgLoadAjaxBC" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
				</div>
			</td>
		</tr>
	</tbody>
</table>
