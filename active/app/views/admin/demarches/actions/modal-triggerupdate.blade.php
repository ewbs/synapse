<?php 
/**
 * Modale permettant de mettre à jour des actions
 * @var Demarche $demarche
 * @var array $aActions
 * @var string $componentType
 * @var int $componentId
 */
if($componentType=='piece') {
	$componentname=DemarchePiece::withTrashed()->find($componentId, ['name'])->name;
}
elseif($componentType=='task') {
	$componentname=DemarcheTask::withTrashed()->find($componentId, ['name'])->name;
}
elseif($componentType=='eform') {
	$componentname=Eform::withTrashed()->find($componentId)->name();
}
?>
<div class="modal fade noAuto colored-header" id="servermodal" tabindex="-1" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form class="form-horizontal" method="post" autocomplete="off" action="{{ route('demarchesActionsPostTriggerUpdate', $demarche->id) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<input type="hidden" name="componentType" value="{{$componentType}}"/>
				<input type="hidden" name="componentId" value="{{$componentId}}"/>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="servermodal-title">{{ Lang::get('admin/demarches/messages.action.modal.title') }}<span></span></h3>
				</div>
				<div class="modal-body">
				{{ Lang::choice('admin/demarches/messages.action.modal.intro.update.'.$componentType, count($aActions), ['name'=>$componentname]) }}
				@foreach($aActions as $action)
					<?php 
					$actionRevision=$action->getLastRevision();
					$state=Input::old('state'.$action->id, $actionRevision ? $actionRevision->state : null);
					?>
					
					<fieldset class="action">
						<legend>{{ $action->name }}</legend>
						
						<!-- Description -->
						<div class="form-group {{{ $errors->has('description'.$action->id) ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="description{{$action->id}}">Description</label>
							<div class="col-md-10">
								<textarea class="form-control" name="description{{$action->id}}" placeholder="Description" rows="6">{{{ Input::old('description', $actionRevision ? $actionRevision->description : null) }}}</textarea>
								{{ $errors->first('description'.$action->id, '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ Description -->
					
						<!-- State -->
						<div class="form-group {{{ $errors->has('state'.$action->id) ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="state{{$action->id}}">Etat</label>
							<div class="col-md-10">
								<select class="form-control" name="state{{$action->id}}">
									@foreach(EwbsActionRevision::states() as $s)
									<option value="{{$s}}"{{ $s==$state ? ' selected': '' }}>{{ Lang::get( "admin/ewbsactions/messages.state.{$s}") }}</option>
									@endforeach
								</select>
								{{ $errors->first('state'.$action->id, '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ State -->
						
					</fieldset>
				@endforeach
				</div>
				<div class="modal-footer">
					<button type="submit" name="action" value="cancel" class="btn btn-default">Non merci</button>
					<button type="submit" name="action" value="save" class="btn btn-primary">{{ Lang::choice('admin/demarches/messages.action.modal.update', count($aActions)) }}</button>
				</div>
			</form>
		</div>
	</div>
</div>