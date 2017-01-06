@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>Utilisateurs & Rôles</h2>
</div>

<div class="cl-mcont">

	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>Suppression d'un utilisateur</h3>
				</div>
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
									href="{{{ URL::secure('admin/users') }}}">Annuler</a>
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

</div>

@stop
