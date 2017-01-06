@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<div class="page-head">
	<h2>Types de pièces et de tâches</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>{{ ($type ? 'Edition' : 'Création') }} d'un type</h3>
				</div>
				<div class="content">
					{{-- Create-Edit Rate Form --}}
					<form id="create-edit-type-form" class="form-horizontal" method="post" autocomplete="off" action="{{ ($type) ? $type->routePostEdit() : $model->routePostCreate() }}">
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->
						
						<!-- nom -->
						<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="name">Nom du type</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $type ? $type->name : null) }}}" />
								{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ nom -->
						
						<!-- appliqué  à ... -->
						<?php
						$checkPiece = true;
						if (count ( Input::old () ) && is_null ( Input::old ( 'for_pieces' ) )) {
							$checkPiece = false;
						} elseif ($type) {
							if ($type->for != "all" && $type->for != 'piece') {
								$checkPiece = false;
							}
						}
						$checkTask = true;
						if (count ( Input::old () ) && is_null ( Input::old ( 'for_tasks' ) )) {
							$checkTask = false;
						} elseif ($type) {
							if ($type->for != "all" && $type->for != 'task') {
								$checkTask = false;
							}
						}
						?>
						<div class="form-group {{{ $errors->has('for') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label"></label>
							<div class="col-md-10">
								<div class="radio">
									<label>
										<input type="checkbox" class="icheck" id="for_pieces" name="for_pieces" value="1"{{{ $checkPiece ? ' checked="checked" ' : ''}}} />
										Ce type est applicable aux pièces
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="checkbox" class="icheck" id="for_tasks" name="for_tasks" value="1"{{{ $checkTask ? ' checked="checked" ' : ''}}} />
										Ce type est applicable aux tâches
									</label>
								</div>
								{{ $errors->first('for', '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ appliqué à -->
						
						<!-- description -->
						<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="name">Description</label>
							<div class="col-md-10">
								<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $type ? $type->description : null) }}}</textarea>
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
