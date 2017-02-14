<?php
/**
 * Modale le formulaire de création et édition d'un lien entre une démarche et un eform.
 * Générée côté serveur et chargée via le clic sur un élément de classe servermodal.
 * 
 * @var Demarche $demarche
 * @var DemarcheEform $demarche_eform
 * @var array $aEforms
 * @var array aSuggestedEforms
 * @var Illuminate\Support\MessageBag $errors
 * @var array $states
 */
$eform_id = Input::old('eform_id', $demarche_eform ? $demarche_eform->eform_id : '');
?>
<div class="modal fade noAuto colored-header" id="servermodal" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form class="form-horizontal" autocomplete="off" action="{{ $demarche_eform ? route('demarchesEformsPostEdit', [$demarche->id, $demarche_eform->id]) : route('demarchesEformsPostCreate', [$demarche->id]) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="servermodal-title">
					{{ ($demarche_eform ? 'Edition' : 'Liaison') }} d'un formulaire
					<span></span></h3>
				</div>
				<div class="modal-body">
					
					<div class="form-group {{{ $errors->has('eform_id') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="eform_id">Formulaire</label>
						<div class="col-md-10">
							@if ($aSuggestedEforms)
								<select class="form-control select2" name="eform_id" id="eform_id" required>
									@if(!$eform_id)<option></option>@endif
									<optgroup label="Formulaires suggérés">
										@foreach($aSuggestedEforms as $item)
											<option value="{{ $item->id }}"{{ $eform_id==$item->id ?' selected':'' }} data-line2="{{$item->nostra_id?'Id Nostra '. ManageableModel::formatId($item->nostra_id):'-'}}" data-line3="{{$item->current_state_id||$item->next_state_id ? ('('.($item->current_state_id?$states[$item->current_state_id]->code:'?') .'->'. ($item->next_state_id?$states[$item->next_state_id]->code:'?').')') : ''}}">{{ $item->title }}</option>
										@endforeach
									</optgroup>
									@if(!$eform_id)<option></option>@endif
									<optgroup label="Autres formulaires">
										@foreach($aEforms as $item)
											<option value="{{ $item->id }}"{{ $eform_id==$item->id ?' selected':'' }} data-line2="{{$item->nostra_id?'Id Nostra '. ManageableModel::formatId($item->nostra_id):'-'}}" data-line3="{{$item->current_state_id||$item->next_state_id ? ('('.($item->current_state_id?$states[$item->current_state_id]->code:'?') .'->'. ($item->next_state_id?$states[$item->next_state_id]->code:'?').')') : ''}}">{{ $item->title }}</option>
										@endforeach
									</optgroup>
								</select>
							@else
								<select class="form-control select2" name="eform_id" id="eform_id" required>
									@if(!$eform_id)<option></option>@endif
									@foreach($aEforms as $item)
									<option value="{{ $item->id }}"{{ $eform_id==$item->id ?' selected':'' }} data-line2="{{$item->nostra_id?'Id Nostra '. ManageableModel::formatId($item->nostra_id):'-'}}" data-line3="{{$item->current_state_id||$item->next_state_id ? ('('.($item->current_state_id?$states[$item->current_state_id]->code:'?') .'->'. ($item->next_state_id?$states[$item->next_state_id]->code:'?').')') : ''}}">{{ $item->title }}</option>
									@endforeach
								</select>
							@endif
							{{ $errors->first('eform_id', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					
					<!-- //Note : pas demandé pour l'instant, mais on le prévoit tjs au cas où...
					 <div class="form-group {{{ $errors->has('comment') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="comment">Commentaire</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="comment" id="comment">{{{ Input::old('comment', null) }}}</textarea>
							{{ $errors->first('comment', '<span class="help-inline">:message</span>') }}
						</div>
					</div>-->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{Lang::get('button.cancel')}}</button>
					<button type="submit" name="action" value="save" class="btn btn-primary">{{Lang::get('button.save')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>