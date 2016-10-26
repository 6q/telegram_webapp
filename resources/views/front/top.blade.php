<!--<div class="top_section">
        <div class="search">
          {!! Form::open(['url' => $Form_action, 'method' => 'get','enctype'=>"multipart/form-data", 'class' => '','id' =>'']) !!}
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              <input type="search" placeholder="Search here" value="{!! $search !!}" name="search">
          {!! Form::close() !!}
        </div>

        <div class="col-about">
            <div class="col-notification">
                <ul>
                    <li><a href="{!! URL::to('/messages') !!}"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></a></li>
                </ul>
            </div>

            <div class="col-profile-view">
                <span>{!! HTML::image('uploads/'.Auth::user()->image) !!}</span>
                <p><a href="#">{!! Auth::user()->first_name.' '.Auth::user()->last_name !!}</a></p>
            </div>
        </div>
</div>
-->