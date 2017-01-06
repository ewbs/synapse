@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>{{ Lang::get ( 'admin/'.$modelInstance->getModelLabel().'/messages.title' ) }}</h2>
</div>

<div class="cl-mcont">

	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>{{ Lang::get ( 'admin/'.$modelInstance->getModelLabel().'/messages.restore.subtitle' ) }}</h3>
				</div>
				<div class="content">
					@if($modelInstance->canManage())
					<form id="restoreForm" class="form-horizontal" method="post" autocomplete="off" action="{{ $modelInstance->routePostRestore() }}">
						
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->
						
						<p>{{ Lang::get ('admin/'.$modelInstance->getModelLabel().'/messages.restore.detail', ['name'=>$modelInstance->name()]) }}</p>
						<!-- Form Actions -->
						<div class="form-group">
							<div class="controls">
								<a class="btn btn-cancel" href="{{ $modelInstance->routeGetTrash() }}">Annuler</a>
								<button type="submit" class="btn btn-primary">Confirmer la restauration</button>
							</div>
						</div>
						<!-- ./ form actions -->
					</form>
					@else
						@error(Lang::get('general.restore.noright'))
						<a class="btn btn-cancel" href="{{ $modelInstance->routeGetTrash() }}">Annuler</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@stop
