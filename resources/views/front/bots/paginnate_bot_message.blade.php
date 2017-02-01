<table id="message_activity">
    <thead>
    <tr>
        <th width="20%">{{ trans('front/bots.user') }}</th>
        <th width="30%">{{ trans('front/bots.message') }} </th>
        <th width="30%">{{ trans('front/bots.replay_message') }}</th>
        <th width="20%">{{ trans('front/bots.date') }}</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($botMessages)){
    foreach($botMessages as $bmk1 => $bmv1){
    ?>
    <tr>
        <td><?php echo $bmv1->first_name.' '.$bmv1->last_name;?></td>
        <td>
            <?php
            if(file_exists(public_path().'/uploads/'.$bmv1->text) && !empty($bmv1->text)){
            ?>
            <button href="#" id="link<?php echo $bmv1->id;?>" data-toggle="modal" data-target="#myModal" src="/uploads/<?=$bmv1->text?>" class="imatge">
                Imatge
            </button>

            <?php
            }
            else{
                echo $bmv1->text;
            }
            ?>
        </td>
        <td>
            <?php
            if(file_exists(public_path().'/uploads/'.$bmv1->reply_message) && !empty($bmv1->reply_message)){
            ?>
            <button href="#" id="link<?php echo $bmv1->id;?>" data-toggle="modal" data-target="#myModal" src="/uploads/<?=$bmv1->reply_message?>" class="imatge">
                Imatge
            </button>
            <?php
            }
            else{
                echo $bmv1->reply_message;
            }
            ?>
        </td>
        <td><?php echo $bmv1->date;?></td>
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
    <tr>
        <td colspan="5" class="paginacio">
            <div id="messageNavPosition" class="light-theme simple-pagination">
                <?php
                $page = $current_page;
                if($total_pages_message > 0)
                {
                    $prev_m = $page - 1;
                    $next_m = $page + 1;
                    $lastpage_m = ceil($total_pages_message/$limit);
                    $lpm1_m = $lastpage_m - 1;
                }

                $pagination_m = '';
                if($lastpage_m >= 1)
                {
                    $pagination_m = '<ul>';

                    if ($page > 1)
                        $pagination_m.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationM('."'0','first','".$botid."'".')")">&lt;</a>';
                    else
                        $pagination_m.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                    if ($lastpage_m < 7 + ($adjacents * 2))
                    {
                        for ($counter_m = 1; $counter_m <= $lastpage_m; $counter_m++)
                        {
                            if ($counter_m == $page)
                                $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span></li>';
                            else
                                $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$botid."'".')">'.$counter_m.'</a></li>';
                        }
                    }
                    elseif($lastpage_m > 5 + ($adjacents * 2))
                    {
                        if($page < 1 + ($adjacents * 2))
                        {
                            for ($counter_m = 1; $counter_m < 4 + ($adjacents * 2); $counter_m++)
                            {
                                if ($counter_m == $page)
                                    $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span>';
                                else
                                    $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$botid."'".')">'.$counter_m.'</a></li>';
                            }
                            $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lpm1_m."','".$lpm1_m."_no', '".$botid."'".')">'.$lpm1_m.'</a></li>';
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lastpage_m."','".$lastpage_m."_no', '".$botid."'".')">'.$lastpage_m.'</a></li>';
                        }
                        elseif($lastpage_m - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                        {
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'1','1_no', '".$botid."'".')">1</a></li>';
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_m = $page - $adjacents; $counter_m <= $page + $adjacents; $counter_m++)
                            {
                                if ($counter_m == $page)
                                    $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span>';
                                else
                                    $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$botid."'".')">'.$counter_m.'</a></li>';
                            }
                            $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lpm1_m."','".$lpm1_m."_no','".$botid."'".')">'.$lpm1_m.'</a></li>';
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$lastpage_m."','".$lastpage_m."_no', '".$botid."'".')">'.$lastpage_m.'</a></li>';
                        }
                        else
                        {
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'1','1_no','".$botid."'".')">1</a></li>';
                            $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_m.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_m = $lastpage_m - (2 + ($adjacents * 2)); $counter_m <= $lastpage_m; $counter_m++)
                            {
                                if ($counter_m == $page)
                                    $pagination_m.= '<li class="active"><span class="current">'.$counter_m.'</span>';
                                else
                                    $pagination_m.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationM('."'".$counter_m."','".$counter_m."_no', '".$botid."'".')">'.$counter_m.'</a></li>';
                            }
                        }
                    }


                    if ($page < $counter_m - 1)
                    {
                        $pagination_m.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationM('."'".$next_m."','".$next_m."_no', '".$botid."'".')">&gt;</a></li>';
                    }
                    else
                    {
                        $pagination_m.= '<li class="active"><span class="current next">&gt;</span>';
                    }

                    $pagination_m .= '</ul>';
                }

                echo $pagination_m;
                ?>
                <img id="imgLoadAjaxM" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
            </div>
        </td>
    </tr>
    </tbody>
</table>
					
