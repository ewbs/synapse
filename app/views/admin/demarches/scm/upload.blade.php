@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2><span class="fa fa-briefcase"></span> SCM de la démarche <em><a href="{{$modelInstance->routeGetView()}}">{{ $modelInstance->nostraDemarche->title }}</a></em></h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-8">
			<div class="block-flat">
				<div class="header">
					<h3><span class="text-primary">{{$modelInstance->completeId}}</span> {{$modelInstance->nostraDemarche->title}}</h3>
				</div>
				<div class="content">
					
					<p id="scm-instructions">
						Il est possible de mettre à jour les pièces et tâches liée à la démarche en envoyant un fichier Excel.<br/>
						Nous vous conseillons de partir d'un fichier généré par Synapse (en téléchargeant le SCM Light) et de mettre les données jour dans ce fichier.<br/>
						<br/>
						Veillez à ne pas enlever le marqueur "#EOF#" présent dans ce fichier.
					</p>
					
					<div id="scm-error" class="alert alert-danger alert-white rounded hidden">
						<div class="icon"><i class="fa fa-exclamation"></i></div>
						<strong>Ouch!</strong> <span id="scm-error-content"></span> <a href="">(Faire un nouvel essai)</a>
					</div>
					
					<div id="scm-success" class="alert alert-success alert-white rounded hidden">
						<div class="icon"><i class="fa fa-check"></i></div>
						<strong>Yipie!</strong> <span id="scm-success-content">Votre fichier a été correctement traité et aucune erreur n'a été décelée.</span></a>
					</div>
					
					<div id="scm-warning" class="alert alert-warning alert-white rounded hidden">
						<div class="icon"><i class="fa fa-warning"></i></div>
						<strong>Tadam!</strong> <span id="scm-warning-content">Votre fichier a été correctement traité mais certaines lignes ont été ignorées.</span></a>
					</div>
					
					<div id="scm-nothing" class="alert alert-info alert-white rounded hidden">
						<div class="icon"><i class="fa fa-info-circle"></i></div>
						<strong>Hu?!</strong> <span id="scm-nothing-content">Aucune ligne de votre fichier n'a été traitée.</span></a>
					</div>
					
					<table id="scm-results" class="no-border hidden">
						<thead class="no-border">
							<tr>
								<th></th>
								<th>Ligne</th>
								<th>Type</th>
								<th>Résultat</th>
							</tr>
						</thead>
						<tbody class="no-border-y"></tbody>
					</table>
					
					<table id="scm-result-template-tr-success" class="hidden">
						<tr>
							<td class="color-success"><i class="fa fa-check"></i></td>
							<td class="scm-td-line"></td>
							<td class="scm-td-type"></td>
							<td class="scm-td-result"></td>
						</tr>
					</table>
					
					<table id="scm-result-template-tr-error" class="hidden">
						<tr>
							<td class="color-danger"><i class="fa fa-exclamation"></i></td>
							<td class="scm-td-line"></td>
							<td class="scm-td-type"></td>
							<td class="scm-td-result"></td>
						</tr>
					</table>
					
					<table id="scm-result-template-tr-nothing" class="hidden">
						<tr>
							<td><i class="fa fa-times"></i></td>
							<td class="scm-td-line"></td>
							<td class="scm-td-type"></td>
							<td class="scm-td-result"></td>
						</tr>
					</table>
										
					<form action="{{route('demarchesScmUploadPostFile', $modelInstance->id)}}" class="dropzone" id="dropzone-scm-upload" data-urlprocess="{{route('demarchesScmUploadPostProcess', $modelInstance->id)}}">
						<!-- CSRF Token -->
						<input type="hidden" id="_token" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->
						<div class="fallback">
							<input name="file" type="file" />
						</div>
					</form>
					
					<div id="file-in-progress" class="well hidden">
						<h3><i class="fa fa-cog fa-spin"></i> Votre fichier est en cours de traitement. Veuillez patienter...</h3>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="content no-padding">
					@include('admin.modelInstance.partial-features')
				</div>
			</div>
			@include('admin.demarches.blocs.projets_lies')
			@include('admin.demarches.blocs.infos_nostra')
		</div>
	</div>
</div>
@stop

@section('scripts')
<script lang="javascript">
	$(document).ready( function () {
		$("#sidebar-collapse").trigger("click"); //fermer la sidebar
	});
</script>
{{ HTML::script('js/dropzone/dropzone.min.js') }}
<script type="text/javascript">Dropzone.autoDiscover = false;</script>
{{ HTML::script('js/synapse/modDemarches-scm.js') }}
@stop