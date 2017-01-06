@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-connectdevelop"></span> Détail d'un public cible</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>{{ $public->title }}</h3>
				</div>
				<div class="content">
					<p>
						<strong>Identifiant Nostra : </strong> {{ $public->nostra_id }}<br/>
						<strong>Présent dans Synapse depuis : </strong> {{ DateHelper::datetime($public->created_at) }}<br/>
						<strong>Aperçu pour la dernière fois dans Nostra : </strong> {{ DateHelper::datetime($public->nostra_state) }}
					</p>
					<h4>En lien avec <span class="badge badge-default">{{count ($public->nostraThematiquesabc)}}</span> thématiques ABC</h4>
					@if ( count ($public->nostraThematiquesabc) > 0 )
						<ul>
							@foreach($public->nostraThematiquesabc as $thematique)
								@include('admin.damus.partials.thematique', $thematique)
							@endforeach
						</ul>
					@else
						Aucune thématique ABC
					@endif
					<h4>En lien avec <span class="badge badge-default">{{count ($public->nostraEvenements)}}</span> événements déclencheurs</h4>
					@if ( count ($public->nostraEvenements) > 0 )
						<ul>
							@foreach($public->nostraEvenements as $evenement)
								@include('admin.damus.partials.evenement', $evenement)
							@endforeach
						</ul>
					@else
						Aucun événement déclencheur
					@endif
					<h4>En lien avec <span class="badge badge-default">{{count ($public->nostraDemarches)}}</span> démarches</h4>
					@if ( count ($public->nostraDemarches) > 0 )
						<ul>
							@foreach($public->nostraDemarches as $demarche)
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