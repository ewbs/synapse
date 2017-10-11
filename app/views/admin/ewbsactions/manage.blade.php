<?php
/**
 * Formulaire de création et édition d'une action.
 * 
 * @var EwbsAction $modelInstance
 * @var EwbsActionRevision $revision
 * @var Illuminate\Database\Eloquent\Collection $aTaxonomy
 * @var array $aSelectedTags
 * @var array $aExpertises
 * @var Illuminate\Database\Eloquent\Collection $aUsers
 */

$state=Input::old('state', $revision ? $revision->state : EwbsActionRevision::$STATE_TODO);
$name=Input::old('name', $modelInstance ? $modelInstance->name():null);
$priority=Input::old('priority', $revision ? $revision->priority : EwbsActionRevision::$PRIORITY_NORMAL);
$responsible_id=Input::old('responsible_id', $revision ? $revision->responsible_id : $loggedUser->id);
?>
@extends('site.layouts.container-fluid')
@section('title')Edition de l'action <em>{{ $modelInstance->name() }}</em> @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			{{-- CreateEdit Form --}}
			<form class="form-horizontal" method="post" autocomplete="off" action="{{ $modelInstance->routeGetEdit() }}">
				{{-- CSRF Token --}}
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				{{-- ./ csrf token --}}
				
				{{-- Name --}}
				<div class="form-group">
					<label class="col-md-2 control-label" for="state">Nom</label>
					<div class="col-md-10">
						<select class="form-control select2 {{ $errors->has('name') ? 'has-error' : '' }}" name="name">
						@foreach($aExpertises as $expertise)
							<option {{ $expertise==$name ? ' selected': '' }}>{{$expertise}}</option>
						@endforeach
						</select>
						{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				{{-- ./ Name --}}
				
				{{-- Description --}}
				<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
					<label class="col-md-2 control-label" for="description">Description</label>
					<div class="col-md-10">
						<textarea class="form-control" name="description" placeholder="Description" rows="6">{{{ Input::old('description', $revision ? $revision->description : null) }}}</textarea>
						{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				{{-- ./ Description --}}
				
				{{-- State --}}
				<div class="form-group">
					<label class="col-md-2 control-label" for="state">Etat</label>
					<div class="col-md-10">
						<select class="form-control" name="state"{{($modelInstance->sub && $modelInstance->eachSub()->count()>0)?' disabled title="L\'état est automatiquement défini par les sous-actions"':''}}>
						@foreach(EwbsActionRevision::states() as $s)
							<option value="{{$s}}"{{ $s==$state ? ' selected': '' }}>{{ Lang::get( "admin/ewbsactions/messages.state.{$s}") }}</option>
						@endforeach
						</select>
					</div>
				</div>
				{{-- ./ State --}}
				
				{{-- Priority --}}
				<div class="form-group">
					<label class="col-md-2 control-label" for="priority">Priorité</label>
					<div class="col-md-10">
						<select class="form-control" name="priority"{{$loggedUser->can('ewbsaction_prioritize')?' ':' disabled'}}>
						@foreach(EwbsActionRevision::priorities() as $p)
							<option value="{{$p}}"{{ $p==$priority ? ' selected': '' }}>{{ Lang::get( "admin/ewbsactions/messages.priority.{$p}") }}</option>
						@endforeach
						</select>
					</div>
				</div>
				{{-- ./ Priority --}}
				
				{{-- Responsible --}}
				<div class="form-group">
					<label class="col-md-2 control-label" for="state">Responsable</label>
					<div class="col-md-10">
						<select class="form-control select2" name="responsible_id">
						@foreach($aUsers as $user)
							<option value="{{$user->id}}"{{ $user->id==$responsible_id ? ' selected': '' }}>{{ $user->username }}</option>
						@endforeach
						</select>
					</div>
				</div>
				{{-- ./ Responsible --}}
				
				{{-- Sub --}}
				@if($loggedUser->hasRole('admin'))
				<div class="form-group {{{ $errors->has('sub') ? 'has-error' : '' }}}">
					<label class="col-md-2 control-label" for="sub">Sous-actions autorisées</label>
					<div class="col-md-10">
						<div class="switch">
							<input type="checkbox" name="sub"{{ $errors->count()>0 ? (Input::old('sub')?' checked':'') : ($modelInstance && $modelInstance->sub ? ' checked':'') }} />
						</div>
						{{ $errors->first('sub', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
				@endif
				{{-- ./ Sub --}}

				{{-- Taxonomie --}}
				<div class="form-group" >
					<label class="col-md-2 control-label" form="tags">Tags</label>
					<div class="col-md-10">
						<!-- tags -->
						<?php
						// recherche de l'élément à selectionner
						$selectedTags = [];
						if ($modelInstance)
							$selectedTags = $aSelectedTags; //passée par le controlleur (voir function getManage());
						if (Input::old('tags'))
							$selectedTags = Input::old('tags');
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
				
				{{-- Actions --}}
				<div class="form-group">
					<div class="col-md-10 col-md-offset-2">
						<a class="btn btn-cancel" href="{{ $modelInstance->routeGetView() }}">{{Lang::get('button.cancel')}}</a>
						<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
					</div>
				</div>
				{{-- ./ form actions --}}
			</form>
		</div>
	</div>
</div>
@stop