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
            <ul>
                <li>
                    <b>{{ trans('front/fornt_user.cost') }}</b>: <?php echo $cv1['user_subscription']['price'];?>€
                </li>
                <li>
                    <b>{{ trans('front/fornt_user.automatic_renewal') }}</b>:<?php echo date('d/m/Y', strtotime($cv1['user_subscription']['expiry_date']));?>
                </li>
            </ul>
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
		$page = $current_page;
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