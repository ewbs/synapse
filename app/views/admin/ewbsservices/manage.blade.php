@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Services eWBS sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')

	<div class="page-head">
		<h2><span class="fa fa-wrench"></span> Catalogue de services</h2>
	</div>
	<div class="cl-mcont">
		<div class="row">
			<div class="col-md-12">
				<div class="block-flat">

					<div class="header">
						@if ($modelInstance)
							@include('admin.modelInstance.partial-features')
						@endif
						<h3>
							{{ ($modelInstance ? 'Edition' : 'Création') }} d'un service eWBS
						</h3>
					</div>

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

							<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="title">Description</label>
								<div class="col-md-10">
									<textarea style="height:100px;" class="form-control" name="description" id="description">{{ Input::old('description', $modelInstance ? $modelInstance->description : '') }}</textarea>
									{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
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
							<div class="form-group {{{ $errors->has('tags') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="category">Tags</label>
								<div class="col-md-10">
									<select class="form-control select2" name="tags[]" id="tags" multiple>
										@foreach($taxonomyCategories as $category)
											<optgroup label="{{$category->name}}">
												@foreach($category->tags as $tag)
													<option value="{{$tag->id}}"{{ in_array($tag->id, $selectedTags) ? ' selected': '' }}>{{$tag->name}}</option>
												@endforeach
											</optgroup>
										@endforeach
									</select>
									{{ $errors->first('tags', '<span class="help-inline">:message</span>') }}
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
	</div>
@stop