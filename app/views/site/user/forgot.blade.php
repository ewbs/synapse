@extends('site.layouts.container')
@section('title')Récupérer mon accès à Synapse @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<form method="POST" action="{{route('userPostForgotPassword')}}" accept-charset="UTF-8">
					<input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
					<div class="form-group">
						<label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
						<div class="input-append input-group">
							<input class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email') }}}">
							<span class="input-group-btn">
								<input class="btn btn-primary" type="submit" value="{{{ Lang::get('confide::confide.forgot.submit') }}}">
							</span>
						</div>
					</div>
					@if (Session::get('error'))
					<div class="alert alert-error alert-danger">{{{ Session::get('error')}}}</div>
					@endif @if (Session::get('notice'))
					<div class="alert alert-success">{{{ Session::get('notice') }}}</div>
					@endif
				</form>
			</div>
		</div>
	</div>
</div>
@stop
