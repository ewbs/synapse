@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') {{{ Lang::get('user/user.forgot_password') }}} ::
@parent @stop {{-- Content --}} @section('content')
<div class="page-header">
	<h1>Redéfinition du mot de passe</h1>
</div>
{{ Confide::makeResetPasswordForm($token)->render() }} @stop
