@extends('back.template')

@section('main')

	<!-- Entête de page -->
	@include('back.partials.entete', ['title' => trans('back/users.dashboard'), 'icone' => 'user', 'fil' => link_to('user', trans('back/users.Users')) . ' / ' . trans('back/users.edition')])

	<div class="col-sm-12">
		{!! Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'put', 'class' => 'form-horizontal panel',"enctype"=>"multipart/form-data"]) !!}
			{!! Form::control('text', 0, 'first_name', $errors, trans('back/users.first_name')) !!}
			{!! Form::control('text', 0, 'last_name', $errors, trans('back/users.last_name')) !!}
			{!! Form::control('email', 0, 'email', $errors, trans('back/users.email')) !!}
			{!! Form::selection('role', $select, $user->role_id, trans('back/users.role')) !!}
			{!! Form::selection('country', $country, $user->country_id, trans('back/users.country')) !!}
			{!! Form::control('text', 0, 'zipcode', $errors, trans('back/users.zipcode')) !!}
			{!! Form::control('text', 0, 'mobile', $errors, trans('back/users.mobile')) !!}
			{!! Form::selection('user_type', array("individual"=>"individual","company"=>"company"), $user->user_type, trans('back/users.user_type'),array('class' => 'form-control','onchange'=>'checkUserType(this.value);')) !!}
			<div class="company_div" style="display:none;">
			{!! Form::control('text',0,'company_name', $errors, trans('back/users.company_name')) !!}
			{!! Form::control('text',0,'vat_number', $errors,  trans('back/users.vat_number')) !!}
			</div>
			{!! Form::selection('payment_method', array("stripe"=>"stripe"), $user->payment_method, trans('back/users.payment_method')) !!}
			{!! Form::control('file', 0, 'image', $errors, trans('back/users.image')) !!}
			
			@if($user->image !='')
			 <img class="img-responsive" src="{{ url('/user_images/150x150/'.$user->image) }}" alt="">
			@endif
			
			{!! Form::checkHorizontal('status', trans('back/users.status'), $user->status) !!}
			{!! Form::submit(trans('front/form.send')) !!}
		{!! Form::close() !!}
	</div>

@stop
@section('scripts')
<script type="text/javascript">
function checkUserType(value){
  if(value=='individual'){
   jQuery('.company_div').hide();
  }else{
   jQuery('.company_div').show();
  }
}
@if($user->user_type =='company')
 jQuery('.company_div').show();
@endif
</script>
@stop
