<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>@section('title') Synapse @show</title> 
	@section('meta_keywords')
	<meta name="keywords" content="" />
	@show @section('meta_author')
	<meta name="author" content="Julian Davreux (eWBS)" />
	@show @section('meta_description')
	<meta name="description" content="" />
	@show
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'>
	{{ HTML::style('js/bootstrap/dist/css/bootstrap.min.css') }}
	{{ HTML::style('fonts/font-awesome-4/css/font-awesome.min.css') }}
	{{ HTML::style('js/jquery.nanoscroller/nanoscroller.css') }}
	{{ HTML::style('js/jquery.datatables/bootstrap-adapter/css/datatables.css') }}
	{{ HTML::style('js/bootstrap.switch/bootstrap-switch.css')}}
	{{ HTML::style('js/jquery.icheck/skins/square/blue.css') }}
	{{ HTML::style('js/jquery.select2/css/select2.min.css') }}
	{{ HTML::style('js/bootstrap.switch/bootstrap-switch.css') }}
	{{ HTML::style('js/bootstrap.slider/css/slider.css') }}
	{{ HTML::style('js/dropzone/css/basic.css') }}
	{{ HTML::style('js/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css') }}
	{{ HTML::style('js/jquery.ui/jquery-ui-1.10.4.custom.min.css') }}

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		{{ HTML::script('js/behaviour/html5shiv.min.js') }}
		{{ HTML::script('js/behaviour/respond.min.js') }}
		{{ HTML::script('js/behaviour/ie8.js') }}
	<![endif]-->

	{{ HTML::style('css/style.css') }}

	<link rel="shortcut icon" href="{{{ secure_asset('favicon.ico') }}}">

	{{ HTML::script('js/jquery.js') }}
</head>

<body>
	<!-- navbar -->
	<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="fa fa-gear"></span>
				</button>
				<a class="navbar-brand" href="#"><span>Synapse</span></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li {{(Request::is('/') ? ' class="active"' : '')}}><a href="{{{ URL::secure('') }}}"><span class="fa fa-home"></span> Accueil</a></li>
					<li {{(Request::is('contact') ? ' class="active"' : '')}}><a href="{{{ URL::secure('/contact') }}}"><span class="fa fa-envelope"></span> Contact</a></li>
					@if (Auth::check() && count(Auth::user()->EWBSMember))
						<li {{(Request::is('tracker') ? ' class="active"' : '')}}><a href="http://tracking.e-wbs.be" target="_blank"><span class="fa fa-bug"></span> Signaler un bug</a></li>
					@endif
				</ul>
				<ul class="nav navbar-nav navbar-right user-nav">
					@if (Auth::check())
					<li class="dropdown profile_menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							{{Gravatarer::make( ['email' => Auth::user()->email, 'size' => 30, 'secured' => true] )->html()}}{{{ Auth::user()->username }}} <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="{{{ URL::secure('user') }}}">Mes infos</a></li>
							<li><a href="{{ route('UserGetFilters') }}">Mes filtres</a></li>
							<li class="divider"></li>
							<li><a href="{{{ URL::secure('user/logout') }}}">Déconnexion</a></li>
						</ul>
					</li>
					@else
					<li class="button"><a href="{{{ URL::secure('/user/login') }}}" title="Connexion"><span class="fa fa-user"></span></a></li>
					@endif
				</ul>
			</div>
			<!-- /navbar-collapse -->
		</div>
	</div>
	
	<div id="cl-wrapper" class="@if (Auth::check()) fixed-menu @endif">
		@if (Auth::check()) @include('site.layouts.sidebar') @endif
		@yield('aside')
		<div class="container-fluid" id="pcont">
			@include('notifications')
			@yield('content')
			<div class="cl-mcont">
				<hr/>
				<p class="muted">
					Synapse est développé par <a href="http://www.ensemblesimplifions.be" target="_blank">eWBS</a><br />
					<small>Propulsé par <a href="http://www.laravel.com/" target="_blank">Laravel</a> | Mis en forme par <a href="http://getbootstrap.com/" target="_blank">Bootstrap</a> | Version {{Config::get('app.version')}}{{Config::getEnvironment()=='production'?'':'-'.Config::getEnvironment()}}</small>
				</p>
			</div>
		</div>
	</div>
	{{ HTML::script('js/bootstrap/dist/js/bootstrap.min.js') }}
	{{ HTML::script('js/jquery.ui/jquery-ui.js') }}
	{{ HTML::script('js/jquery.ui/jquery-ui-1.10.4.custom.min.js') }}
	{{ HTML::script('js/jquery.nanoscroller/jquery.nanoscroller.js') }}
	{{ HTML::script('js/jquery.datatables/jquery.datatables.min.js') }}
	{{ HTML::script('js/jquery.datatables/jquery.datatables.refresh.js') }}
	{{ HTML::script('js/jquery.datatables/jquery.datatables.columndata.js') }}
	{{ HTML::script('js/jquery.datatables/bootstrap-adapter/js/datatables.js') }}
	{{ HTML::script('js/bootstrap.switch/bootstrap-switch.min.js') }}
	{{ HTML::script('js/jquery.icheck/icheck.min.js') }}
	{{ HTML::script('js/jquery.select2/js/select2.min.js') }}
	{{ HTML::script('js/jquery.select2/js/i18n/fr.js') }}
	{{ HTML::script('js/bootstrap.switch/bootstrap-switch.min.js') }}
	{{ HTML::script('js/bootstrap.slider/js/bootstrap-slider.js') }}
	{{ HTML::script('js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js') }}
	{{ HTML::script('js/bootstrap.datetimepicker/js/locales/bootstrap-datetimepicker.fr.js') }}
	{{ HTML::script('js/jquery.flot/jquery.flot.min.js') }}
	{{ HTML::script('js/jquery.flot/jquery.flot.pie.min.js') }}
	{{ HTML::script('js/jquery.flot/jquery.flot.resize.min.js') }}
	{{ HTML::script('js/jquery.flot/jquery.flot.labels.js') }}
	{{ HTML::script('js/jquery.easy-overlay/jquery.easy-overlay.js') }}
	{{ HTML::script('js/jquery.number/jquery.number.min.js') }}
	{{ HTML::script('js/jquery.nestable/jquery.nestable.js') }}
	{{ HTML::script('js/synapse/modIdeas.js') }}
	{{-- HTML::script('js/synapse/modDemarches-list.js') --}}
	{{ HTML::script('js/synapse/modDemarches-edit.js') }}
	{{ HTML::script('js/behaviour/general.js') }}

	<script type="text/javascript">
		$(document).ready(function(){
			App.init({
				'tooltip': true,
				'popover': true,
				'nanoScroller': true,
				'bootstrapSwitch':true,
				'dateTime':false,
				'select2':true,
				'slider':true
			});
		});
		<?php /* synapseGlobal_BaseURL = {{Config::get('app.url')}} */ ?>
	</script>

	@yield('scripts')
	</body>
</html>
