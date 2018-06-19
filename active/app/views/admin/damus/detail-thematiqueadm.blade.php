@extends('site.layouts.container-fluid')
@section('title')Détail d'une thématique Administrative @stop
@section('content')
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