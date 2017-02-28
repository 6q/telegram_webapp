<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{{ trans('front/site.title') }}</title>
		@yield('head')
		<script src="https://use.fontawesome.com/f73f310856.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> <!--320-->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

		{!! HTML::style('css/front/style.css') !!}
		{!! HTML::style('css/front/bootstrap.css') !!}
		{!! HTML::style('css/front/datepicker.css') !!}
		{!! HTML::style('lib/css/nanoscroller.css') !!}
		{!! HTML::style('lib/css/sweetalert.css') !!}
		{!! HTML::style('lib/css/emoji.css') !!}

		{!! HTML::script('js/front/jquery1.12.4.js') !!}
		{!! HTML::script('js/front/bootstrap-datepicker.js') !!}
		{!! HTML::script('js/front/bootstrap-datepicker.es.js') !!}
		{!! HTML::script('js/front/bootstrap.js') !!}
		{!! HTML::script('lib/js/sweetalert.min.js') !!}


		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



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
			@if(Config::get('app.locale')=="en")
				<script type="text/javascript" src="https://s3.amazonaws.com/assets.freshdesk.com/widget/freshwidget.js"></script>
				<script type="text/javascript">
                    FreshWidget.init("", {"queryString": "&widgetType=popup&formTitle=Help+and+Support&submitTitle=Send&submitThanks=Thank+you&searchArea=no", "backgroundImage": "https://s3.amazonaws.com/assets.freshdesk.com/widget/help-button.png", "utf8": "✓", "widgetType": "popup", "buttonType": "image", "buttonText": "Support", "buttonColor": "white", "buttonBg": "#456288", "alignment": "3", "offset": "-1500px", "submitThanks": "Thank you", "formHeight": "500px", "url": "https://gestinet.freshdesk.com"} );
				</script>
				Citymes © {{date('Y')}} – All rights reserved   |   <a href="http://www.citymes.com/#contactar" target="_blank">Contact</a> | <a href="http://www.citymes.com/ca/avis-legal/" target="_blank">Legal Advise</a>   |   <a href="http://www.citymes.com/" target="_blank">Citymes.com</a>
			@elseif(Config::get('app.locale')=="es")
				<script type="text/javascript" src="https://s3.amazonaws.com/assets.freshdesk.com/widget/freshwidget.js"></script>
				<script type="text/javascript">
                    FreshWidget.init("", {"queryString": "&widgetType=popup&formTitle=Ayuda+y+Soporte&submitTitle=Enviar&submitThanks=Gracias%2C+te+contactaremos+lo+mas+r%C3%A1pido+posible&searchArea=no", "backgroundImage": "https://s3.amazonaws.com/assets.freshdesk.com/widget/help-button.png", "utf8": "✓", "widgetType": "popup", "buttonType": "image", "buttonText": "Support", "buttonColor": "white", "buttonBg": "#456288", "alignment": "3", "offset": "-1500px", "submitThanks": "Gracias, te contactaremos lo mas rápido posible", "formHeight": "500px", "url": "https://gestinet.freshdesk.com"} );
				</script>
				Citymes © {{date('Y')}} – Todos los derechos reservados   |   <a href="http://www.citymes.com/es/#contactar" target="_blank">Contactar</a> | <a href="http://www.citymes.com/es/aviso-legal/" target="_blank">Aviso Legal</a>   |   <a href="http://www.citymes.com/" target="_blank">Citymes.com</a>
			@elseif(Config::get('app.locale')=="ca")
				<script type="text/javascript" src="https://s3.amazonaws.com/assets.freshdesk.com/widget/freshwidget.js"></script>
				<script type="text/javascript">
                    FreshWidget.init("", {"queryString": "&widgetType=popup&formTitle=Ajuda+i+Suport&submitTitle=Enviar&submitThanks=Gr%C3%A0cies.+Et+contactarem+el+m%C3%A9s+r%C3%A0pid+possible&searchArea=no", "backgroundImage": "https://s3.amazonaws.com/assets.freshdesk.com/widget/help-button.png", "utf8": "✓", "widgetType": "popup", "buttonType": "image", "buttonText": "Support", "buttonColor": "white", "buttonBg": "#456288", "alignment": "3", "offset": "-1500px", "submitThanks": "Gràcies. Et contactarem el més ràpid possible", "formHeight": "500px", "url": "https://gestinet.freshdesk.com"} );
				</script>
				Citymes © {{date('Y')}} – Tots els drets reservats   |   <a href="http://www.citymes.com/#contactar" target="_blank">Contactar</a> | <a href="http://www.citymes.com/ca/avis-legal/" target="_blank">Avís Legal</a>   |   <a href="http://www.citymes.com/" target="_blank">Citymes.com</a>
			@endif
		</footer>
		{!! HTML::script('lib/js/nanoscroller.min.js') !!}
		{!! HTML::script('lib/js/tether.min.js') !!}
		{!! HTML::script('lib/js/config.js') !!}
		{!! HTML::script('lib/js/util.js') !!}
		{!! HTML::script('lib/js/jquery.emojiarea.js') !!}
		{!! HTML::script('lib/js/emoji-picker.js') !!}
		{!! HTML::script('js/jQueryEmoji.js') !!}
		<script>
            jQuery(document).ready(function () {
                $('.col-sm-8').css({opacity: 0, visibility: "visible"}).animate({opacity: 1}, 'slow');

                //alert('hello');
                jQuery(function () {
                    jQuery(".datepicker").datepicker( {
                        language: '{{Config::get('app.locale')}}',
						format: 'dd/mm/yyyy',
                        pickTime: false,
                        autoclose: true,
					});
                });
            });
            function isTouchDevice(){
                return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
            }
            jQuery(document).ready(function(){
                if(isTouchDevice()===false) {
                    jQuery('[data-toggle="tooltip"]').tooltip({placement: "right"});
                }
            });

            jQuery(document).ready(function() {
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

            jQuery(document).ready(function(){
                $('.chat_tab').Emoji({
                    path:'https://app.citymes.com/img/apple40/'
                });
                $('h2').Emoji({
                    path:'https://app.citymes.com/img/apple40/'
                });
                $('td').Emoji({
                    path:'https://app.citymes.com/img/apple40/'
                });
            });

			
			$(function() {
				setInterval(function() {
					$(".alert").hide()
				}, 5000);
				
				setInterval(function() {
					$("#alertMsg").hide()
				}, 5000);
			});
		</script>

		@yield('scripts')
	</body>
</html>
   
