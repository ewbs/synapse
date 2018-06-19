@extends('site.layouts.container-fluid')
@section('title'){{ ($modelInstance ? 'Edition' : 'Création') }} d'un tag @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
					<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
				
					<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="title">Nom du tag</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="name" id="name" value="{{ Input::old('name', $modelInstance ? $modelInstance->name : '') }}"/>
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
						</div>
					</div>

					<!-- catégorie -->
					<?php
					// recherche de l'élément à selectionner
					$selectedCategory = 0;
					if ($modelInstance)
						$selectedCategory = $modelInstance->taxonomy_category_id;
					if (Input::old('category'))
						$selectedCategory = Input::old('category');
					?>
					<div class="form-group {{{ $errors->has('category') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="category">Catégorie</label>
						<div class="col-md-10">
							<select class="form-control" name="category" id="category">
								@foreach($categories as $category)
									<option value="{{$category->id}}"{{ $selectedCategory== $category->id ? ' selected': '' }}>{{$category->name}}</option>
								@endforeach
							</select>
							{{ $errors->first('category', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ relais ewbs -->

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