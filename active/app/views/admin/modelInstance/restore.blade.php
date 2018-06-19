@extends('site.layouts.container-fluid')
@section('title'){{ Lang::get ( 'admin/'.$modelInstance->getModelLabel().'/messages.title' ) }} @stop
@section('content')
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
							<a class="btn btn-cancel" href="{{ $modelInstance->routeGetTrash() }}">{{Lang::get('button.cancel')}}</a>
							<button type="submit" class="btn btn-primary">Confirmer la restauration</button>
						</div>
					</div>
					<!-- ./ form actions -->
				</form>
				@else
					@error(Lang::get('general.restore.noright'))
					<a class="btn btn-cancel" href="{{ $modelInstance->routeGetTrash() }}">{{Lang::get('button.cancel')}}</a>
				@endif
			</div>
		</div>
	</div>
</div>
@stop
