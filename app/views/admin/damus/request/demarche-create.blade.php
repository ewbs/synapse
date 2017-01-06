@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
Bienvenue sur Synapse
@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-connectdevelop"></span> Damus (Contenu tiré de Nostra)</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header"><h3>{{Lang::get('admin/damus/messages.request.demarche.create.title')}}</h3></div>
				<div class="content">
					<form class="form-horizontal" method="post" autocomplete="off" action="{{route('damusPostRequestCreateDemarche')}}">
						<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
						<div class="form-group">
							<label class="col-md-2 control-label" for="name">Nom du projet</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="name" value="{{{ Input::old('name', '') }}}" required/>
							</div>
						</div>
						@include('admin.common.partial-nostraSelects')
						<div class="form-group">
							<label class="col-md-2 control-label" for="documents">Documents liés</label>
							<div class="col-md-10">
								<textarea class="form-control" name="documents" rows="4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="forms">Formulaires liés</label>
							<div class="col-md-10">
								<textarea class="form-control" name="forms" rows="4"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="simplified">Simplifié</label>
							<div class="col-md-10">
								<div class="switch">
									<input type="checkbox" name="simplified"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="german_version">Version allemande</label>
							<div class="col-md-10">
								<div class="switch">
									<input type="checkbox" name="german_version"/>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="type">Type</label>
							<div class="col-md-10">
								<select class="form-control">
									<option value="droit">Droit</option>
									<option value="obligation">Obligation</option>
									<option value="information">Information</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="comment">Motivation de la demande</label>
							<div class="col-md-10">
								<textarea class="form-control" name="comment" rows="4" required></textarea>
							</div>
						</div>
						<div class="form-group ">
							<div class="col-md-offset-2 col-md-10">
								<a class="btn btn-cancel" href="{{ route('demarchesGetIndex') }}">{{Lang::get('button.cancel')}}</a>
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