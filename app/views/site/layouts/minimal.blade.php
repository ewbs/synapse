<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<title>@section('title') Synapse @show</title> @section('meta_keywords')
<meta name="keywords" content="" />
@show @section('meta_author')
<meta name="author" content="Julian Davreux (eWBS)" />
@show @section('meta_description')
<meta name="description" content="" />
@show
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link
	href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800'
	rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Raleway:300,200,100'
	rel='stylesheet' type='text/css'>

{{ HTML::style('js/bootstrap/dist/css/bootstrap.css') }}

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
                    {{ HTML::script('js/behaviour/html5shiv.min.js') }}
                    {{ HTML::script('js/behaviour/respond.min.js') }}
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
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target=".navbar-collapse">
					<span class="fa fa-gear"></span>
				</button>
				<a class="navbar-brand" href="#"><span>Synapse</span></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li {{(Request::is('/') ? ' class="active"' : '')}}><a
						href="{{{ URL::secure('') }}}">Accueil</a></li>
					<li {{(Request::is('contact') ? ' class="active"' : '')}}><a
						href="{{{ URL::secure('/contact') }}}">Contact</a></li>
				</ul>
			</div>
			<!-- /navbar-collapse -->
		</div>
	</div>


	<div id="cl-wrapper">

		<div class="container-fluid" id="pcont">


			@yield('content')

			<div class="cl-mcont">
				<hr />
				<p class="muted">
					Synapse est développé par <a
						href="http://www.ensemblesimplifions.be" target="_blank">eWBS</a><br />
					<small>Propulsé par <a href="http://www.laravel.com/"
						target="_blank">Laravel</a> | Mis en forme par <a
						href="http://getbootstrap.com/" target="_blank">Bootstrap</a> |
						Version {{Config::get('app.version')}}{{Config::getEnvironment()=='production'?'':'-'.Config::getEnvironment()}}
					</small>
				</p>
			</div>

		</div>

	</div>


	{{ HTML::script('js/bootstrap/dist/js/bootstrap.min.js')
	}} {{ HTML::script('js/behaviour/general.js') }}


	<script type="text/javascript">
                $(document).ready(function(){
                    App.init({
                                'tooltip': false,
                                'popover': false,
                                'nanoScroller': false,
                                'nestableLists': false,
                                'hiddenElements': false,
                                'bootstrapSwitch':false,
                                'dateTime':false,
                                'select2':false,
                                'tags':false,
                                'slider':false
                            });
                });
                
                <?php /* synapseGlobal_BaseURL = {{Config::get('app.url')}} */ ?>
            </script>

	@yield('scripts')

</body>
</html>
