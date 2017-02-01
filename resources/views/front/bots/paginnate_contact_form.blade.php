<table id="botContactForm">
    <thead>
    <tr>
        <th>{{ trans('front/bots.submenu_heading_text') }}</th>
        <th>{{ trans('front/bots.action') }}</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($contactForm)){
    foreach($contactForm as $d3 => $v3){
    ?>
    <tr>
        <td><?php echo $v3->submenu_heading_text;?></td>
        <td><a class="btn btn-warning" href="{!! URL::to('/command/contactform_edit/'.$v3->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            <a class="btn btn-danger" href="{!! URL::to('/command/contactform_delete/'.$v3->type_id.'/'.$v3->id) !!}" onclick="return confirm('Are you sure want to delete this contact form?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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

            <div id="botContactFormNavPosition" class="light-theme simple-pagination">
                <?php
                $page = $current_page;
                if($total_pages_contatc_form > 0)
                {
                    $prev_cf = $page - 1;
                    $next_cf = $page + 1;
                    $lastpage_cf = ceil($total_pages_contatc_form/$limit);
                    $lpm1_cf = $lastpage_cf - 1;
                }

                $pagination_cf = '';
                if($lastpage_cf >= 1)
                {
                    $pagination_cf = '<ul>';

                    if ($page > 1)
                        $pagination_cf.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationCF('."'0','first','".$botid."'".')")">&lt;</a>';
                    else
                        $pagination_cf.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                    if ($lastpage_cf < 7 + ($adjacents * 2))
                    {
                        for ($counter_cf = 1; $counter_cf <= $lastpage_cf; $counter_cf++)
                        {
                            if ($counter_cf == $page)
                                $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span></li>';
                            else
                                $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$botid."'".')">'.$counter_cf.'</a></li>';
                        }
                    }
                    elseif($lastpage_cf > 5 + ($adjacents * 2))
                    {
                        if($page < 1 + ($adjacents * 2))
                        {
                            for ($counter_cf = 1; $counter_cf < 4 + ($adjacents * 2); $counter_cf++)
                            {
                                if ($counter_cf == $page)
                                    $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span>';
                                else
                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$botid."'".')">'.$counter_cf.'</a></li>';
                            }
                            $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lpm1_cf."','".$lpm1_cf."_no', '".$botid."'".')">'.$lpm1_cf.'</a></li>';
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lastpage_cf."','".$lastpage_cf."_no', '".$botid."'".')">'.$lastpage_cf.'</a></li>';
                        }
                        elseif($lastpage_cf - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                        {
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'1','1_no', '".$botid."'".')">1</a></li>';
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_cf = $page - $adjacents; $counter_cf <= $page + $adjacents; $counter_cf++)
                            {
                                if ($counter_cf == $page)
                                    $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span>';
                                else
                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$botid."'".')">'.$counter_cf.'</a></li>';
                            }
                            $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lpm1_cf."','".$lpm1_cf."_no','".$botid."'".')">'.$lpm1_cf.'</a></li>';
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$lastpage_cf."','".$lastpage_cf."_no', '".$botid."'".')">'.$lastpage_cf.'</a></li>';
                        }
                        else
                        {
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'1','1_no','".$botid."'".')">1</a></li>';
                            $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_cf.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_cf = $lastpage_cf - (2 + ($adjacents * 2)); $counter_cf <= $lastpage_cf; $counter_cf++)
                            {
                                if ($counter_cf == $page)
                                    $pagination_cf.= '<li class="active"><span class="current">'.$counter_cf.'</span>';
                                else
                                    $pagination_cf.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationCF('."'".$counter_cf."','".$counter_cf."_no', '".$botid."'".')">'.$counter_cf.'</a></li>';
                            }
                        }
                    }

                    if ($page < $counter_cf - 1)
                    {
                        $pagination_cf.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationCF('."'".$next_cf."','".$next_cf."_no', '".$botid."'".')">&gt;</a></li>';
                    }
                    else
                    {
                        $pagination_cf.= '<li class="active"><span class="current next">&gt;</span>';
                    }

                    $pagination_cf .= '</ul>';
                }

                echo $pagination_cf;
                ?>
                <img id="imgLoadAjaxCF" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
            </div>
        </td>
    </tr>
    </tbody>
</table>