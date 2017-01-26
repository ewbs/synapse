@extends('site.layouts.base')

@section('title')Login @stop

@section('bodyclass')texture @stop

@section('wrapper')
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
@stop