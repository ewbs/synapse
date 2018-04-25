<?php
/**
 * @var Annexe $modelInstance
 * @var array aPieces
 */
$piece_id = Input::old('piece_id', $modelInstance ? $modelInstance->piece_id : '');
?>
@extends('site.layouts.container-fluid')
@section('title'){{ ($modelInstance ? 'Edition' : 'Création') }} d'une annexe @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
					<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
					
					<div class="form-group {{{ $errors->has('title') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="title">Nom de l'annexe</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="title" id="title" value="{{ Input::old('title', $modelInstance ? $modelInstance->title : '') }}"/>
								{{ $errors->first('title', '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						
					<div class="form-group">
						<label class="col-md-2 control-label" for="name">Description</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $modelInstance ? $modelInstance->description : null) }}}</textarea>
							@optional
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label" for="piece_id">Relative à la pièce</label>
						<div class="col-md-10">
							<select class="form-control select2" name="piece_id" id="piece_id"/>
									<option></option>
									@foreach($aPieces as $item)
									<option value="{{ $item->id }}"{{ $piece_id==$item->id ?' selected':'' }}>{{ $item->name }}</option>
									@endforeach
								</select>
							@optional
						</div>
					</div>
					
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