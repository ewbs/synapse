{{-- Modale permettant de mettre à jour l'état des idées et leur ajouter un commentaire --}}
<div class="modal fade noAuto colored-header" id="servermodal" tabindex="-1" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form class="form-horizontal" method="post" autocomplete="off" action="{{ route('demarchesIdeasPostTriggerUpdate', $demarche->id) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				
				<div class="modal-header">
					<button type="submit" name="action" value="cancel" class="close" aria-label="Fermer">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="servermodal">{{ Lang::get('admin/demarches/messages.idea.modalUpdate.title') }}<span></span></h3>
				</div>
				<div class="modal-body">
					@if ( $message = (isset($error) ? $error : Session::get('error')) )
					<div class="text-center">
						<div class="i-circle danger">
							<i class="fa fa-times"></i>
						</div>
						<h4>Ooops!</h4>
						<p>
							@if(is_array($message)) @foreach ($message as $m) {{ $m }}<br />
							@endforeach @else {{ $message }} @endif
						</p>
					</div>
					@endif
					<?php
						$ideaStates=IdeaState::all();
					?>
					{{ Lang::choice('admin/demarches/messages.idea.modalUpdate.intro', count($aIdeas)) }}
					@foreach($aIdeas as $idea)
					<?php
					$currentIdeaState = $idea->getLastStateModification()->ideaState;
					?>
					<fieldset class="idea">
						<legend>
							<span class="text-primary">{{DateHelper::year($idea->created_at)}}-{{$idea->id}}</span>
							{{ $idea->name }}
						</legend>
						<!-- ./ Etat -->
						<div class="form-group {{{ $errors->has('state'.$idea->id) ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="state{{$idea->id}}">Nouvel état</label>
							<div class="col-md-10">
								<select class="form-control" name="state{{$idea->id}}" id="state{{$idea->id}}"> 
									@foreach ($ideaStates as $state)
									<option value="{{$state->id}}" {{{$currentIdeaState->id == $state->id ? 'selected': ''}}}>{{Lang::get('admin/ideas/states.'.$state->name)}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<!-- Etat -->
						
						<!-- Commentaire -->
						<div class="form-group {{{ $errors->has('comment'.$idea->id) ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="comment{{$idea->id}}">Commentaire</label>
							<div class="col-md-10">
								<textarea class="form-control" name="comment{{$idea->id}}" id="comment{{$idea->id}}" placeholder="Commentaire et/ou explicatif éventuel du changement d'état" rows="4">{{{ Input::old('comment'.$idea->id, null) }}}</textarea>
								{{ $errors->first('comment'.$idea->id, '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ Commentaire -->
					</fieldset>
					@endforeach
				</div>
				<div class="modal-footer">
					<button type="submit" name="action" value="save" class="btn btn-primary">{{ Lang::choice('admin/demarches/messages.idea.modalUpdate.update', count($aIdeas)) }}</button>
					<button type="submit" name="action" value="cancel" class="btn btn-default">Non merci</button>
				</div>
			</form>
		</div>
	</div>
</div>