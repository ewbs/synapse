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
					<h3>Suppression d'un rôle</h3>
				</div>
				<div class="content">

					{{-- Delete Post Form --}}
					<form id="deleteForm" class="form-horizontal" method="post"
						action="@if (isset($role)){{ URL::secure('admin/roles/' . $role->id . '/delete') }}@endif"
						autocomplete="off">
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->

						<p>
							Vous allez supprimer le rôle <strong>{{ $role->name }}</strong>.
							Cette opération est irréversible.
						</p>

						<!-- Form Actions -->
						<div class="form-group">
							<div class="controls">
								<a class="btn btn-cancel"
									href="{{{ URL::secure('admin/roles') }}}">Annuler</a>
								<button type="submit" class="btn btn-danger">Confirmer la
									suppression</button>
							</div>
						</div>
						<!-- ./ form actions -->
					</form>
					@stop