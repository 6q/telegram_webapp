@extends('front.template')
@section('main')


    <div class="col-sm-8 col-sm-offset-4 col-lg-9 col-lg-offset-3 col-message">
     
      @include('front.top')
      
      <div class="col-lg-12">
        <?php
          if(isset($pageData) && !empty($pageData)){
             ?>
          <div class="my_account">
            <h4><?php echo $pageData[0]->title;?></h4>
          </div>
                <div class="col-plan">
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