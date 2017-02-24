<table id="botChannels">
    <thead>
    <tr>
        <th>{{ trans('front/bots.submenu_heading_text') }}</th>
        <th>{{ trans('front/bots.action') }}</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($chanels)){
    foreach($chanels as $d5 => $v5){
    ?>
    <tr>
        <td><?php echo $v5->chanel_submenu_heading_text;?></td>
        <td>
            <a class="btn btn-warning" href="{!! URL::to('/command/chanel_edit/'.$v5->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            <a class="btn btn-danger" onclick="return warnBeforeRedirect('{!! URL::to('/command/chanel_delete/'.$v5->type_id.'/'.$v5->id) !!}');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
    <tr>
        <td colspan="5" class="paginacio">
            <div class="botonou">
                <a href="{!! URL::to('/command/create/'.$botid) !!}" class="btn btn-success">{!! trans('front/dashboard.create_command') !!}</a>
            </div>

            <div id="botChannelsNavPosition" class="light-theme simple-pagination">
                <?php
                $page = $current_page;
                if($total_pages_chanels > 0)
                {
                    $prev_ch = $page - 1;
                    $next_ch = $page + 1;
                    $lastpage_ch = ceil($total_pages_chanels/$limit);
                    $lpm1_ch = $lastpage_ch - 1;
                }

                $pagination_ch = '';
                if($lastpage_ch >= 1)
                {
                    $pagination_ch = '<ul>';

                    if ($page > 1)
                        $pagination_ch.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationCH('."'0','first','".$botid."'".')")">&lt;</a>';
                    else
                        $pagination_ch.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                    if ($lastpage_ch < 7 + ($adjacents * 2))
                    {
                        for ($counter_ch = 1; $counter_ch <= $lastpage_ch; $counter_ch++)
                        {
                            if ($counter_ch == $page)
                                $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span></li>';
                            else
                                $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$botid."'".')">'.$counter_ch.'</a></li>';
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
                                    $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$botid."'".')">'.$counter_ch.'</a></li>';
                            }
                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lpm1_ch."','".$lpm1_ch."_no', '".$botid."'".')">'.$lpm1_ch.'</a></li>';
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lastpage_ch."','".$lastpage_ch."_no', '".$botid."'".')">'.$lastpage_ch.'</a></li>';
                        }
                        elseif($lastpage_ch - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                        {
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'1','1_no', '".$botid."'".')">1</a></li>';
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_ch = $page - $adjacents; $counter_ch <= $page + $adjacents; $counter_ch++)
                            {
                                if ($counter_ch == $page)
                                    $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                else
                                    $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$botid."'".')">'.$counter_ch.'</a></li>';
                            }
                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lpm1_ch."','".$lpm1_ch."_no','".$botid."'".')">'.$lpm1_ch.'</a></li>';
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$lastpage_ch."','".$lastpage_ch."_no', '".$botid."'".')">'.$lastpage_ch.'</a></li>';
                        }
                        else
                        {
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'1','1_no','".$botid."'".')">1</a></li>';
                            $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_ch.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_ch = $lastpage_ch - (2 + ($adjacents * 2)); $counter_ch <= $lastpage_ch; $counter_ch++)
                            {
                                if ($counter_ch == $page)
                                    $pagination_ch.= '<li class="active"><span class="current">'.$counter_ch.'</span>';
                                else
                                    $pagination_ch.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCH('."'".$counter_ch."','".$counter_ch."_no', '".$botid."'".')">'.$counter_ch.'</a></li>';
                            }
                        }
                    }

                    if ($page < $counter_ch - 1)
                    {
                        $pagination_ch.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationCH('."'".$next_ch."','".$next_ch."_no', '".$botid."'".')">&gt;</a></li>';
                    }
                    else
                    {
                        $pagination_ch.= '<li class="active"><span class="current next">&gt;</span>';
                    }

                    $pagination_ch .= '</ul>';
                }

                echo $pagination_ch;
                ?>
                <img id="imgLoadAjaxCH" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
            </div>
        </td>
    </tr>
    </tbody>
</table>