@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary"> {{DateHelper::year($modelInstance->created_at)}}-{{$modelInstance->id}}</span> {{$modelInstance->nostraDemarche ? $modelInstance->nostraDemarche->title : $modelInstance->title}} @stop
@section('content')
<div class="row">
	<div class="col-md-4">
		{{-- 	--------------------------------------------------------------------------------------------
				INFOS NOSTRA
				--------------------------------------------------------------------------------------------	--}}
		@if($modelInstance->nostraDemarche)
			@include('admin.demarches.blocs.infos_nostra')
		@else
			@include('admin.demarches.blocs.infos_nostra_empty')
		@endif

		{{-- 	--------------------------------------------------------------------------------------------
				ACTIONS DE LA DEMARCHE
				--------------------------------------------------------------------------------------------	--}}
		@include('admin.ewbsactions.blocs.expertises')
	</div>

	<div class="col-md-4">
		@include('admin.demarches.blocs.components',['minimal'=>true, 'manage' => $modelInstance->canManage()])
	</div>

	<div class="col-md-4">
		@if (strlen($modelInstance->volume))
		<div class="block-flat">
			<div class="content no-padding">
				<div class="overflow-hidden">
					<span class="fa fa-random fa-4x color-success pull-left"></span>
					<h3 class="no-margin">{{$modelInstance->volume}}</h3>
					<p class="color-success">dossiers par an</p>
				</div>
			</div>
		</div>
		@endif

		@if ($modelInstance->is_dematerialise)
			<div class="block-flat">
				<div class="content no-padding">
					<div class="overflow-hidden">
						<span class="fa fa-flag fa-4x color-success pull-left"></span>
						<h3 class="no-margin">Cette démarche est dématerialisée</h3>
					</div>
				</div>
			</div>
		@endif

		<div class="block-flat">
			<div class="header">
				<h4><span class="fa fa-calculator"></span> Gains de charge (effectif / potentiel)</h4>
			</div>
			<div class="content">
				<p><strong>Usager : </strong>{{{ NumberHelper::moneyFormat($gains->gain_real_citizen) }}} / {{{ NumberHelper::moneyFormat($gains->gain_potential_citizen) }}}</p>
				<?php
					$percent = ( ($gains->gain_potential_citizen>0) ? round ( ($gains->gain_real_citizen/ $gains->gain_potential_citizen) * 100 ) : 0);
					if ($percent > 70) $class="success";
					elseif ($percent > 35) $class="warning";
					else $class="danger";
				?>
				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-{{$class}}" style="width:{{$percent>100?100:$percent}}%">{{$percent}}%</div>
				</div>
				<p><strong>Administration : </strong>{{{ NumberHelper::moneyFormat($gains->gain_real_administration) }}} / {{{ NumberHelper::moneyFormat($gains->gain_potential_administration) }}}</p>
				<?php
					$percent = ( ($gains->gain_potential_administration>0) ? round ( ($gains->gain_real_administration / $gains->gain_potential_administration) * 100 ) : 0);
					if ($percent > 70) $class="success";
					elseif ($percent > 35) $class="warning";
					else $class="danger";
				?>
				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-{{$class}}" style="width:{{$percent>100?100:$percent}}%">{{$percent}}%</div>
				</div>
			</div>
		</div>

		@include('admin.demarches.blocs.projets_lies')

		<div class="block-flat">
			<div class="header">
				<h4>Documentation</h4>
			</div>
			<div class="content">
				@if (strlen($modelInstance->comment)) <p>{{$modelInstance->comment}}</p><hr/> @endif
				<!-- Documentation externe (liens) -->
				@if ( ! count($modelInstance->docLinks) )
					<p>Aucune documentation n'a été liée à cette démarche.</p>
				@else
					<div class="list-group">
						@foreach($modelInstance->docLinks as $link)
							<a href="{{$link->url}}" target="_blank" class="list-group-item">
								<h5 class="list-group-item-heading"><i class="fa fa-link"></i> {{$link->name}}</h5>
								<div>{{$link->description}}</div>
							</a>
						@endforeach
					</div>
				@endif
			</div>
		</div>

		<div class="block-flat">
			<div class="header">
				<h4><span class="fa fa-tag"></span> Taxonomie</h4>
			</div>
			<div class="content">
				@if ( ! count($modelInstance->tags) )
					Aucun tag défini
				@else
					@foreach ( $modelInstance->tags as $tag )
						<span class="label label-default">{{$tag->name}}</span>
					@endforeach
				@endif
			</div>
		</div>

		<div class="block-flat">
			<div class="header">
				<h4>Administrations impliquées</h4>
			</div>
			<div class="content">
				@foreach ( $modelInstance->administrations as $adm )
					<span class="label label-default">{{$adm->name}}</span>
				@endforeach
			</div>
		</div>

		<div class="block-flat">
			<div class="header">
				<h4>Informations complémentaires</h4>
			</div>
			<div class="content">
				<p>
					<span class="fa fa-user"></span> Créé par <strong>{{$modelInstance->user->username}}</strong>
					({{HTML::mailto($modelInstance->user->email)}})
				</p>
				<p>
					<span class="fa fa-calendar"></span> Le {{DateHelper::datetime($modelInstance->created_at, true)}}
				</p>
			</div>
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
@stop
