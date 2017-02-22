<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ trans('front/site.title') }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/> <!--320-->

        @yield('head')

        {!! HTML::style('css/style.css') !!}
        {!! HTML::style('css/bootstrap.css') !!}



        <!--[if (lt IE 9) & (!IEMobile)]>
                {!! HTML::script('js/vendor/respond.min.js') !!}
        <![endif]-->
        <!--[if lt IE 9]>
                {!! HTML::style('https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js') !!}
                {!! HTML::style('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') !!}
        <![endif]-->

        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">



    </head>

    <body>
    <div class="header">
        <div class="fussion-row">
            <div class="logotip">
                <a class="fusion-logo-link" href="http://www.citymes.com">
                    <img src="/img/logo-citymes.png" alt="Citymes" class="fusion-logo-link img">
                </a>
            </div>

		<div class="fusion-main-menu" style="">
            <ul id="menu-main-menu-catalan" class="fusion-menu">
                @if(Config::get('app.locale')=="en")
                    <li id="menu-item-687" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-687"><a href="http://www.citymes.com/es/#sobre-citymes"><span class="menu-text">About Citymes</span></a></li>
                    <li id="menu-item-688" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-688 current-menu-item"><a href="http://www.citymes.com/es/#plataforma"><span class="menu-text">Platform</span></a></li>
                    <li id="menu-item-690" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-690"><a href="http://www.citymes.com/es/#partners"><span class="menu-text">Partners</span></a></li>
                    <li id="menu-item-691" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-691"><a href="http://www.citymes.com/es/#contactar"><span class="menu-text">Contact</span></a></li>
                    <li id="menu-item-692" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-692 fusion-menu-item-button fusion-last-menu-item"><a href="https://app.citymes.com"><span class="menu-text fusion-button button-default button-medium">PLATFORM LOGIN</span></a></li>
                @elseif(Config::get('app.locale')=="es")
                    <li id="menu-item-687" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-687"><a href="http://www.citymes.com/es/#sobre-citymes"><span class="menu-text">Sobre Citymes</span></a></li>
                    <li id="menu-item-688" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-688 current-menu-item"><a href="http://www.citymes.com/es/#plataforma"><span class="menu-text">Plataforma</span></a></li>
                    <li id="menu-item-690" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-690"><a href="http://www.citymes.com/es/#partners"><span class="menu-text">Partners</span></a></li>
                    <li id="menu-item-691" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-691"><a href="http://www.citymes.com/es/#contactar"><span class="menu-text">Contactar</span></a></li>
                    <li id="menu-item-692" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-692 fusion-menu-item-button fusion-last-menu-item"><a href="https://app.citymes.com"><span class="menu-text fusion-button button-default button-medium">ACCESO PLATAFORMA</span></a></li>
                @elseif(Config::get('app.locale')=="ca")
                    <li id="menu-item-687" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-687"><a href="http://www.citymes.com/#sobre-citymes"><span class="menu-text">Sobre Citymes</span></a></li>
                    <li id="menu-item-688" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-688 current-menu-item"><a href="http://www.citymes.com/#plataforma"><span class="menu-text">Plataforma</span></a></li>
                    <li id="menu-item-690" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-690"><a href="http://www.citymes.com/#partners"><span class="menu-text">Partners</span></a></li>
                    <li id="menu-item-691" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-691"><a href="http://www.citymes.com/#contactar"><span class="menu-text">Contactar</span></a></li>
                    <li id="menu-item-692" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-692 fusion-menu-item-button fusion-last-menu-item"><a href="https://app.citymes.com"><span class="menu-text fusion-button button-default button-medium">ACCÉS PLATAFORMA</span></a></li>
                @endif

                <li style="padding-right:0px;">
                    <div class="col-laguage">
                        <?php
                        $currLang = Config::get('app.locale');
                        $select1 = '';
                        if($currLang == 'en'){
                            $select1 = 'selected="selected"';
                        }

                        $select2 = '';
                        if($currLang == 'es'){
                            $select2 = 'selected="selected"';
                        }
                        $select3 = '';
                        if($currLang == 'ca'){
                            $select3 = 'selected="selected"';
                        }
                        ?>
                        <select name="select_lang" onchange="changeLang();" id="select_lang">
                            <option <?php echo $select3;?> value="{!! URL::to('/language/ca') !!}">{!! trans('front/header.catalan') !!}</option>
                            <option <?php echo $select2;?> value="{!! URL::to('/language/es') !!}">{!! trans('front/header.spanish') !!}</option>
                            <option <?php echo $select1;?> value="{!! URL::to('/language/en') !!}">{!! trans('front/header.english') !!}</option>
                        </select>

                        <script>
                            function changeLang(){
                                var url = $('#select_lang').val();
                                window.location.href = url;
                            }
                        </script>
                    </div>
                </li>
            </ul>
        </div>
        </div>
    </div>
        <!--[if lt IE 8]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <section class="login_register">
            <div class="container">
                @if(session()->has('ok'))
                @include('partials/error', ['type' => 'success', 'message' => session('ok')])
                @endif	
                @if(isset($info))
                @include('partials/error', ['type' => 'info', 'message' => $info])
                @endif
                @yield('main')


            </div>
            <div class="footer-copyright">
                @if(Config::get('app.locale')=="en")
                    Citymes © {{date('Y')}} – All rights reserved   |   <a href="http://www.citymes.com/es/#contactar" target="_blank">Contact</a> | <a href="http://www.citymes.com/es/aviso-legal/" target="_blank">Legal Advise</a>   |   <a href="http://www.citymes.com/es/" target="_blank">Citymes.com</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://www.gestinet.com/es/mantenimiento-informatico">Mantenimiento informático</a>
                @elseif(Config::get('app.locale')=="es")
                    Citymes © {{date('Y')}} – Todos los derechos reservados   |   <a href="http://www.citymes.com/es/#contactar" target="_blank">Contactar</a> | <a href="http://www.citymes.com/es/aviso-legal/" target="_blank">Aviso Legal</a>   |   <a href="http://www.citymes.com/es/" target="_blank">Citymes.com</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://www.gestinet.com/es/mantenimiento-informatico">Mantenimiento informático</a>
                @elseif(Config::get('app.locale')=="ca")
                    Citymes © {{date('Y')}} – Tots els drets reservats   |   <a href="http://www.citymes.com/#contactar" target="_blank">Contactar</a> | <a href="http://www.citymes.com/ca/avis-legal/" target="_blank">Avís Legal</a>   |   <a href="http://www.citymes.com/" target="_blank">Citymes.com</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://www.gestinet.com/manteniment-informatic">Mantenimient informàtic</a>
                @endif
            </div>
        </section>
        {!! HTML::script('js/jquery1.12.4.js') !!}
        {!! HTML::script('js/bootstrap.js') !!}

        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        {!! HTML::script('js/plugins.js') !!}
        {!! HTML::script('js/main.js') !!}

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
                    (function (b, o, i, l, e, r) {
                        b.GoogleAnalyticsObject = l;
                        b[l] || (b[l] =
                                function () {
                                    (b[l].q = b[l].q || []).push(arguments)
                                });
                        b[l].l = +new Date;
                        e = o.createElement(i);
                        r = o.getElementsByTagName(i)[0];
                        e.src = '//www.google-analytics.com/analytics.js';
                        r.parentNode.insertBefore(e, r)
                    }(window, document, 'script', 'ga'));
            ga('create', 'UA-XXXXX-X');
            ga('send', 'pageview');
        </script>

        @yield('scripts')

    </body>
</html>