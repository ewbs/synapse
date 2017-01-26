<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Synapse - Login</title>
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

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		{{ HTML::script('js/behaviour/html5shiv.min.js') }}
		{{ HTML::script('js/behaviour/respond.min.js') }}
		{{ HTML::script('js/behaviour/ie8.js') }}
	<![endif]-->

	{{ HTML::style('css/style.css') }}
	{{ HTML::script('js/jquery.js') }}
</head>

<body class="texture">
	<div id="cl-wrapper" class="login-container">
		<div class="middle-login">
			<div class="block-flat">
				<div class="header">
					<h3 class="text-center">
						<img class="logo-img" src="{{{ secure_asset('images/logo.png') }}}" alt="logo" />Synapse
					</h3>
				</div>
				<div>
					@if ( Session::get('error') )
					<div class="alert alert-danger">{{ Session::get('error') }}</div>
					@endif @if ( Session::get('notice') )
					<div class="alert alert-info">{{ Session::get('notice') }}</div>
					@endif
					<form style="margin-bottom: 0px !important;" class="form-horizontal" method="POST" action="{{ URL::secure('user/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="content">
							<h4 class="title">Connexion</h4>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input type="text" placeholder="Nom d'utilisateur"
											name="email" id="email" value="{{ Input::old('email') }}"
											class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" placeholder="Mot de passe"
											id="password" name="password" class="form-control">
									</div>
								</div>
							</div>
						</div>
						<div class="foot">
							<div class="checkbox" style="text-align: left;">
								<label for="remember"> <input type="hidden" name="remember"
									value="0"> <input type="checkbox" name="remember" id="remember"
									value="1"> Rester connecté
								</label>
							</div>
							<a class="btn btn-default" href="{{{ URL::secure('/') }}}">Retour</a>
							<button class="btn btn-primary" data-dismiss="modal" type="submit">Connexion</button>
							<br />
							<a href="{{{ URL::secure('user/forgot') }}}">J'ai oublié mes identifiants</a>
						</div>
					</form>
				</div>
			</div>
			<div class="text-center out-links">
				<a href="http://www.ensemblesimplifions.be" target="_blank">eWBS 2015</a>
			</div>
		</div>
	</div>
	{{ HTML::script('js/behaviour/general.js') }}
	{{ HTML::script('js/bootstrap/dist/js/bootstrap.min.js') }}
	{{ HTML::script('js/jquery.ui/jquery-ui.js') }}
</body>
</html>
