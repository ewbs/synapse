<?php
/**
 * Modale le formulaire de création et édition d'un lien entre une démarche et un composant.
 * Générée côté serveur et chargée via le clic sur un élément de classe servermodal.
 * 
 * @var Demarche $demarche
 * @var DemarcheComponent $demarche_component
 * @var DemarcheComponentRevision $revision
 * @var array $aLinks
 * @var array $states
 * @var string $action
 * @var boolean $restoring
 */
$componentId = Input::old('componentId', $action=='edit' ? $demarche_component->componentId() : '');
$name = Input::old('name', $action=='edit' ? $demarche_component->name : '');
?>
<div class="modal fade noAuto colored-header" id="servermodal" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form autocomplete="off" action="{{ $demarche_component->routeGetManage(['demarche'=>$demarche->id]) }}" method="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="servermodal-title">
					{{Lang::get("admin/demarches/messages.{$demarche_component->componentType()}.{$action}.title")}}
					<span></span></h3>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
					@if($action=='choose')
						<div class="form-group {{{ $errors->has('componentId') ? 'has-error' : '' }}}">
							<label>{{Lang::get("admin/demarches/messages.{$demarche_component->componentType()}.label")}}</label>
							<select class="select2" name="componentId">
								<option></option>
								@foreach ($aLinks as $link)
								<option{{ ($componentId==$link->id) ? ' selected=""':'' }} value="{{$link->id}}" data-line2="{{$link->description}}">{{$link->name}}</option>
								@endforeach
							</select>
							{{ $errors->first('componentId', '<span class="help-inline">:message</span>') }}
						</div>
						<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
							<label>Nom</label>
							<input class="form-control" type="text" name="name" value="{{$name}}" placeholder='{{{Lang::get("admin/demarches/messages.{$demarche_component->componentType()}.name")}}}'/>
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
						</div>
					@else
						@if($restoring)
							@warning('Cet élément était déjà lié à la démarche mais avait été supprimé, il est donc restauré avec ses anciennes valeurs par défaut.')
						@endif
						<input type="hidden" name="componentId" value="{{$componentId}}"/>
						@foreach ($aLinks as $link)
						@if($componentId==$link->id)
						<h4>{{$link->name}}</h4>
						<p>{{$link->description}}</p>
						@endif
						@endforeach
						
						<div class="row">
							<div class="col-md-12">
								<div class="form-group {{{ $errors->has('name') && !$restoring ? 'has-error' : '' }}}">
									<label>Nom</label>
									<input class="form-control" type="text" name="name" value="{{$name}}" placeholder='{{{Lang::get("admin/demarches/messages.{$demarche_component->componentType()}.name")}}}'/>
									@if(!$restoring)
									{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
									@endif
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group {{{ $errors->has('volume') ? 'has-error' : '' }}}">
									<label>Volume</label>
									<input class="form-control integerNumber" type="text" name="volume" id="component_volume" placeholder="Nombre de dossiers par an" value="{{Input::old('volume')!==NULL ? Input::old('volume') : $revision->volume}}"/>
									{{ $errors->first('volume', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group {{{ $errors->has('frequency') ? 'has-error' : '' }}}">
									<label>Fréquence</label>
									<input class="form-control integerNumber" type="text" name="frequency" id="component_frequency" placeholder="Fréquence à laquelle le dossier est demandé par an" value="{{Input::old('frequency')!==NULL ? Input::old('frequency') : $revision->frequency}}"/>
									{{ $errors->first('frequency', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group {{{ $errors->has('cost_administration_currency') ? 'has-error' : '' }}}">
									<label>Coût administration</label>
									<div class="input-group">
										<input class="form-control decimalNumber" type="text" name="cost_administration_currency" id="component_cost_administration_currency" placeholder="Indiquez un montant en euros" value="{{NumberHelper::decimalFormat(Input::old('cost_administration_currency')!==NULL ? Input::old('cost_administration_currency') : $revision->cost_administration_currency)}}"/>
										<span class="input-group-addon">€</span>
									</div>
									{{ $errors->first('cost_administration_currency', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group {{{ $errors->has('cost_citizen_currency') ? 'has-error' : '' }}}">
									<label>Coût usager</label>
									<div class="input-group">
										<input class="form-control decimalNumber" type="text" name="cost_citizen_currency" id="component_cost_citizen_currency" placeholder="Indiquez un montant en euros" value="{{NumberHelper::decimalFormat(Input::old('cost_citizen_currency')!==NULL ? Input::old('cost_citizen_currency') : $revision->cost_citizen_currency)}}"/>
										<span class="input-group-addon">€</span>
									</div>
									{{ $errors->first('cost_citizen_currency', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Gain potentiel administration</label>
									<div class="input-group">
										<input class="form-control decimalNumber" type="text" name="gain_potential_administration" id="component_gain_potential_administration" placeholder="Indiquez un montant en euros" disabled/>
										<span class="input-group-addon">€</span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Gain potentiel usager</label>
									<div class="input-group">
										<input class="form-control decimalNumber" type="text" name="gain_potential_citizen" id="component_gain_potential_citizen" placeholder="Indiquez un montant en euros" disabled/>
										<span class="input-group-addon">€</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group {{{ $errors->has('gain_real_administration') ? 'has-error' : '' }}}">
									<label>Gain effectif administration</label>
									<div class="input-group">
										<input class="form-control decimalNumber" type="text" name="gain_real_administration" id="component_gain_real_administration" placeholder="Indiquez un montant en euros" value="{{NumberHelper::decimalFormat(Input::old('gain_real_administration')!==NULL ? Input::old('gain_real_administration') : $revision->gain_real_administration)}}"/>
											<span class="input-group-addon">€</span>
									</div>
									{{ $errors->first('gain_real_administration', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group {{{ $errors->has('gain_real_citizen') ? 'has-error' : '' }}}">
									<label>Gain effectif usager</label>
									<div class="input-group">
										<input class="form-control decimalNumber" type="text" name="gain_real_citizen" id="component_gain_real_citizen" placeholder="Indiquez un montant en euros" value="{{NumberHelper::decimalFormat(Input::old('gain_real_citizen')!==NULL ? Input::old('gain_real_citizen') : $revision->gain_real_citizen)}}"/>
										<span class="input-group-addon">€</span>
									</div>
									{{ $errors->first('gain_real_citizen', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Etat courant</label>
									<select class="form-control" name="current_state_id">
										<option></option>
										@foreach($states as $item)
										<option value="{{ $item->id }}"{{$item->id == Input::old('current_state_id', $revision->current_state_id) ? ' selected':''}}>{{ $item->code }} : {{ $item->name }}</option>
										@endforeach
									</select>
									@optional
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Etat suivant</label>
									<select class="form-control" name="next_state_id">
										<option></option>
										@foreach($states as $item)
										<option value="{{ $item->id }}"{{$item->id == Input::old('next_state_id', $revision->next_state_id) ? ' selected':''}}>{{ $item->code }} : {{ $item->name }}</option>
										@endforeach
									</select>
									@optional
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Commentaire</label>
							<textarea class="form-control" name="comment">{{Input::old('comment')}}</textarea>
							@optional
						</div>
						@if($loggedUser->hasRole('admin'))
						<div class="row">
							<div class="col-md-6">
								<div class="form-group {{{ $errors->has('created_at') ? 'has-error' : '' }}}">
									<label>Date de création</label>
									<div class="input-group date datetimepicker" data-date-enddate="{{date('d/m/Y H:i')}}">
										<input type="text" class="form-control" name="created_at" value="{{Input::old('created_at')}}" readonly placeholder="Laisser vide pour date et heure courante"/>
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
									{{ $errors->first('created_at', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
						</div>
						@endif
					@endif
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{Lang::get('button.cancel')}}</button>
					<button type="submit" name="action" value="{{$action}}" class="btn btn-primary">
					{{Lang::get($action=='choose' ? 'button.next':'button.save')}}
					</button>
				</div>
			</form>
		</div>
	</div>
	<script>
	$(document).ready(function () {
		// Calcul du gain potentiel, nb : Le plugin jquery.number semble absorber l'événement change dans certains cas => focusout à la place
		$('body').on('focusout', $('#component_cost_administration_currency'), refreshPieceGainPotenialAdministration);
		$('body').on('focusout', $('#component_cost_citizen_currency'), refreshPieceGainPotenialCitizen);
		$('body').on('focusout', $('#component_volume'), refreshPieceGainPotenials);
		$('body').on('focusout', $('#component_frequency'), refreshPieceGainPotenials);
		
		function refreshPieceGainPotenials () {
			refreshPieceGainPotenialAdministration();
			refreshPieceGainPotenialCitizen();
		}
	
		function refreshPieceGainPotenialAdministration () {
			console.log('refreshPieceGainPotenialAdministration'+$('#component_cost_administration_currency').val());
			$('#component_gain_potential_administration').val( $('#component_volume').val() * $('#component_frequency').val() * $('#component_cost_administration_currency').val());
		}
		function refreshPieceGainPotenialCitizen () {
			console.log('refreshPieceGainPotenialCitizen'+$('#component_cost_citizen_currency').val());
			$('#component_gain_potential_citizen').val( $('#component_volume').val() * $('#component_frequency').val() * $('#component_cost_citizen_currency').val());
		}
	});
	</script>
</div>