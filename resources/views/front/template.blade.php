<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{{ trans('front/site.title') }}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@yield('head')
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		{!! HTML::style('css/front/style.css') !!}
		{!! HTML::style('css/front/bootstrap.css') !!}
		{!! HTML::style('css/front/datepicker.css') !!}
		{!! HTML::style('lib/css/nanoscroller.css') !!}
		{!! HTML::style('lib/css/emoji.css') !!}

		{!! HTML::script('js/front/jquery1.12.4.js') !!}
		{!! HTML::script('js/front/bootstrap-datepicker.js') !!}
		{!! HTML::script('js/front/bootstrap.js') !!}




	</head>
	
	<body>
		<section class="col_top">
			<div class="container_1">
				@if(session()->has('ok'))
					@include('partials/error', ['type' => 'success', 'message' => session('ok')])
				@endif	
				@if(isset($info))
					@include('partials/error', ['type' => 'info', 'message' => $info])
				@endif
					@include('front.header')
				@yield('main')
				
				

				
				@yield('content')


			</div>

		</section>
		<footer>

			Citymes © Copyright – Tots els drets reservats   |   <a href="http://www.citymes.com/#contactar" target="_blank">Contactar</a> | <a href="http://www.citymes.com/ca/avis-legal/" target="_blank">Avís Legal</a>   |   <a href="http://www.citymes.com/" target="_blank">Citymes.com</a>

		</footer>
		{!! HTML::script('lib/js/nanoscroller.min.js') !!}
		{!! HTML::script('lib/js/tether.min.js') !!}
		{!! HTML::script('lib/js/config.js') !!}
		{!! HTML::script('lib/js/util.js') !!}
		{!! HTML::script('lib/js/jquery.emojiarea.js') !!}
		{!! HTML::script('lib/js/emoji-picker.js') !!}
	</body>
</html>
   
<script>
  jQuery(document).ready(function () {
        //alert('hello');
        jQuery(function () {
            jQuery(".datepicker").datepicker();
        });
    });
  jQuery(document).ready(function(){
	  jQuery('[data-toggle="tooltip"]').tooltip({placement: "bottomreat el"});
	});
</script>
<script>
    $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: '/lib/img',
            popupButtonClasses: 'fa fa-smile-o'
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
    });
</script>
@yield('scripts')