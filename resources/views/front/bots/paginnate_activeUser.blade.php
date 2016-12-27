<table id="activeUser">
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
<div id="activeUserNavPosition" class="light-theme simple-pagination">
    <?php
		$page = $current_page;
        if($total_pages_activeUser > 0)
        {
            $prev_u = $page - 1;
            $next_u = $page + 1;
            $lastpage_u = ceil($total_pages_activeUser/$limit);
            $lpm1_u = $lastpage_u - 1;
        }	

        $pagination_u = '';
        if($lastpage_u >= 1)
        {
            $pagination_u = '<ul>';
            
            if ($page > 1) 
                $pagination_u.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationU('."'0','first','".$botid."'".')")">&lt;</a>';
            else
                $pagination_u.= '<li class="disabled"><span class="current prev">&lt;</span></li>';	
                
                
            if ($lastpage_u < 7 + ($adjacents * 2))
            {	
                for ($counter_u = 1; $counter_u <= $lastpage_u; $counter_u++)
                {
                    if ($counter_u == $page)
                        $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span></li>';
                    else
                        $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$botid."'".')">'.$counter_u.'</a></li>';					
                }
            }	
            elseif($lastpage_u > 5 + ($adjacents * 2))
            {
                if($page < 1 + ($adjacents * 2))		
                {
                    for ($counter_u = 1; $counter_u < 4 + ($adjacents * 2); $counter_u++)
                    {
                        if ($counter_u == $page)
                            $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span>';
                        else
                            $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$botid."'".')">'.$counter_u.'</a></li>';				
                    }
                    $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lpm1_u."','".$lpm1_u."_no', '".$botid."'".')">'.$lpm1_u.'</a></li>';
                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lastpage_u."','".$lastpage_u."_no', '".$botid."'".')">'.$lastpage_u.'</a></li>';	
                }
                elseif($lastpage_u - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'1','1_no', '".$botid."'".')">1</a></li>';
                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'2','2_no', '".$botid."'".')">2</a></li>';
                    $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                    for ($counter_u = $page - $adjacents; $counter_u <= $page + $adjacents; $counter_u++)
                    {
                        if ($counter_u == $page)
                            $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span>';
                        else
                            $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$botid."'".')">'.$counter_u.'</a></li>';					
                    }
                    $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lpm1_u."','".$lpm1_u."_no','".$botid."'".')">'.$lpm1_u.'</a></li>';
                        $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$lastpage_u."','".$lastpage_u."_no', '".$botid."'".')">'.$lastpage_u.'</a></li>';		
                }
                else
                {
                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'1','1_no','".$botid."'".')">1</a></li>';
                    $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'2','2_no', '".$botid."'".')">2</a></li>';
                    $pagination_u.= '<li><span class="ellipse clickable">...</span></li>';
                    for ($counter_u = $lastpage_u - (2 + ($adjacents * 2)); $counter_u <= $lastpage_u; $counter_u++)
                    {
                        if ($counter_u == $page)
                            $pagination_u.= '<li class="active"><span class="current">'.$counter_u.'</span>';
                        else
                            $pagination_u.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationU('."'".$counter_u."','".$counter_u."_no', '".$botid."'".')">'.$counter_u.'</a></li>';					
                    }
                }
            }
            
            if ($page < $counter_u - 1) 
            {
                $pagination_u.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationU('."'".$next_u."','".$next_u."_no', '".$botid."'".')">&gt;</a></li>';
            }
            else
            {
                $pagination_u.= '<li class="active"><span class="current next">&gt;</span>';
            }

            $pagination_u .= '</ul>';
        }
        
        echo $pagination_u;
    ?>
        <img id="imgLoadAjaxU" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
</div>