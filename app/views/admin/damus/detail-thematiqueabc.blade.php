@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-connectdevelop"></span> Détail d'une thématique ABC</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>{{ $thematique->title }}</h3>
				</div>
				<div class="content">
					<p>
						<strong>Identifiant Nostra : </strong> {{ $thematique->nostra_id }}<br/>
						<strong>Présent dans Synapse depuis : </strong> {{ DateHelper::datetime($thematique->created_at) }}<br/>
						<strong>Aperçu pour la dernière fois dans Nostra : </strong> {{ DateHelper::datetime($thematique->nostra_state) }}
					</p>
					<h4>En lien avec <span class="badge badge-default">{{count ($thematique->nostraPublics)}}</span> publics cibles</h4>
					@if ( count ($thematique->nostraPublics) > 0 )
						<ul>
							@foreach($thematique->nostraPublics as $public)
								@include('admin.damus.partials.public', $public)
							@endforeach
						</ul>
					@else
						Aucun public
					@endif
					<h4>En lien avec <span class="badge badge-default">{{count ($thematique->nostraEvenements)}}</span> événements déclencheurs</h4>
					@if ( count ($thematique->nostraEvenements) > 0 )
						<ul>
							@foreach($thematique->nostraEvenements as $evenement)
								@include('admin.damus.partials.evenement', $evenement)
							@endforeach
						</ul>
					@else
						Aucun événement déclencheur
					@endif
					<h4>En lien avec <span class="badge badge-default">{{count ($thematique->nostraDemarches)}}</span> démarches</h4>
					@if ( count ($thematique->nostraDemarches) > 0 )
						<ul>
							@foreach($thematique->nostraDemarches as $demarche)
								@include('admin.damus.partials.demarche', $demarche)
							@endforeach
						</ul>
					@else
						Aucune démarche
					@endif
				</div>
			</div>
		</div>
	</div>
	
@stop