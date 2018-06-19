@extends('site.layouts.container-fluid')
@section('title'){{ ($modelInstance ? 'Edition' : 'Création') }} d'un service eWBS @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
					<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
				
					<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="title">Nom du service</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="name" id="name" value="{{ Input::old('name', $modelInstance ? $modelInstance->name : '') }}"/>
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="title">Description</label>
						<div class="col-md-10">
							<textarea style="height:100px;" class="form-control" name="description" id="description">{{ Input::old('description', $modelInstance ? $modelInstance->description : '') }}</textarea>
							@optional
						</div>
					</div>

					<!-- tags -->
					<?php
					// recherche de l'élément à selectionner
					$selectedTags = [];
					if ($modelInstance)
						$selectedTags = $modelInstance->tags->lists('id');
					if (Input::old('tags'))
						$selectedTags = Input::old('tags');
					?>
					<div class="form-group">
						<label class="col-md-2 control-label" for="category">Tags</label>
						<div class="col-md-10">
							<select class="form-control select2" name="tags[]" id="tags" multiple>
								@foreach($taxonomyCategories as $category)
									<optgroup label="{{$category->name}}">
										@foreach($category->tags()->orderBy('name')->get() as $tag)
											<option value="{{$tag->id}}"{{ in_array($tag->id, $selectedTags) ? ' selected': '' }}>{{$tag->name}}</option>
										@endforeach
									</optgroup>
								@endforeach
							</select>
							@optional
						</div>
					</div>
					<!-- ./ tags -->

					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<a class="btn btn-cancel" href="{{ $modelInstance ? $modelInstance->routeGetView() : $model->routeGetIndex() }}">{{Lang::get('button.cancel')}}</a>
							<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop