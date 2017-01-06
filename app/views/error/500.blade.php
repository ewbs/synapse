<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="images/favicon.png">

<title>Synapse</title>
<link
	href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800'
	rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Raleway:300,200,100'
	rel='stylesheet' type='text/css'>

{{ HTML::style('js/bootstrap/dist/css/bootstrap.css') }}
{{ HTML::style('fonts/font-awesome-4/css/font-awesome.min.css') }}

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
            {{ HTML::script('js/behaviour/html5shiv.min.js') }}
            {{ HTML::script('js/behaviour/respond.min.js') }}
        <![endif]-->

{{ HTML::style('css/style.css') }}
</head>

<body class="texture">

	<div id="cl-wrapper" class="error-container">
		<div class="page-error">
			<h1 class="number text-center">500</h1>
			<h2 class="description text-center">Désolé mais il semble qu'un
				problème serveur nous empêche de vous fournir ce que vous avez
				demandé.</h2>
			<h3 class="text-center">
				Un petit tour par la <a href="{{{URL::secure('/')}}}">page d'accueil</a>?
			</h3>
		</div>
		<div class="text-center copy">
			2015 <a href="http://www.ensemblesimplifions.be">eWBS</a>
		</div>


	</div>

	{{ HTML::script('js/jquery.js') }}
	{{ HTML::script('js/behaviour/general.js') }}

</body>
</html>
