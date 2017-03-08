<?
/**
 * @param User $modelInstance
 * 
 */
?>
@extends('site.layouts.container-fluid')
@section('title'){{$modelInstance?'Edition':'Création'}} d'un utilisateur @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				
				<form class="form-horizontal" method="post" autocomplete="off" action="{{$modelInstance ? $modelInstance->routePostEdit() : $model->routePostCreate()}}">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					
					<!-- username -->
					<div class="form-group {{{ $errors->has('username') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="username">Nom d'utilisateur</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="username" id="username" value="{{{ Input::old('username', $modelInstance ? $modelInstance->username : null) }}}" />
							{{ $errors->first('username', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ username -->
					
					<!-- Email -->
					<div class="form-group {{{ $errors->has('email') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="email">E-mail</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email', $modelInstance ? $modelInstance->email : null) }}}" />
							{{ $errors->first('email', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ email -->
					
					<!-- Password -->
					<div class="form-group {{{ $errors->has('password') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="password">Mot de passe</label>
						<div class="col-md-10">
							<input class="form-control" type="password" name="password" id="password" value="" />
							{{ $errors->first('password', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ password -->
					
					<!-- Password Confirm -->
					<div class="form-group {{{ $errors->has('password_confirmation') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="password_confirmation">Confirmation</label>
						<div class="col-md-10">
							<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
							{{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ password confirm -->
					
					<!-- Activation Status -->
					<div class="form-group {{{ $errors->has('activated') || $errors->has('confirmed') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="confirmed">Actif ?</label>
						<div class="col-md-6">
							@if(!$modelInstance)
							<select class="form-control" name="confirmed" id="confirmed">
								<option value="1" {{{ (Input::old('confirmed', 0) === 1 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
								<option value="0" {{{ (Input::old('confirmed', 0) === 0 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
							</select>
							@else
							<select class="form-control" {{{ ($modelInstance->id === Confide::user()->id ? ' disabled="disabled"' : '') }}} name="confirmed" id="confirmed">
								<option value="1" {{{ ( $modelInstance->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
								<option value="0" {{{ (!$modelInstance->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
							</select>
							@endif
							{{ $errors->first('confirmed', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ activation status -->
					
					<!-- Roles -->
					<?php $aSelectedRoles = Input::old('roles', ($modelInstance ? $modelInstance->currentRoleIds(): [])); ?>
					<div class="form-group {{{ $errors->has('roles') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="roles">Roles</label>
						<div class="col-md-6">
							<select class="form-control" name="roles[]" id="roles[]" multiple>
							@foreach ($roles as $role)
								@if (!$modelInstance)
								<option value="{{{ $role->id }}}" {{{ ( in_array($role->id, $aSelectedRoles) ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
								@else
								<option value="{{{ $role->id }}}" {{{ ( array_search($role->id, $aSelectedRoles) !== false && array_search($role->id, $aSelectedRoles) >= 0 ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
								@endif
							@endforeach
							</select>
						</div>
					</div>
					<!-- ./ Roles -->
					
					<?php $aSelectedAdministrations = Input::old('administrations', ($modelInstance ? $modelInstance->currentAdministrationIds():[])); ?>
					<div class="form-group {{{ $errors->has('administrations') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="roles">Restrictions d'accès par Administration</label>
						<div class="col-md-6">
							<select class="form-control" name="administrations[]" id="administrations[]" multiple>
							@foreach ($administrations as $administration)
								@if (!$modelInstance)
								<option value="{{{ $administration->id }}}" {{{ ( in_array($administration->id, $aSelectedAdministrations) ? ' selected="selected"' : '') }}}>
									{{{ $administration->name}}}
								</option>
								@else
								<option value="{{{ $administration->id }}}" {{{ ( array_search($administration->id, $aSelectedAdministrations) !== false && array_search($role->id, $aSelectedAdministrations) >= 0 ? ' selected="selected"' : '') }}}>
									{{{ $administration->name }}}
								</option>
								@endif
								@endforeach
							</select>
						</div>
						<span class="help-inline">Aucune sélection = pas de restrictions</span>
					</div>
					
					<!-- Form Actions -->
					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<a class="btn btn-cancel" href="{{route('usersGetIndex')}}">{{Lang::get('button.cancel')}}</a>
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
