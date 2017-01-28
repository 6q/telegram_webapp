<table id="botAutoresponse">
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
            <a class="btn btn-warning" href="{!! URL::to('/command/autoresponse_edit/'.$v2->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            <a class="btn btn-danger" href="{!! URL::to('/command/autoresponse_delete/'.$v2->type_id.'/'.$v2->id) !!}" onclick="return confirm('Are you sure want to delete this command?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
    <tfoot>
    <tr>
        <td colspan="5">
        	<?php if(isset($planDetails[0]->autoresponses) && !empty($planDetails[0]->autoresponses)){ 
				echo '<span class="info_test"> '.$total_pages.' / '.$planDetails[0]->autoresponses.' </span>';
			 } ?>
            
            <?php 
				if(isset($planDetails[0]->autoresponses) && !empty($planDetails[0]->autoresponses) && $total_pages < $planDetails[0]->autoresponses){
				?>
					<a href="{!! URL::to('/command/create/'.$botid.'?type=autoresponses') !!}" class="btn btn-primary">{!! trans('front/dashboard.create_command') !!}</a>
				<?php
				}
				else{
					?>
					<a href="{!! URL::to('/command/create/'.$botid.'?type=autoresponses&act=1') !!}" class="btn btn-primary">{!! trans('front/dashboard.create_command') !!}</a>
					<?php
				}
			 ?>
        </td>
    </tr>
    </tfoot>
    </tbody>
</table>
<div id="botAutoresponseNavPosition" class="light-theme simple-pagination">
    <?php
        $adjacents = 3;
        $page = $current_page;
        if($total_pages > 0)
        {
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = ceil($total_pages/$limit);
            $lpm1 = $lastpage - 1;
        }	
        
        $pagination = '';
        if($lastpage > 1)
        {
            $pagination = '<ul>';
            
            if ($page > 1) 
                $pagination.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePagination('."'0','first','".$botid."'".')")">&lt;</a>';
            else
                $pagination.= '<li class="disabled"><span class="current prev">&lt;</span></li>';	
                
                
            if ($lastpage < 7 + ($adjacents * 2))
            {	
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= '<li class="active"><span class="current">'.$counter.'</span></li>';
                    else
                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$botid."'".')">'.$counter.'</a></li>';					
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
                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$botid."'".')">'.$counter.'</a></li>';				
                    }
                    $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no', '".$botid."'".')">'.$lpm1.'</a></li>';
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$botid."'".')">'.$lastpage.'</a></li>';	
                }
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no', '".$botid."'".')">1</a></li>';
					$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$botid."'".')">2</a></li>';
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
						else
							$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$botid."'".')">'.$counter.'</a></li>';					
					}
					$pagination.= '<li><span class="ellipse clickable">...</span></li>';
					$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no','".$botid."'".')">'.$lpm1.'</a></li>';
						$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$botid."'".')">'.$lastpage.'</a></li>';		
				}
				else
				{
					$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no','".$botid."'".')">1</a></li>';
					$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$botid."'".')">2</a></li>';
					$pagination.= '<li><span class="ellipse clickable">...</span></li>';
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
						else
							$pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$botid."'".')">'.$counter.'</a></li>';					
					}
				}
            }
			
			if ($page < $counter - 1) 
			{
				$pagination.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePagination('."'".$next."','".$next."_no', '".$botid."'".')">&gt;</a></li>';
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