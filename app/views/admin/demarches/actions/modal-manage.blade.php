<?php
/**
 *
 *
 * @var Demarche $demarche
 * @var EwbsAction $action
 * @var array $aTaxonomy
 * @var array $selectedTags
 * @var string $returnTo
 */

// TODO: on a du code ici qui aurait plus sa place dans un controller

$edit=($action && $action->id);
$revision=$edit ? $action->getLastRevision() : null;

$state=Input::old('state', $revision ? $revision->state : null);
$priority=Input::old('priority', $revision ? $revision->priority : EwbsActionRevision::$PRIORITY_NORMAL);

$piecetask=Input::old('piecetask');
if(!$piecetask && $action) {
	if($action->demarche_piece_id ) {
		$piecetask='piece'.$action->demarche_piece_id;
	}
	elseif($action->demarche_task_id) {
		$piecetask='task'.$action->demarche_task_id;
	}
	elseif($action->demarche_id && $action->eform_id) {
		$piecetask='eform'.$action->eform_id;
	}
}

$piecetasktype=(!$piecetask) ? null : (strpos($piecetask, 'piece')===0 ? 'piece' : (strpos($piecetask, 'task')===0 ? 'task' : 'eform'));
$piecetaskid=(!$piecetask) ? null : str_replace($piecetasktype, '', $piecetask);
$piecetaskname=(!$piecetask) ? null : ($piecetasktype=='piece' ? DemarchePiece::withTrashed()->find($piecetaskid,['name'])->name : ($piecetasktype=='task' ? DemarcheTask::withTrashed()->find($piecetaskid,['name'])->name : Eform::withTrashed()->find($piecetaskid)->name() ));

$fromTriggerUpdate=(isset($fromTriggerUpdate) && $fromTriggerUpdate); // Dans ce cas c'est qu'on vient de la méthode getActionsTriggerUpdate() => créer une action depuis une pièce/tâche
if(!$edit && !$fromTriggerUpdate) {
	$piecestasks=array();
	$piecestasks['eform']=$demarche->getLastRevisionEforms();
	$piecestasks['piece']=$demarche->pieces()->getResults();
	$piecestasks['task']=$demarche->tasks()->getResults();
}

?>
<div class="modal fade noAuto colored-header" id="servermodal" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form class="form-horizontal" method="post" autocomplete="off" action="{{ $edit ? route('demarchesActionsPostEdit', [$demarche->id, $action->id]) :route('demarchesActionsPostCreate', $demarche->id) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<input type="hidden" name="fromTriggerUpdate" value="{{ $fromTriggerUpdate }}" />
				<div class="modal-header">
					<button class="close" aria-label="Fermer"
					@if($fromTriggerUpdate)
					type="submit" name="action" value="cancel"
					@else
					type="button" data-dismiss="modal"
					@endif
					><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="servermodal-title">
						@if($fromTriggerUpdate)
						{{ Lang::get('admin/demarches/messages.action.modal.title') }}
						@else
						{{ ($edit ? 'Edition' : 'Création') }} d'une action
						@endif
						<span></span>
					</h3>
				</div>
				<div class="modal-body">
					@if($fromTriggerUpdate)
					{{ Lang::get('admin/demarches/messages.action.modal.intro.create.'.$piecetasktype, ['name'=>$piecetaskname]) }}
					@endif
							
					<!-- Nom -->
					<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Nom</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="name" value="{{{ Input::old('name', $edit ? $action->name : null) }}}" placeholder="Nom" />
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Nom -->
					
					<!--Formulaire, pièce ou tâche -->
					@if($fromTriggerUpdate)
					<input type="hidden" name="piecetask" value="{{$piecetask}}"/>
					@elseif(!$edit || $piecetasktype)
					<div class="form-group {{{ $errors->has('piecetask') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="piecetask">
						@if($edit)
						{{Lang::get('admin/demarches/messages.'.$piecetasktype.'.'.$piecetasktype)}}
						@else
						{{Lang::get('admin/demarches/messages.eform.eform')}}<br/>/ {{Lang::get('admin/demarches/messages.piece.piece')}}<br/>/ {{Lang::get('admin/demarches/messages.task.task')}}
						@endif
						</label>
						<div class="col-md-10">
							<select name="piecetask" class="select2">
								@if($edit)
									<option value="{{$piecetask}}" selected>{{$piecetaskname}}</option>
								@else
									<option></option>
									@foreach($piecestasks as $type=>$items)
										<?php $selected = false; ?>
										<optgroup label="{{Lang::get("admin/demarches/messages.{$type}.{$type}")}}">
										@foreach($items as $item)
											
											@if($type=='eform')
											<?php
											$component=$item->$type;
											$value = $type . $component->id;
											if($value==$piecetask) $selected=true;
											?>
											<option value="{{$value}}"{{ $value==$piecetask ? ' selected': '' }}>{{ $component->name()}}</option>
											
											@else
											<?php
											$value = $type . $item->id;
											if($value==$piecetask) $selected=true;
											?>
											<option value="{{$value}}"{{ $value==$piecetask ? ' selected': '' }}>{{ $item->name()}}</option>
											@endif
											
										@endforeach
										@if($edit && !$selected && $piecetasktype==$type) {{-- Réinclure une option en + avec la pièce, tâche ou formulaire courant, dans le cas où celle-ci ne serait pas dans la liste constituée (supprimée au niveau de la démarche-pièce|tâche|formulaire) --}}
											<option value="{{$type}}{{$piecetaskid}}" selected>{{$piecetaskname}}</option>
										@endif
										</optgroup>
									@endforeach
								@endif
							</select>
							@optional
							{{ $errors->first('piecetask', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Formulaire, pièce ou tâche -->
					@endif
					
					<!-- Description -->
					<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="description">Description</label>
						<div class="col-md-10">
							<textarea class="form-control" name="description" placeholder="Description" rows="6">{{{ Input::old('description', $revision ? $revision->description : null) }}}</textarea>
							{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Description -->
					
					<!-- State -->
					@if ($edit)
					<div class="form-group {{{ $errors->has('state') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="state">Etat</label>
						<div class="col-md-10">
							<select class="form-control" name="state"{{($action->sub && $action->eachSub()->count()>0)?' disabled title="L\'état est automatiquement défini par les sous-actions"':''}}>
								@foreach(EwbsActionRevision::states() as $s)
								<option value="{{$s}}"{{ $s==$state ? ' selected': '' }}>{{ Lang::get( "admin/ewbsactions/messages.state.{$s}") }}</option>
								@endforeach
							</select>
							{{ $errors->first('state', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					@else
					<input type="hidden" name="state" value="{{EwbsActionRevision::$STATE_TODO}}"/>
					@endif
					<!-- ./ State -->
					
					{{-- Priority --}}
					<div class="form-group {{{ $errors->has('priority') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="priority">Priorité</label>
						<div class="col-md-10">
							<select class="form-control" name="priority"{{$loggedUser->can('ewbsaction_prioritize')?' ':' disabled'}}>
							@foreach(EwbsActionRevision::priorities() as $p)
								<option value="{{$p}}"{{ $p==$priority ? ' selected': '' }}>{{ Lang::get( "admin/ewbsactions/messages.priority.{$p}") }}</option>
							@endforeach
							</select>
							{{ $errors->first('priority', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					{{-- ./ Priority --}}

					{{-- Taxonomie --}}
					<div class="form-group">
						<label class="col-md-2 control-label" form="tags">Tags</label>
						<div class="col-md-10">
							<!-- tags -->
							<?php
							// recherche de l'élément à selectionner
							if (Input::old('tags'))
								$selectedTags = Input::old('tags'); //selectedTags est initialisé par le controller. Normalement on aura jamais de POST car c'est une modale qui sauve en ajax.
							?>
							<select class="form-control select2" name="tags[]" id="tags" multiple>
								@foreach($aTaxonomy as $category)
									<optgroup label="{{$category->name}}">
										@foreach($category->tags as $tag)
											<option value="{{$tag->id}}"{{ in_array($tag->id, $selectedTags) ? ' selected': '' }}>{{$tag->name}}</option>
										@endforeach
									</optgroup>
								@endforeach
							</select>
							@optional
						</div>
					</div>
					{{-- ./ Taxonomie --}}

					{{-- sous actions // ne s'affiche que si on a une action (donc pas dans le cas de la création d'une action --}}
					@if ($edit)
						<div class="form-group">
							<label class="col-md-2 control-label">Sous-actions</label>
							<div class="col-md-10">
								<div class="table-responsive">
									<table class="table table-hover datatable" data-ajaxurl="{{ route('ewbsactionsSubGetData', $action->id) }}?minimal=true">
										<thead>
											<tr>
												<th class="col-md-1">#</th>
												<th>Nom</th>
												<th class="col-md-1">Etat</th>
												<th class="col-md-1">Priorité</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<a class="btn btn-primary pull-right" href="{{route('ewbsactionsGetView',$action->id)#subactions}}">Gérer les sous-actions</a>
							</div>
						</div>
					@endif
				</div>
				<div class="modal-footer">
					@if($fromTriggerUpdate)
					<button class="btn btn-default" type="submit" name="action" value="cancel">Non merci</button>
					@else
					<button class="btn btn-default" type="button" data-dismiss="modal">{{Lang::get('button.cancel')}}</button>
					@endif
					<button type="submit" name="action" value="save" class="btn btn-primary">{{Lang::get('button.save')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>