<table id="botGallery">
    <thead>
    <tr>
        <th>{{ trans('front/bots.submenu_heading_text') }}</th>
        <th>{{ trans('front/bots.action') }}</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($gallery)){
    foreach($gallery as $d4 => $v4){
    ?>
    <tr>
        <td><?php echo $v4->gallery_submenu_heading_text;?></td>
        <td>
            <a class="btn btn-warning" href="{!! URL::to('/command/gallery_edit/'.$v4->id) !!}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
            <a class="btn btn-danger" href="{!! URL::to('/command/gallery_delete/'.$v4->type_id.'/'.$v4->id) !!}" onclick="return confirm('Are you sure want to delete this gallery?');"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
                <?php if(isset($planDetails[0]->image_gallery) && !empty($planDetails[0]->image_gallery) && $total_pages_gallery < $planDetails[0]->image_gallery){
                ?>
                <a href="{!! URL::to('/command/create/'.$botid.'?type=galleries') !!}" class="btn btn-primary">{!! trans('front/dashboard.create_command') !!}</a>
                <?php
                }
                else{
                ?>
                <a href="{!! URL::to('/command/create/'.$botid.'?type=galleries&act=1') !!}" class="btn btn-primary">{!! trans('front/dashboard.create_command') !!}</a>
                <?php
                }
                ?>
            </div>

            <?php if(isset($planDetails[0]->image_gallery) && !empty($planDetails[0]->image_gallery)){
                echo '<div class="info_test"> '.$total_pages_gallery.' / '.$planDetails[0]->image_gallery.' </div>';
            } ?>
            <div id="botGalleryNavPosition" class="light-theme simple-pagination">
                <?php
                $page = $current_page;
                if($total_pages_gallery > 0)
                {
                    $prev_g = $page - 1;
                    $next_g = $page + 1;
                    $lastpage_g = ceil($total_pages_gallery/$limit);
                    $lpm1_g = $lastpage_g - 1;
                }

                $pagination_g = '';
                if($lastpage_g >= 1)
                {
                    $pagination_g = '<ul>';

                    if ($page > 1)
                        $pagination_g.= '<li><a href="javascript:void(0)" class="page-link" onclick="changePaginationG('."'0','first','".$botid."'".')")">&lt;</a>';
                    else
                        $pagination_g.= '<li class="disabled"><span class="current prev">&lt;</span></li>';


                    if ($lastpage_g < 7 + ($adjacents * 2))
                    {
                        for ($counter_g = 1; $counter_g <= $lastpage_g; $counter_g++)
                        {
                            if ($counter_g == $page)
                                $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span></li>';
                            else
                                $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$botid."'".')">'.$counter_g.'</a></li>';
                        }
                    }
                    elseif($lastpage_g > 5 + ($adjacents * 2))
                    {
                        if($page < 1 + ($adjacents * 2))
                        {
                            for ($counter_g = 1; $counter_g < 4 + ($adjacents * 2); $counter_g++)
                            {
                                if ($counter_g == $page)
                                    $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span>';
                                else
                                    $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$botid."'".')">'.$counter_g.'</a></li>';
                            }
                            $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lpm1_g."','".$lpm1_g."_no', '".$botid."'".')">'.$lpm1_g.'</a></li>';
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lastpage_g."','".$lastpage_g."_no', '".$botid."'".')">'.$lastpage_g.'</a></li>';
                        }
                        elseif($lastpage_g - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                        {
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'1','1_no', '".$botid."'".')">1</a></li>';
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_g = $page - $adjacents; $counter_g <= $page + $adjacents; $counter_g++)
                            {
                                if ($counter_g == $page)
                                    $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span>';
                                else
                                    $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$botid."'".')">'.$counter_g.'</a></li>';
                            }
                            $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lpm1_g."','".$lpm1_g."_no','".$botid."'".')">'.$lpm1_g.'</a></li>';
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$lastpage_g."','".$lastpage_g."_no', '".$botid."'".')">'.$lastpage_g.'</a></li>';
                        }
                        else
                        {
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'1','1_no','".$botid."'".')">1</a></li>';
                            $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'2','2_no', '".$botid."'".')">2</a></li>';
                            $pagination_g.= '<li><span class="ellipse clickable">...</span></li>';
                            for ($counter_g = $lastpage_g - (2 + ($adjacents * 2)); $counter_g <= $lastpage_g; $counter_g++)
                            {
                                if ($counter_g == $page)
                                    $pagination_g.= '<li class="active"><span class="current">'.$counter_g.'</span>';
                                else
                                    $pagination_g.= '<li><a class="page-link" href="javascript:void(0)" onclick="changePaginationG('."'".$counter_g."','".$counter_g."_no', '".$botid."'".')">'.$counter_g.'</a></li>';
                            }
                        }
                    }

                    if ($page < $counter_g - 1)
                    {
                        $pagination_g.= '<li><a class="page-link next" href="javascript:void(0)" onclick="changePaginationG('."'".$next_g."','".$next_g."_no', '".$botid."'".')">&gt;</a></li>';
                    }
                    else
                    {
                        $pagination_g.= '<li class="active"><span class="current next">&gt;</span>';
                    }

                    $pagination_g .= '</ul>';
                }

                echo $pagination_g;
                ?>
                <img id="imgLoadAjaxG" src="{{URL::asset('img/balls.gif')}}" class="loading_ajax_img" style="display:none;">
            </div>
        </td>
    </tr>
    </tbody>
</table>
