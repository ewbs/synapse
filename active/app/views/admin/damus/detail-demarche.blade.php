<?php
/**
 * ATTENTION, ON PARLE ICI D'UNE NOSTRADEMARCHE, PAS D'UNE DEMARCHE
 */
?>

@extends('site.layouts.container-fluid')
@section('title')Détail d'une démarche @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<h3>{{ $demarche->title }}</h3>
			</div>
			<div class="content">
				<p>
					@if ( $synapseDemarche )
						<a href="{{ route('demarchesGetView', $synapseDemarche->id) }}"><span class="fa fa-eye"></span> Voir la démarche</a>
					@else
						<a href="{{ route('demarchesGetCreate', $demarche->id) }}"><span class="fa fa-eye"></span> Voir la démarche</a>
					@endif
				</p>
				<p>
					<strong>Identifiant Nostra : </strong> {{ $demarche->nostra_id }}<br/>
					<strong>Présent dans Synapse depuis : </strong> {{ DateHelper::datetime($demarche->created_at) }}<br/>
					<strong>Aperçu pour la dernière fois dans Nostra : </strong> {{ DateHelper::datetime($demarche->nostra_state) }}
				</p>
				<p>
					<strong>Titre long : </strong> {{ $demarche->title_long }}<br/>
					<strong>Titre court : </strong> {{ $demarche->title_short }}<br/>
					<strong>Type : </strong> {{ $demarche->type }}<br/>
				</p>
				<h4>En lien avec <span class="badge badge-default">{{count ($demarche->nostraPublics)}}</span> publics cibles</h4>
				@if ( count ($demarche->nostraPublics) > 0 )
					<ul>
						@foreach($demarche->nostraPublics as $public)
							@include('admin.damus.partials.public', $public)
						@endforeach
					</ul>
				@else
					Aucun public
				@endif
				<h4>En lien avec <span class="badge badge-default">{{count ($demarche->nostraThematiquesabc)}}</span> thématiques ABC</h4>
				@if ( count ($demarche->nostraThematiquesabc) > 0 )
					<ul>
						@foreach($demarche->nostraThematiquesabc as $thematique)
							@include('admin.damus.partials.thematique', $thematique)
						@endforeach
					</ul>
				@else
					Aucune thématique ABC
				@endif
				<h4>En lien avec <span class="badge badge-default">{{count ($demarche->nostraThematiquesadm)}}</span> thématiques administratives</h4>
				@if ( count ($demarche->nostraThematiquesadm) > 0 )
					<ul>
						@foreach($demarche->nostraThematiquesadm as $thematique)
							@include('admin.damus.partials.thematiqueadm', $thematique)
						@endforeach
					</ul>
				@else
					Aucune thématique ADM
				@endif
				<h4>En lien avec <span class="badge badge-default">{{count ($demarche->nostraEvenements)}}</span> événements</h4>
				@if ( count ($demarche->nostraEvenements) > 0 )
					<ul>
						@foreach($demarche->nostraEvenements as $evenement)
							@include('admin.damus.partials.evenement', $evenement)
						@endforeach
					</ul>
				@else
					Aucun événement
				@endif
			</div>
		</div>
	</div>
</div>
@stop