<?php
/**
 * 
 * @var Demarche $modelInstance
 * @var EwbsAction $action
 */
$edit=($action && $action->id);
$revision=$edit ? $action->getLastRevision() : null;

$state=Input::old('state', $revision ? $revision->state : null);
$priority=Input::old('priority', $revision ? $revision->priority : EwbsActionRevision::$PRIORITY_NORMAL);

?>
<div class="modal fade noAuto colored-header" id="servermodal" tabindex="-1" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form class="form-horizontal" method="post" autocomplete="off" action="{{ $edit ? route('eformsActionsPostEdit', [$modelInstance->id, $action->id]) :route('eformsActionsPostCreate', $modelInstance->id) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<div class="modal-header">
					<button class="close" aria-label="Fermer" type="button" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="servermodal-title">
						{{ ($edit ? 'Edition' : 'Création') }} d'une action
						<span></span>
					</h3>
				</div>
				<div class="modal-body">
					<!-- Nom -->
					<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Nom</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="name" value="{{{ Input::old('name', $edit ? $action->name : null) }}}" placeholder="Nom" />
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Nom -->
					
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
							<select class="form-control" name="state">
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
					
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" type="button" data-dismiss="modal">{{Lang::get('button.cancel')}}</button>
					<button type="submit" name="action" value="save" class="btn btn-primary">{{Lang::get('button.save')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>