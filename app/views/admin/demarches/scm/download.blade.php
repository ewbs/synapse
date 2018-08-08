<?php 
/**
 * @var Demarche $modelInstance
 */
?>
@extends('site.layouts.container-fluid')
@section('title')SCM de la démarche <em>{{ $modelInstance->nostraDemarche->title }}</em> @stop
@section('content')
<div class="row">
	<div class="col-md-8">
		<div class="block-flat">
			<div class="header">
				<h3>
					SCM Light généré par Synapse
				</h3>
			</div>
			<div class="content">
				<p>
					Vous pouvez télécharger ici le SCM Light généré par Synapse à la date d'aujourd'hui.<br/>
					Ce SCM ne prend pas en compte les pièces et tâches ajoutées manuellement par un utilisateur dans le fichier Excel.
				</p>
				<a class="btn" href="{{ route('demarchesGetDownload',['demarche'=>$modelInstance->id, 'bypassXLSCheck'=>'1']) }}"><span class="fa fa-download"></span> Télécharger le SCM Light généré par Synapse</a>
				
				<hr/>
				
				<h4>SCM Light au format XLS envoyés manuellement</h4>
				<p>Voici la liste des fichiers XLS envoyés dans Synapse</p>
				<table id="scm-results" class="no-border">
					<thead class="no-border">
						<tr>
							<th>Date</th>
							<th>Utilisateur</th>
							<th></th>
						</tr>
					</thead>
					<tbody class="no-border-y">
						@foreach ($scmFiles as $file)
							<tr>
								<td>{{DateHelper::datetime( $file->created_at, true )}}</td>
								<td>{{$file->user->username}}</td>
								<td class="actions">
									<a class="btn btn-xs" href="{{ route('demarchesGetSCMDownload',['demarche'=>$modelInstance->id, 'demarche_scm'=>$file->id]) }}"><span class="fa fa-download"></span></a>
									<a class="btn btn-xs btn-danger" href="{{ route('demarchesGetDeleteSCMDownload',['demarche'=>$modelInstance->id, 'demarche_scm'=>$file->id]) }}"><span class="fa fa-trash"></span></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		@include('admin.demarches.blocs.projets_lies')
		@include('admin.demarches.blocs.infos_nostra')
	</div>
</div>
@stop

@section('scripts')
<script lang="javascript">
	$(document).ready( function () {
		$("#sidebar-collapse").trigger("click"); //fermer la sidebar
	});
</script>
@stop
