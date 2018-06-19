@extends('site.layouts.container')
@section('title'){{ Lang::get('user/user.new_password') }} @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
			{{ Confide::makeResetPasswordForm($token)->render() }} @stop
			</div>
		</div>
	</div>
</div>
@stop
