@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Contactez eWBS @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>Mes infos</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>Modifier vos coordonnées</h3>
				</div>
				<div clas="content">
					<form class="form-horizontal" method="post"
						action="{{ URL::secure('user/' . $user->id . '/edit') }}"
						autocomplete="off">
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->

						<!-- avatar -->
						<div class="form-group">
							<label class="col-md-2 control-label">Avatar</label>
								<div class="col-md-10">
									{{Gravatarer::make( ['email' => Auth::user()->email, 'size' => 80, 'secured' => true] )->html( ['class' => 'avatar'] )}}
									<span>
										Votre avatar est chargé depuis le service <a href="https://fr.gravatar.com/" target="_blank">Gravatar</a>. Utilisez ce service pour personnaliser votre avatar sur l'ensemble des outils de eWBS.
									</span>
								</div>
							</li>
						</div>
						<!-- ./avatar -->

						<!-- username -->
						<div
							class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
							<label class="col-md-2 control-label" for="username">Nom
								d'utilisateur</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="username"
									id="username"
									value="{{{ Input::old('username', $user->username) }}}" /> {{
								$errors->first('username', '<span class="help-inline">:message</span>')
								}}
							</div>
						</div>
						<!-- ./ username -->

						<!-- Email -->
						<div
							class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
							<label class="col-md-2 control-label" for="email">E-mail</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="email" id="email"
									value="{{{ Input::old('email', $user->email) }}}" /> {{
								$errors->first('email', '<span class="help-inline">:message</span>')
								}}
							</div>
						</div>
						<!-- ./ email -->

						<!-- Password -->
						<div
							class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
							<label class="col-md-2 control-label" for="password">Mot de passe</label>
							<div class="col-md-10">
								<input class="form-control" type="password" name="password"
									id="password" value="" /> {{ $errors->first('password', '<span
									class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ password -->

						<!-- Password Confirm -->
						<div
							class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
							<label class="col-md-2 control-label" for="password_confirmation">Confirmation</label>
							<div class="col-md-10">
								<input class="form-control" type="password"
									name="password_confirmation" id="password_confirmation"
									value="" /> {{ $errors->first('password_confirmation', '<span
									class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ password confirm -->

						<!-- Form Actions -->
						<div class="form-group">
							<div class="col-md-offset-2 col-md-10">
								<button type="submit" class="btn btn-success">Confirmer les
									modifications</button>
							</div>
						</div>
						<!-- ./ form actions -->
					</form>
				</div>
			</div>
		</div>
	</div>

</div>
@stop
