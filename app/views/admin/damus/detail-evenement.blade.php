@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-connectdevelop"></span> Détail d'un événement déclencheur</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>{{ $evenement->title }}</h3>
				</div>
				<div class="content">
					<p>
						<strong>Identifiant Nostra : </strong> {{ $evenement->nostra_id }}<br/>
						<strong>Présent dans Synapse depuis : </strong> {{ DateHelper::datetime($evenement->created_at) }}<br/>
						<strong>Aperçu pour la dernière fois dans Nostra : </strong> {{ DateHelper::datetime($evenement->nostra_state) }}
					</p>
					<h4>En lien avec <span class="badge badge-default">{{count ($evenement->nostraPublics)}}</span> publics cibles</h4>
					@if ( count ($evenement->nostraPublics) > 0 )
						<ul>
							@foreach($evenement->nostraPublics as $public)
								@include('admin.damus.partials.public', $public)
							@endforeach
						</ul>
					@else
						Aucun public
					@endif
					<h4>En lien avec <span class="badge badge-default">{{count ($evenement->nostraThematiquesabc)}}</span> thématiques ABC</h4>
					@if ( count ($evenement->nostraThematiquesabc) > 0 )
						<ul>
							@foreach($evenement->nostraThematiquesabc as $thematique)
								@include('admin.damus.partials.thematique', $thematique)
							@endforeach
						</ul>
					@else
						Aucune thématique ABC
					@endif
					<h4>En lien avec <span class="badge badge-default">{{count ($evenement->nostraDemarches)}}</span> démarches</h4>
					@if ( count ($evenement->nostraDemarches) > 0 )
						<ul>
							@foreach($evenement->nostraDemarches as $demarche)
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