<?php 
/**
 * @var Demarche $modelInstance
 */
?>
@extends('site.layouts.container-fluid')
@section('title')Suppression d'un fichier SCM Light envoyé manuellement @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<form id="deleteForm" class="form-horizontal" method="post" autocomplete="off" action="{{ route('demarchesPostDeleteSCMDownload',['demarche'=>$scmFile->demarche_id, 'demarche_scm'=>$scmFile->id]) }}">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<p>
						Vous allez supprimer le fichier SCM Light envoyé le <strong>{{DateHelper::datetime( $scmFile->created_at, true )}}</strong>. Cette opération est irréversible.
					</p>
					<div class="controls">
						<a class="btn btn-cancel" href="{{ route('demarchesGetDownload', $scmFile->demarche_id) }}">{{Lang::get('button.cancel')}}</a>
						<button type="submit" class="btn btn-danger">Confirmer la suppression</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop
