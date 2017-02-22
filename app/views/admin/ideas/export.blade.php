@extends('site.layouts.container-fluid')
@section('title')Export des projets au format Excel @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<h3>Export des projets au format Excel</h3>
			</div>
			<div class="content">
				{{-- Ideas Form --}}
				<form id="export-ideas-form" class="form-horizontal" method="post" action="" autocomplete="off">
					<!-- CSRF Token -->
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<!-- ./ csrf token -->
					
					<div class="col-md-10 col-md-offset-2">
						<p>Vous pouvez personnaliser votre fichier d'export grâce à ces filtres.<br /></p>
					</div>
					
					<!-- Public cible -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="nostra_publics">Public(s) cible</label>
						<div class="col-md-10">
							<select class="select2 nostra" multiple name="nostra_publics[]" id="nostra_publics">
								@foreach($arrayNostraPublics as $public)
								<option value="{{$public->id}}">{{{$public->title}}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<!-- ./ publics cibles -->
					
					<!-- administrations impliquées -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="administrations">Administration(s) impliquée(s)</label>
						<div class="col-md-10">
							<select class="select2" multiple name="administrations[]" id="administrations">
								@foreach($arrayOfRegions as $region)
								<optgroup label="{{$region->name}}">
									@foreach($region->administrations as $administration)
									<option value="{{$administration->id}}">{{{$administration->name}}}</option>
									@endforeach
								</optgroup>
								@endforeach
							</select>
						</div>
					</div>
					<!-- ./ administrations impliquées -->
					
					<!-- ministre compétent -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="ministers">Ministre(s) compétent(s)</label>
						<div class="col-md-10">
							<select class="select2" multiple name="ministers[]" id="ministers">
								@foreach($arrayOfGovernements as $governement)
								<optgroup label="{{$governement->name}}">
									@foreach($governement->ministers as $minister)
									<option value="{{$minister->id}}">{{{$minister->lastname . ' '. $minister->firstname}}}</option>
									@endforeach
								</optgroup>
								@endforeach
							</select>
						</div>
					</div>
					<!-- ./ ministre compétent -->
					
					<!-- switches -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="">Uniquement les projets prioritaire ?</label>
						<div class="col-md-10">
							<div class="switch">
								<input type="checkbox" name="prioritary" value="1" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="">Inclure les projets génériques ?</label>
						<div class="col-md-10">
							<div class="switch">
								<input type="checkbox" name="transversal" value="1" />
							</div>
						</div>
					</div>
					<!-- ./ switches -->
					
					<!-- Actions -->
					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<a class="btn btn-cancel" href="{{ $model->routeGetIndex() }}">{{Lang::get('button.cancel')}}</a>
							<button type="submit" class="btn btn-primary">Télécharger un export au format xls</button>
						</div>
					</div>
					<!-- ./ form actions -->
				</form>
			</div>
		</div>
	</div>
</div>
@stop
