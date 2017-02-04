@extends('back.template')

@section('main')

	@include('back.partials.entete', ['title' => trans('back/emailtemplate.dashboard'), 'icone' => 'page', 'fil' => link_to('emailtemplate', trans('back/emailtemplate.view_email_template')) . ' / ' . trans('back/emailtemplate.card')])

	<p>{{ trans('back/emailtemplate.title') . ' : ' .  $template->title }}</p>
	<p>{{ trans('back/emailtemplate.subject') . ' : '.$template->subject }}</p>
	<?php /*
    <p>{{ trans('back/emailtemplate.from') . ' : ' .  $template->from }}</p>
	<p>{{ trans('back/emailtemplate.reply_to_email') . ' : ' .  $template->reply_to_email }}</p>
	<p>{{ trans('back/emailtemplate.is_html') . ' : ' .  $template->is_html }}</p>
	*/ ?>
	<p>
	{{ trans('back/emailtemplate.status') . ' : '}} @if($template->status ==1) <img class="img-responsive" src="{{asset("img/enable.png")}}" alt=""> @else <img class="img-responsive" src="{{asset("img/disable.png")}}" alt=""> @endif</p>
	
	<p>
		<?php echo $template->description;?>
	</p>

@stop