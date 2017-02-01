@extends('site.layouts.container-fluid')
@section('title'){{ ($administration ? 'Edition' : 'Création') }} d'une administration @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				{{-- CreateEdit Form --}}
				<form id="manage-administration-form" class="form-horizontal" method="post" autocomplete="off" action="{{ $administration ? $administration->routePostEdit() :$model->routePostCreate() }}">
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<!-- ./ csrf token -->
					
					<!-- Nom -->
					<div
						class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Nom</label>
						<div class="col-md-10">
							<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $administration ? $administration->name : null) }}}" placeholder="Nom" />
							{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Nom -->
					
					<!-- Région -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="region">Région</label>
						<div class="col-md-10">
							<select class="select2" name="region" id="region">
								@foreach($regions as $region)
								<option value="{{ $region->id }}" {{ ($administration && $administration->region==$region) ?'selected' : '' }}>{{$region->name}}</option>
								@endforeach
							</select>
							{{ $errors->first('region', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					<!-- ./ Région -->
					
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
@stop
