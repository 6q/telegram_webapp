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
<div id="channelMessagesNavPosition" class="light-theme simple-pagination">
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
                $pagination.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePagination('."'0','first','".$channelId."'".')")">&lt;</a>';
            else
                $pagination.= '<li class="disabled"><span class="current prev">&lt;</span></li>';	
                
                
            if ($lastpage < 7 + ($adjacents * 2))
            {	
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= '<li class="active"><span class="current">'.$counter.'</span></li>';
                    else
                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$channelId."'".')">'.$counter.'</a></li>';					
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
                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$channelId."'".')">'.$counter.'</a></li>';				
                    }
                    $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no', '".$channelId."'".')">'.$lpm1.'</a></li>';
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$channelId."'".')">'.$lastpage.'</a></li>';	
                }
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no', '".$channelId."'".')">1</a></li>';
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$channelId."'".')">2</a></li>';
                    $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
                        else
                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$channelId."'".')">'.$lastpage.'</a></li>';					
                    }
                    $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lpm1."','".$lpm1."_no','".$channelId."'".')">'.$lpm1.'</a></li>';
                        $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$lastpage."','".$lastpage."_no', '".$channelId."'".')">'.$lastpage.'</a></li>';		
                }
                else
                {
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'1','1_no','".$channelId."'".')">1</a></li>';
                    $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'2','2_no', '".$channelId."'".')">2</a></li>';
                    $pagination.= '<li><span class="ellipse clickable">...</span></li>';
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= '<li class="active"><span class="current">'.$counter.'</span>';
                        else
                            $pagination.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePagination('."'".$counter."','".$counter."_no', '".$channelId."'".')">'.$counter.'</a></li>';					
                    }
                }
            }
            
            if ($page < $counter - 1) 
            {
                $pagination.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePagination('."'".$next."','".$next."_no', '".$channelId."'".')">&gt;</a></li>';
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