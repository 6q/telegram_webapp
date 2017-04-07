@extends('front.template')
@section('main')

    <script src="https://use.fontawesome.com/e14e68e15c.js"></script>

	{!! HTML::style('css/front/simplePagination.css') !!}
	{!! HTML::script('js/front/jquery.simplePagination.js') !!}

	<div class="col-sm-9 col-sm-offset-3 col-lg-9 col-lg-offset-3">

		@include('front.top')

		<div class="my_account">
			<h4>{!! trans('front/fornt_user.my_account') !!}</h4>
			<div class="modify_icon">
            	<?php
                	$ur = '/front_user/'.Auth::user()->id.'/edit';
				?>
				<a href="{!! URL::to($ur) !!}" class="btn btn-success">
                	{!! trans('front/fornt_user.modify_account') !!}
                </a>
                
                
			</div>
		</div>

{!! Form::open(['url' => 'front_user', 'method' => 'post','enctype'=>"multipart/form-data", 'class' => 'form-horizontal panel','id' =>'']) !!}
{!! Form::close() !!}
		<div class="col-lg-7">
            <div class="col-plan">
				<h2>{{ trans('front/fornt_user.my_bots') }}</h2>
					<div id="a_autoResp">
						<table id="bots_content">

						<tbody>
						<?php
						if(!empty($data)){
						foreach($data as $d1 => $dv1){
						$planName = (isset($dv1['user_subscription']['plan_name']) && !empty($dv1['user_subscription']['plan_name'])) ? $dv1['user_subscription']['plan_name'] : '';

						$expDate = (isset($dv1['user_subscription']['expiry_date']) && !empty($dv1['user_subscription']['expiry_date'])) ? $dv1['user_subscription']['expiry_date'] : '';
						$price = (isset($dv1['user_subscription']['price']) && !empty($dv1['user_subscription']['price'])) ? $dv1['user_subscription']['price'] : '';

						?>
						<tr>
							<td style="text-align:left;padding:0 20px 20px">
								<h3 style="font-size:16px; font-weight:bold;">
                                	<?php
                                    	if($dv1['bot']['is_subscribe'] == 1){
										?>
                                        	<a href="javascript:void(0);"><?php echo $dv1['bot']['username'];?></a>
                                        <?php
										}
										else{
										?>
                                        	<a href="{!! URL::to('/bot/detail/'.$dv1['bot']['id']) !!}"><?php echo $dv1['bot']['username'];?></a> 
                                        <?php
										}
									?>
								</h3>
								<ul>
									<li>
                                        <b>{{ trans('front/fornt_user.cost') }}</b>: <?php echo $price; ?>€
									</li>
									<li>
                                        <b>{{ trans('front/fornt_user.automatic_renewal') }}</b>: <?php if (!empty($expDate)) {
												echo date('d/m/Y', strtotime($expDate));
											}?>
                                    </li>
									<li>
                                        <b><?php echo $planName; ?></b>
                                    </li>
								</ul>
							</td>
                            <td>
								<a href="{!! URL::to('/bot/upgradeplan/'.$dv1['bot']['id']) !!}"><i class="fa fa-tag" aria-hidden="true" title=" {!! trans('front/bots.upgrade_plan') !!}"></i></a>
                            	<?php
                                	if($dv1['bot']['is_subscribe'] == 1){
										?>
                                        	<a href="javascript:void(0);" title=""><i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                                        <?php
									}
									else{
									?>
                                  		<a href="{!! URL::to('/bot/bot_subscription_cancel/'.$dv1['bot']['id']) !!}"
                                   onclick="return confirm('Segur que vols cancelar la subscripció? No hi ha volta enrere');" data-toggle="tooltip" title="" data-original-title="Cancelar Subscripció"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>  	
                                    <?php
									}
								?>
                                <a  href="{!! URL::to('/bot/bot_delete/'.$dv1['bot']['id']) !!}"
                                   onclick="return confirm('Segur que el vols eliminar? No hi ha volta enrere.');" data-toggle="tooltip" title="" data-original-title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>

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
						<div id="bots_contentNavPosition" class="light-theme simple-pagination">
                        	<?php
								$lastpage = 0;
                                if($total_pages > 0)
                                {
                                    $prev = $page - 1;
                                    $next = $page + 1;
                                    $lastpage = ceil($total_pages/$limit);
                                    $lpm1 = $lastpage - 1;
                                }	

                                $pagination = '';
                                if($lastpage >= 1)
                                {
                                    $pagination = '<ul>';
                                    
                                    if ($page > 1) 
                                        $pagination.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePagination('."'0','first','".$user_id."'".')")">&lt;</a>';
                                    else
                                        $pagination.= '<li class="disabled"><span class="current prev">&lt;</span></li>';	
                                        
                                        
                                    if ($lastpage < 7 + ($adjacents * 2))
                                    {	
                                        for ($counter = 1; $counter <= $lastpage; $counter++)
                                        {
                                            if ($counter == $page)
                                                $pagination.= '<li class="active"><span class="current">'.$counter.'</span></li>';
                                            else
                                                $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$user_id."'".')">'.$counter.'</a></li>';					
                                        }
                                    }	
                                    elseif($lastpage > 5 + ($adjacents * 2))
                                    {
                                        if($page < 1 + ($adjacents * 2))		
                                        {
                                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                                            {
                                                if ($counter == $page)
                                                    $pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
                                                else
                                                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$user_id."'".')">'.$counter.'</a></li>';				
                                            }
                                            $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no', '".$user_id."'".')">'.$lpm1.'</a></li>';
                                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$user_id."'".')">'.$lastpage.'</a></li>';	
                                        }
										elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
										{
											$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no', '".$user_id."'".')">1</a></li>';
											$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$user_id."'".')">2</a></li>';
											$pagination.= '<li><span class="ellipse clickable">...</span></li>';
											for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
											{
												if ($counter == $page)
													$pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
												else
													$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$user_id."'".')">'.$counter.'</a></li>';					
											}
											$pagination.= '<li><span class="ellipse clickable">...</span></li>';
											$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no','".$user_id."'".')">'.$lpm1.'</a></li>';
												$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$user_id."'".')">'.$lastpage.'</a></li>';		
										}
										else
										{
											$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no','".$user_id."'".')">1</a></li>';
											$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$user_id."'".')">2</a></li>';
											$pagination.= '<li><span class="ellipse clickable">...</span></li>';
											for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
											{
												if ($counter == $page)
													$pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
												else
													$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$user_id."'".')">'.$counter.'</a></li>';					
											}
										}
                                    }
									
									if ($page < $counter - 1) 
									{
										$pagination.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePagination('."'".$next."','".$next."_no', '".$user_id."'".')">&gt;</a></li>';
									}
									else
									{
										$pagination.= '<li class="active"><span class="current next">&gt;</span>';
									}
			
                                    $pagination .= '</ul>';
                                }
                                
                                echo $pagination;
                            ?>
                            	<img id="imgLoadAjax" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
                    	</div>
                    </div>    
                </div>
                <div class="col-plan">
                    <h2>{{ trans('front/fornt_user.my_channel') }}</h2>
                    <div id="ch_autoResp">
                    	<table id="channels_content">
                            <tbody>
                            <?php
                            if(!empty($chanel_data)){
                            foreach($chanel_data as $ck1 => $cv1){
                            ?>
                            <tr>
                                <td style="text-align:left;padding:0 20px 20px">
                                    <h3 style="font-size:16px; font-weight:bold;">
                                        <?php echo $cv1['channel']['name'];?>
                                    </h3>
                                    <?php if(isset($cv1['user_subscription']) && !empty($cv1['user_subscription'])){?>
                                    <ul>
                                        <li>
                                            <b>{{ trans('front/fornt_user.cost') }}</b>: <?php echo $cv1['user_subscription']['price'];?>€
                                        </li>
                                        <li>
                                            <b>{{ trans('front/fornt_user.automatic_renewal') }}</b>:<?php echo date('d/m/Y', strtotime($cv1['user_subscription']['expiry_date']));?>
                                        </li>
                                    </ul>
                                    <?php }?>
                                </td>
                                <td>
                                    <?php
                                        if($cv1['channel']['is_subscribe'] == 1){
                                            ?>
                                                <a href="javascript:void(0);"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>
                                            <?php
                                        }
                                        else{
                                            ?>
                                                <a href="{!! URL::to('/my_channel/channel_subscription_cancel/'.$cv1['channel']['id']) !!}" onclick="return confirm('Segur que vols cancelar la subscripció? No hi ha volta enrere');" data-toggle="tooltip" title="" data-original-title="Cancelar Subscripció"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>      	
                                            <?php	
                                        }
                                    ?>
                                    <a href="{!! URL::to('/my_channel/channel_delete/'.$cv1['channel']['id']) !!}"
                                       onclick="return confirm('Segur que el vols eliminar? No hi ha volta enrere.');" data-toggle="tooltip" title="" data-original-title="Eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
						<div id="channel_contentNavPosition" class="light-theme simple-pagination">
							<?php
                                $lastpage_ch = 0;
                                if($total_pages_ch > 0)
                                {
                                    $prev_ch = $page - 1;
                                    $next_ch = $page + 1;
                                    $lastpage_ch = ceil($total_pages_ch/$limit);
                                    $lpm1 = $lastpage_ch - 1;
                                }	
    
                                $pagination_ch = '';
                                if($lastpage_ch >= 1)
                                {
                                    $pagination_ch = '<ul>';
                                    
                                    if ($page > 1) 
                                        $pagination_ch.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePagination_Ch('."'0','first','".$user_id."'".')")">&lt;</a>';
                                    else
                                        $pagination_ch.= '<li class="disabled"><span class="current prev">&lt;</span></li>';	
                                        
                                        
                                    if ($lastpage_ch < 7 + ($adjacents * 2))
                                    {	
                                        for ($counter_ch = 1; $counter_ch <= $lastpage_ch; $counter_ch++)
                                        {
                                            if ($counter_ch == $page)
                                                $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span></li>';
                                            else
                                                $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$counter_ch."','".$counter_ch."_no', '".$user_id."'".')">'.$counter_ch.'</a></li>';					
                                        }
                                    }	
                                    elseif($lastpage_ch > 5 + ($adjacents * 2))
                                    {
                                        if($page < 1 + ($adjacents * 2))		
                                        {
                                            for ($counter_ch = 1; $counter_ch < 4 + ($adjacents * 2); $counter_ch++)
                                            {
                                                if ($counter_ch == $page)
                                                    $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                                else
                                                    $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$counter_ch."','".$counter_ch."_no', '".$user_id."'".')">'.$counter_ch.'</a></li>';				
                                            }
                                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$lpm1_ch."','".$lpm1_ch."_no', '".$user_id."'".')">'.$lpm1_ch.'</a></li>';
                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$lastpage_ch."','".$lastpage_ch."_no', '".$user_id."'".')">'.$lastpage_ch.'</a></li>';	
                                        }
                                        elseif($lastpage_ch - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                                        {
                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'1','1_no', '".$user_id."'".')">1</a></li>';
                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'2','2_no', '".$user_id."'".')">2</a></li>';
                                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                            for ($counter_ch = $page - $adjacents; $counter_ch <= $page + $adjacents; $counter_ch++)
                                            {
                                                if ($counter_ch == $page)
                                                    $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                                else
                                                    $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$counter_ch."','".$counter_ch."_no', '".$user_id."'".')">'.$lastpage_ch.'</a></li>';					
                                            }
                                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$lpm1_ch."','".$lpm1_ch."_no','".$user_id."'".')">'.$lpm1_ch.'</a></li>';
                                                $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$lastpage_ch."','".$lastpage_ch."_no', '".$user_id."'".')">'.$lastpage_ch.'</a></li>';		
                                        }
                                        else
                                        {
                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'1','1_no','".$user_id."'".')">1</a></li>';
                                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'2','2_no', '".$user_id."'".')">2</a></li>';
                                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                                            for ($counter_ch = $lastpage_ch - (2 + ($adjacents * 2)); $counter_ch <= $lastpage_ch; $counter_ch++)
                                            {
                                                if ($counter_ch == $page)
                                                    $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                                else
                                                    $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_Ch('."'".$counter_ch."','".$counter_ch."_no', '".$user_id."'".')">'.$counter_ch.'</a></li>';					
                                            }
                                        }
                                    }
                                    
                                    if ($page < $counter_ch - 1) 
                                    {
                                        $pagination_ch.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePagination_Ch('."'".$next_ch."','".$next_ch."_no', '".$user_id."'".')">&gt;</a></li>';
                                    }
                                    else
                                    {
                                        $pagination_ch.= '<li class="active"><span class="current next">&gt;</span>';
                                    }
            
                                    $pagination_ch .= '</ul>';
                                }
                                
                                echo $pagination_ch;
                            ?>
                                <img id="imgLoadAjax_CH" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
                        </div>
                    </div>

			</div>
		</div>

		<div class="col-lg-5">


			<div class="col-plan">
				<h2>{{ trans('front/fornt_user.billing_transactions') }}</h2>
				<div id="tb_autoResp">
                	<table id="bot_billing">
					<thead>
					<tr>
						<th>{{ trans('front/fornt_user.transaction_date') }}</th>
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
						<td>
                        <?php
                            if ($t2->types=="bot") {
                                echo trans('front/dashboard.bot');
                            } else echo trans('front/dashboard.channel');
                            ?>
                        </td>
						<td><?php echo $t2->amount;?>€</td>
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
					<div id="bot_billingNavPosition" class="light-theme simple-pagination">
                	<?php
						$lastpage_tb = 0;
						if($total_pages_tb > 0)
						{
							$prev_tb = $page - 1;
							$next_tb = $page + 1;
							$lastpage_tb = ceil($total_pages_tb/$limit);
							$lpm1_tb = $lastpage_tb - 1;
						}	

						$pagination_tb = '';
						if($lastpage_tb >= 1)
						{
							$pagination_tb = '<ul>';
							
							if ($page > 1) 
								$pagination_tb.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePagination_TB('."'0','first','".$user_id."'".')")">&lt;</a>';
							else
								$pagination_tb.= '<li class="disabled"><span class="current prev">&lt;</span></li>';	
								
								
							if ($lastpage_tb < 7 + ($adjacents * 2))
							{	
								for ($counter_tb = 1; $counter_tb <= $lastpage_tb; $counter_tb++)
								{
									if ($counter_tb == $page)
										$pagination_tb.= '<li class="active"><span class="current">'.$counter_tb.'</span></li>';
									else
										$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$user_id."'".')">'.$counter_tb.'</a></li>';					
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
											$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$user_id."'".')">'.$counter_tb.'</a></li>';				
									}
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lpm1_tb."','".$lpm1_tb."_no', '".$user_id."'".')">'.$lpm1_tb.'</a></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lastpage_tb."','".$lastpage_tb."_no', '".$user_id."'".')">'.$lastpage_tb.'</a></li>';	
								}
								elseif($lastpage_tb - ($adjacents * 2) > $page && $page > ($adjacents * 2))
								{
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'1','1_no', '".$user_id."'".')">1</a></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'2','2_no', '".$user_id."'".')">2</a></li>';
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									for ($counter_tb = $page - $adjacents; $counter_tb <= $page + $adjacents; $counter_tb++)
									{
										if ($counter_tb == $page)
											$pagination_tb.= '<li class="active"><span class="current">'.$counter_tb.'</span>';
										else
											$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$user_id."'".')">'.$lastpage_tb.'</a></li>';					
									}
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lpm1_tb."','".$lpm1_tb."_no','".$user_id."'".')">'.$lpm1_tb.'</a></li>';
										$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$lastpage_tb."','".$lastpage_tb."_no', '".$user_id."'".')">'.$lastpage_tb.'</a></li>';		
								}
								else
								{
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'1','1_no','".$user_id."'".')">1</a></li>';
									$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'2','2_no', '".$user_id."'".')">2</a></li>';
									$pagination_tb.= '<li><span class="ellipse clickable">...</span></li>';
									for ($counter_tb = $lastpage_tb - (2 + ($adjacents * 2)); $counter_tb <= $lastpage_tb; $counter_tb++)
									{
										if ($counter_tb == $page)
											$pagination_tb.= '<li class="active"><span class="current">'.$counter_tb.'</span>';
										else
											$pagination_tb.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination_TB('."'".$counter_tb."','".$counter_tb."_no', '".$user_id."'".')">'.$counter_tb.'</a></li>';					
									}
								}
							}
							
							if ($page < $counter_tb - 1) 
							{
								$pagination_tb.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePagination_TB('."'".$next_tb."','".$next_tb."_no', '".$user_id."'".')">&gt;</a></li>';
							}
							else
							{
								$pagination_tb.= '<li class="active"><span class="current next">&gt;</span>';
							}
	
							$pagination_tb .= '</ul>';
						}
						
						echo $pagination_tb;
					?>
						<img id="imgLoadAjax_CH" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
                </div>
                </div>
			</div>
        </div>
	</div>


	<script>
		function changePagination(pageId,liId,user_id)
		{
			$('#imgLoadAjax').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/front_user/get_bots')?>/'+user_id,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,user_id:user_id},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjax').css('display','none');
					$('#a_autoResp').html(resp);
				}
			});
		}
		
		function changePagination_Ch(pageId,liId,user_id)
		{
			$('#imgLoadAjax_CH').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/front_user/get_chanel')?>/'+user_id,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,user_id:user_id},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjax_CH').css('display','none');
					$('#ch_autoResp').html(resp);
				}
			});
		}
		
		function changePagination_TB(pageId,liId,user_id)
		{
			$('#imgLoadAjax_TB').css('display','inline-block');
			var token = $('input[name=_token]').val();
			$.ajax({
				url: '<?php echo URL::to('/front_user/get_bot_transaction')?>/'+user_id,
				headers: {'X-CSRF-TOKEN': token},
				data: {pageId: pageId,user_id:user_id},
				type:'POST',
				success: function (resp) {
					$('#imgLoadAjax_TB').css('display','none');
					$('#tb_autoResp').html(resp);
				}
			});
		}

		


		jQuery(function ($) {
			var pageParts = $("#bot_plan_sub tbody tr");
			var numPages = pageParts.length;
			var perPage = 5;
			pageParts.slice(perPage).hide();

			$("#bot_plan_subNavPosition").pagination({
				items: numPages,
				itemsOnPage: perPage,
				cssStyle: "light-theme",
				onPageClick: function (pageNum) {
					var start = perPage * (pageNum - 1);
					var end = start + perPage;
					pageParts.hide().slice(start, end).show();
				}
			});
		});

		



	</script>

@stop