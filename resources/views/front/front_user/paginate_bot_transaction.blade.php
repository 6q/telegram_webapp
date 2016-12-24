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
		$page = $current_page;
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