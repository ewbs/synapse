@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2><span class="fa fa-briefcase"></span> Référentiel des démarches</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>Suppression d'un fichier SCM Light envoyé manuellement</h3>
				</div>
				<div class="content">
					{{-- Delete Form --}}
					<form id="deleteForm" class="form-horizontal" method="post" autocomplete="off" action="{{ route('demarchesPostDeleteSCMDownload',['demarche'=>$scmFile->demarche_id, 'demarche_scm'=>$scmFile->id]) }}">
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->
						<p>
							Vous allez supprimer le fichier SCM Light envoyé le <strong>{{DateHelper::datetime( $scmFile->created_at, true )}}</strong>. Cette opération est irréversible.
						</p>
						<!-- Form Actions -->
						<div class="form-group">
							<div class="controls">
								<a class="btn btn-cancel" href="{{ route('demarchesGetDownload', $scmFile->demarche_id) }}">Annuler</a>
								<button type="submit" class="btn btn-danger">Confirmer la suppression</button>
							</div>
						</div>
						<!-- ./ form actions -->
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
