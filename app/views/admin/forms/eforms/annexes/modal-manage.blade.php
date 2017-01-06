<?php
/**
 * Modale le formulaire de création et édition d'un lien entre un eform et une annexe.
 * Générée côté serveur et chargée via le clic sur un élément de classe servermodal.
 * 
 * @var Eform $eform
 * @var AnnexeEform $annexe_eform
 * @var array $aAnnexes
 */
$aPiecesStates = DemarchePieceState::all ();
$annexe_id = Input::old('annexe_id', $annexe_eform ? $annexe_eform->annexe_id : '');
$current_state = Input::old('current_state', $annexe_eform ? $annexe_eform->current_state_id : '');
$next_state = Input::old('next_state', $annexe_eform ? $annexe_eform->next_state_id : '');
?>
<div class="modal fade noAuto colored-header" id="servermodal" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form class="form-horizontal" autocomplete="off" action="{{ $annexe_eform ? route('eformsAnnexesPostEdit', [$eform->id, $annexe_eform->id]) : route('eformsAnnexesPostCreate', [$eform->id]) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="servermodal-title">
					{{ ($annexe_eform ? 'Edition' : 'Création') }} d'une annexe
					<span></span></h3>
				</div>
				<div class="modal-body">
					
					<div class="form-group {{{ $errors->has('annexe_id') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="annexe_id">Annexe</label>
						<div class="col-md-10">
							<select class="form-control select2" name="annexe_id" id="annexe_id">
								@if(!$annexe_id)<option></option>@endif
								@foreach($aAnnexes as $item)
								<option value="{{ $item->id }}"{{ $annexe_id==$item->id ?' selected':'' }}>{{ $item->title }}</option>
								@endforeach
							</select>
							{{ $errors->first('annexe_id', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="current_state">Etat courant</label>
						<div class="col-md-10">
							<select class="form-control" name="current_state" id="current_state">
								<option></option>
								@foreach($aPiecesStates as $item)
								<option value="{{ $item->id }}"{{ $current_state==$item->id ?' selected':'' }}>{{ $item->code }} : {{ $item->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="next_state">Etat suivant</label>
						<div class="col-md-10">
							<select class="form-control" name="next_state" id="next_state">
								<option></option>
								@foreach($aPiecesStates as $item)
								<option value="{{ $item->id }}"{{ $next_state==$item->id ?' selected':'' }}>{{ $item->code }} : {{ $item->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<div class="form-group {{{ $errors->has('comment') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="comment">Commentaire</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="comment" id="comment">{{{ Input::old('comment', null) }}}</textarea>
							{{ $errors->first('comment', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="action" value="save" class="btn btn-primary">{{ ($annexe_eform ? 'Modifier' : 'Ajouter') }} l'annexe</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">{{Lang::get('button.cancel')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>