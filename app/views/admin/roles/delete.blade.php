@extends('site.layouts.container-fluid')
@section('title')Suppression d'un rôle @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
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
							<button type="submit" class="btn btn-danger">Confirmer la suppression</button>
						</div>
					</div>
					<!-- ./ form actions -->
				</form>
			</div>
		</div>
	</div>
</div>
@stop