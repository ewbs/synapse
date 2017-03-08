@extends('site.layouts.container-fluid')
@section('title'){{ ($rate ? 'Edition' : 'Création') }} d'un tarif @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				{{-- AVERTISSEMENTS --}} @if ($rate)
				@warning('La modification du tarif est immédiate et impacte toutes les données de Synapse (excepté les analyses SCM Light déjà générées).')
				@endif {{-- Create-Edit Rate Form --}}
				<form id="create-edit-rate-form" class="form-horizontal" method="post" autocomplete="off" action="{{ ($rate) ? $rate->routePostEdit() : $model->routePostCreate() }}">
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<!-- ./ csrf token -->
					
					<!-- nom -->
					<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Nom du tarif</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $rate ? $rate->name : null) }}}" />
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ nom -->
					
					<!-- cout -->
					<div class="form-group {{{ $errors->has('hour_rate') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="hour_rate">Coût horaire</label>
						<div class="col-md-10">
							<div class="input-group">
								<input class="form-control decimalNumber" type="text" name="hour_rate" id="hour_rate" value="{{{ Input::old('hour_rate', $rate ? NumberHelper::decimalFormat($rate->hour_rate) : '0') }}}" placeholder="Indiquez un montant en euros" />
									<span class="input-group-addon">€</span>
							</div>
							{{ $errors->first('hour_rate', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ cout -->
					<?php
					$selectedWho=null;
					if (Input::old ( 'who' )) $selectedWho=Input::old ( 'who' );
					elseif ($rate) $selectedWho=$rate->who;
					?>
					<!-- public -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="who">Appliqué à</label>
						<div class="col-md-10">
							<select class="select2" name="who" id="who">
								<option value="citizen"{{ $selectedWho=='citizen' ? ' selected': '' }}>Usager</option>
								<option value="administration"{{ $selectedWho=='administration' ? ' selected': '' }}>Administration</option>
							</select>
						</div>
					</div>
					<!-- ./ public -->
					
					<!-- description -->
					<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Description</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $rate ? $rate->description : null) }}}</textarea>
							@optional
							{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ description -->
					
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
