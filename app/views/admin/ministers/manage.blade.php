<?php 
/**
 * @var Minister $modelInstance
 */
?>
@extends('site.layouts.container-fluid')
@section('title'){{ ($modelInstance ? 'Edition' : 'Création') }} d'un ministre @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{ $modelInstance ? $modelInstance->routePostEdit() :$model->routePostCreate() }}">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					
					<!-- Prénom -->
					<div
						class="form-group {{{ $errors->has('firstname') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="firstname">Prénom</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="firstname" id="firstname" value="{{{ Input::old('firstname', $modelInstance ? $modelInstance->firstname : null) }}}"/>
							{{ $errors->first('firstname', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Prénom -->
					
					<!-- Nom -->
					<div
						class="form-group {{{ $errors->has('lastname') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="astname">Nom</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="lastname" id="lastname" value="{{{ Input::old('lastname', $modelInstance ? $modelInstance->lastname : null) }}}"/>
							{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Nom -->
					
					<!-- Actions -->
					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<a class="btn btn-cancel" href="{{ $modelInstance ? $modelInstance->routeGetView() : $model->routeGetIndex() }}">{{Lang::get('button.cancel')}}</a>
							<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
						</div>
					</div>
					<!-- ./ form actions -->
				</form>
			</div>
		</div>
	</div>
</div>
@stop
