<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Synapse - {{strip_tags($__env->yieldContent('title', 'L\'outil de pilotage des idées et projets de simplification administrative'))}}</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="Julian Davreux (eWBS)" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8" />
	
	<link rel="shortcut icon" href="{{{ secure_asset('favicon.ico') }}}"/>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'/>
	<link href='https://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'/>
	{{ HTML::style('js/bootstrap/dist/css/bootstrap.min.css') }}
	{{ HTML::style('fonts/font-awesome-4/css/font-awesome.min.css') }}
	@yield('layoutstyles')

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		{{ HTML::script('js/behaviour/html5shiv.min.js') }}
		{{ HTML::script('js/behaviour/respond.min.js') }}
		{{ HTML::script('js/behaviour/ie8.js') }}
	<![endif]-->

	{{ HTML::style('css/style.css') }}
	{{ HTML::script('js/jquery.js') }}
</head>

<body class="@yield('bodyclass')">
	@yield('wrapper')
	
	{{ HTML::script('js/bootstrap/dist/js/bootstrap.min.js') }}
	
	@yield('layoutscripts')
	
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
	</script>
	@yield('containerscripts')
	@yield('scripts')
</body>
</html>
