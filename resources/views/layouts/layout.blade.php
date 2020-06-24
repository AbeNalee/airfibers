<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AIRFibers SelfCare</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{asset('css/normalize.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.mind.css')}}">
    <link rel="stylesheet" href="{{asset('css/templatemo-style.css')}}">
	<link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}"/>
    <script src="{{asset('js/vendor/modernizr-2.6.2.min.js')}}"></script>
    <script type="text/javascript">
        function disabled() {
            alert('You already have access')
        }
    </script>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div class="site-bg"></div>
<!--div class="site-bg-overlay"></div-->

<!-- TOP HEADER -->
<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                <p>
                    <!--a class="float-left far fa-clock-o pr-2"> {{date('H:i')}}</a-->
                    <a class="fas fa-phone pl-2 pr-2" href="tel:254731999399"target="_blank"></a>  &emsp; &emsp; &emsp;
					<a class="far fa-envelope pl-2 pr-2" href="mailto:info@airfibers.com" target="_blank"></a> &emsp; &emsp; &emsp;
                    <a class="fab fa-whatsapp pl-2 pr-2" href="https://api.whatsapp.com/send?phone=254731999399"
                       target="_blank"></a> &emsp; &emsp; &emsp;
                    <a href="https://www.facebook.com/airfibersKE" target="_blank"
                       class="float-right fab fa-facebook pl-2"></a>
                </p>
            </div>
        </div>
    </div>
</div> <!-- .top-header -->

<div class="container" id="page-content">
    <div class="row">
        @yield('content')
    </div>
</div>

<!-- SITE-FOOTER -->
<div class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
				<p>
                    Copyright &copy; {{date('Y')}} Airfibers Wireless. All Rights Reserved.

                    <!-- | Design: <a href="http://www.templatemo.com" target="_parent"><span class="green">free templates</span></a> -->
                </p>
            </div>
        </div>
    </div>
</div> <!-- .site-footer -->

<script src="{{asset('js/vendor/jquery-1.10.2.min.js')}}"></script>
<script src="{{asset('js/plugins.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>
<!-- templatemo 439 rectangle -->
</body>
</html>