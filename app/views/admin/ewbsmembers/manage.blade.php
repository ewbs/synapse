<?php 
/**
 * @var EWBSMember $modelInstance
 * @var array $arrayOfUsers
 */
?>
@extends('site.layouts.container-fluid')
@section('title'){{ ($modelInstance ? 'Edition' : 'Création') }} d'un membre du personnel eWBS @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				{{-- CreateEdit Form --}}
				<form id="create-edit-ewbsmember-form" class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<!-- ./ csrf token -->
					
					<!-- Utilisateur -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="user">Utilisateur</label>
						<div class="col-md-10">
							@if ($modelInstance==null)
							<select class="select2" name="user" id="user">
								@foreach($arrayOfUsers as $user)
								<option value="{{$user->id}}">{{$user->username}}</option>
								@endforeach
							</select>
							{{ $errors->first('user_id', '<span class="help-inline">:message</span>') }}
							@else
							<input type="hidden" name="id" value="{{ $modelInstance->id }}" />{{-- ! Cet id est utilisé par les règles de validation (required_without) --}}
							<input disabled="disabled" class="form-control" type="text" value="{{{ $modelInstance->user->username }}}" placeholder="Fonction dans eWBS. Ex: Chargé de projet" />
							@endif
						</div>
					</div>
					<!-- ./ Utilisateur -->
					
					<!-- Nom, prénom, fonctione-->
					<div class="form-group {{{ $errors->has('lastname') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="lastname">Nom</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="lastname" id="lastname" value="{{{ Input::old('lastname', $modelInstance!=null ? $modelInstance->lastname : null) }}}" placeholder="Nom de famille" />
							{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<div class="form-group {{{ $errors->has('firstname') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="firstname">Prénom</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="firstname" id="firstname" value="{{{ Input::old('firstname',  $modelInstance!=null ? $modelInstance->firstname : null) }}}" placeholder="Prénom" />
							{{ $errors->first('firstname', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<div class="form-group {{{ $errors->has('jobtitle') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="jobtitle">Fonction</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="jobtitle" id="jobtitle" value="{{{ Input::old('jobtitle',  $modelInstance!=null ? $modelInstance->jobtitle : null) }}}" placeholder="Fonction dans eWBS. Ex: Chargé de projet" />
							{{ $errors->first('jobtitle', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ volume -->
					
					<!-- Actions -->
					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<a class="btn btn-cancel" href="{{ $model->routeGetIndex() }}">{{Lang::get('button.cancel')}}</a>
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
