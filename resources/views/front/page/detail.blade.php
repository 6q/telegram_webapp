@extends('front.template')
@section('main')


    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3 col-message">
     
      @include('front.top')  
      
      <div class="my_account">
        <h4>{{ trans('front/page.detail') }}</h4>
      </div>
      
    
      
      <div class="col-lg-12">
        <?php
          if(isset($pageData) && !empty($pageData)){
             ?>
                <div class="col-plan">
                  <h2><?php echo $pageData[0]->title;?></h2>
                  <div class="page_data">
                    <?php echo $pageData[0]->content;?>
                  </div>
                </div>
              <?php
            }
        ?>
</div>
      
  </div>

<style>
  .thumb {
    width: 20%;
  }
  
  .page_data {
  padding: 10px;
}
  .page_data img {
  border-radius: 0% !important;
  max-width: 80% !important;
  width: 80% !important;
}
</style>
@stop