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
		$page = $current_page;
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
                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$user_id."'".')">'.$lastpage.'</a></li>';					
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