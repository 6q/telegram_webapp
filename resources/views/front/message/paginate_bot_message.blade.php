<?php
  if(isset($bots) && !empty($bots)){
  $i = 0;
  foreach($bots as $k1 => $v1){
  ++$i;
	 ?>
      <table id="message_tbl_<?php echo $i;?>">
      <thead>
        <tr>
          <th>{{ trans('front/message.bot_user') }}</th>
          <th>{{ trans('front/message.created') }} </th>
          <th>{{ trans('front/message.text') }}</th>
          <th>{{ trans('front/message.reply') }}</th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(!empty($v1->message)){
          foreach($v1->message as $mk1 => $mv1){
            ?>
                <tr>
                  <td><?php echo $mv1->bot_user;?></td>
                  <td><?php echo $mv1->forward_date;?></td>
                  <td><?php echo $mv1->text;?></td>
                  <td>
                    <?php 
        if(file_exists(public_path().'/uploads/'.$mv1->reply_message) && !empty($mv1->reply_message)){
            ?>
            {!! HTML::image('uploads/'.$mv1->reply_message) !!}
            <?php
        }
        else{
            echo $mv1->reply_message;
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
		$page = $current_page;
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
	  <?php
	}
  }
?>