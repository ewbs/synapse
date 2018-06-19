@extends('site.layouts.container-fluid')
@section('title'){{ ($modelInstance ? 'Edition' : 'Création') }} d'une catégorie de taxonomie @stop
@section('content')
<div class="row">
	<div class="col-md-{{ ($modelInstance)?8:12 }}">
		<div class="block-flat">
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
					<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
				
					<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="title">Nom de la catégorie</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="name" id="name" value="{{ Input::old('name', $modelInstance ? $modelInstance->name : '') }}"/>
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
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
	@if ($modelInstance)
	<div class="col-md-4">
		<div class="block-flat">
			<div class="header">
				<h3>Tags contenus</h3>
			</div>
			<div class="content">
				@if ( count($modelInstance->tags) )
					
						@foreach ($modelInstance->tags as $tag)
						<a href="{{route('taxonomytagsGetView', $tag->id)}}"><span class="label label-default">{{$tag->name()}}</span></a>
						@endforeach
					
				@else
				Aucun
				@endif
			</div>
		</div>
	</div>
	@endif
</div>
@stop