@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-clipboard"></span> Catalogue des pièces justificatives</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>{{ ($piece ? 'Edition' : 'Création') }} d'une pièce justificative</h3>
				</div>
				<div class="content">
					{{-- AVERTISSEMENTS --}} @if ($piece)
					@warning('La modification de la pièce est immédiate et impacte toutes les données de Synapse (excepté les analyses SCM Light déjà générées).')
					@endif {{-- Create-Edit Piece Form --}}
					<form id="manage-piece-form" class="form-horizontal" method="post" autocomplete="off" action="{{ ($piece) ? $piece->routePostEdit() : $model->routePostCreate() }}">
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->
						
						<!-- nom -->
						<div
							class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="name">Nom de la pièce</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $piece!=null ? $piece->name : null) }}}" />
								{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ nom -->
						
						<!-- cout pour l'administration -->
						<div class="form-group {{{ $errors->has('cost_administration_currency') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="cost_administration_currency">Coût pour l'administration</label>
							<div class="col-md-10">
								<div class="input-group">
									<input class="form-control decimalNumber" type="text" name="cost_administration_currency" id="cost_administration_currency"
										value="{{{ Input::old('cost_administration_currency', $piece!=null ? NumberHelper::decimalFormat($piece->cost_administration_currency) : '0') }}}" placeholder="Indiquez un montant en euros" />
										<span class="input-group-addon">€</span>
								</div>
								{{ $errors->first('cost_administration_currency', '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ cout pour l'administration -->
						
						<!-- cout pour l'usager -->
						<div class="form-group {{{ $errors->has('cost_citizen_currency') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="cost_citizen_currency">Coût pour l'usager</label>
							<div class="col-md-10">
								<div class="input-group">
									<input class="form-control decimalNumber" type="text" name="cost_citizen_currency" id="cost_citizen_currency"
										value="{{{ Input::old('cost_citizen_currency', $piece!=null ? NumberHelper::decimalFormat($piece->cost_citizen_currency) : '0') }}}" placeholder="Indiquez un montant en euros" />
										<span class="input-group-addon">€</span>
								</div>
								{{ $errors->first('cost_citizen_currency', '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ cout pour l'usager -->
						
						<!-- type -->
						<?php /*
						// recherche de l'élément à selectionner
						$selectedType = 0;
						if ($piece) {
							$selectedType = $piece->type_id;
						}
						if (Input::old ( 'type' )) {
							$selectedType = Input::old ( 'type' );
						}
						?>
						<div class="form-group">
							<label class="col-md-2 control-label" for="type">Nature</label>
							<div class="col-md-10">
								<select class="select2" name="type" id="type">
									@foreach($types as $type)
									<option value="{{$type->id}}" {{{$type->id == $selectedType? 'selected': ''}}}>{{$type->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<!-- ./ public --> */ ?>
						
						<!-- description -->
						<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="name">Description</label>
							<div class="col-md-10">
								<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $piece!=null ? $piece->description : null) }}}</textarea>
								{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
								<small class="pull-right">(facultatif)</small>
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
</div>
@stop
