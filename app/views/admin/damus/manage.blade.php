@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-connectdevelop"></span> Damus (Contenu tiré de Nostra)</h2>
</div>

<div class="cl-mcont">
	
	<div class="tab-container">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#damus-pub" data-toggle="tab"><span class="badge badge-primary">{{ $damusPublicsCount }}</span> Publics cibles</a></li>
			<li><a href="#damus-abc" data-toggle="tab"><span class="badge badge-primary">{{ $damusThematiquesABCCount }}</span> Thématiques ABC</a></li>
			<li><a href="#damus-evt" data-toggle="tab"><span class="badge badge-primary">{{ $damusEvenementsCount }}</span> Evenements déclencheurs</a></li>
			<li><a href="#damus-adm" data-toggle="tab"><span class="badge badge-primary">{{ $damusThematiquesADMCount }}</span> Thématiques Administratives</a></li>
			<li><a href="#damus-dem" data-toggle="tab"><span class="badge badge-primary">{{ $damusDemarchesCount }}</span> Démarches</a></li>
		</ul>
		<div class="tab-content">
			<div id="damus-pub" class="tab-pane cont active">
				@if ( count($damusRootPublics) > 0 )
					<ul>
						@foreach ($damusRootPublics as $public)
							@include('admin.damus.partials.public', $public)
						@endforeach
					</ul>
				@else
					Aucun public cible
				@endif
			</div>
			<div id="damus-abc" class="tab-pane cont">
				@if ( count($damusRootThematiquesABC) > 0 )
					<ul>
						@foreach ($damusRootThematiquesABC as $thematique)
							@include('admin.damus.partials.thematique', $thematique)
						@endforeach
					</ul>
				@else
					Aucune thématique ABC
				@endif
			</div>
			<div id="damus-evt" class="tab-pane cont">
				@if ( count($damusEvenements) > 0 )
					<ul>
						@foreach ($damusEvenements as $evenement)
							@include('admin.damus.partials.evenement', $evenement)
						@endforeach
					</ul>
				@else
					Aucun événement déclencheur
				@endif
			</div>
			<div id="damus-adm" class="tab-pane cont">
				@if ( count($damusRootThematiquesADM) > 0 )
					<ul>
						@foreach ($damusRootThematiquesADM as $thematique)
							@include('admin.damus.partials.thematiqueadm', $thematique)
						@endforeach
					</ul>
				@else
					Aucune thématique ADM
				@endif
			</div>			
			<div id="damus-dem" class="tab-pane cont">
				@if ( count($damusDemarches) > 0 )
					<ul>
						@foreach ($damusDemarches as $demarche)
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

@section('scripts')
<script lang="javascript">
	$(document).ready( function () {
		
	});
</script>
@stop