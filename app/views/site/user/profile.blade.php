@extends('site.layouts.container-fluid')
@section('title') {{ Lang::get('user/user.profile') }} @stop
@section('content')
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th>Username</th>
			<th>Signed Up</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{{$user->id}}}</td>
			<td>{{{$user->username}}}</td>
			<td>{{{$user->joined()}}}</td>
		</tr>
	</tbody>
</table>
@stop
