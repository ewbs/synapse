@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>Récupérer mon accès à Synapse</h2>
</div>

<div class="cl-mcont">
    <?php /* {{ Confide::makeForgotPasswordForm() }} */ ?>
    
    <form method="POST"
		action="{{ URL::to('/users/forgot_password',array(), true) }}"
		accept-charset="UTF-8">
		<input type="hidden" name="_token" value="{{{ Session::getToken() }}}">

		<div class="form-group">
			<label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
			<div class="input-append input-group">
				<input class="form-control"
					placeholder="{{{ Lang::get('confide::confide.e_mail') }}}"
					type="text" name="email" id="email"
					value="{{{ Input::old('email') }}}"> <span class="input-group-btn">
					<input class="btn btn-default" type="submit"
					value="{{{ Lang::get('confide::confide.forgot.submit') }}}">
				</span>
			</div>
		</div>

		@if (Session::get('error'))
		<div class="alert alert-error alert-danger">{{{ Session::get('error')
			}}}</div>
		@endif @if (Session::get('notice'))
		<div class="alert">{{{ Session::get('notice') }}}</div>
		@endif
	</form>

</div>

@stop
