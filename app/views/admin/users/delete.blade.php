@extends('site.layouts.container-fluid')
@section('title')Suppression d'un utilisateur @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				{{-- Delete User Form --}}
				<form id="deleteForm" class="form-horizontal" method="post"
					action="@if (isset($user)){{ URL::secure('admin/users/' . $user->id . '/delete') }}@endif"
					autocomplete="off">
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<!-- ./ csrf token -->

					<p>
						Vous allez supprimer l'utilisateur <strong>{{ $user->username }}</strong>.
						Cette opération est irréversible.
					</p>

					<!-- Form Actions -->
					<div class="form-group">
						<div class="controls">
							<a class="btn btn-cancel"
								href="{{{ URL::secure('admin/users') }}}">{{Lang::get('button.cancel')}}</a>
							<button type="submit" class="btn btn-danger">Confirmer la
								suppression</button>
						</div>
					</div>
					<!-- ./ form actions -->
				</form>
			</div>
		</div>
	</div>
</div>
@stop
