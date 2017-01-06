<?php
/**
 * Details d'une démarche Nostra (colonne de droite dans les différents écran d'édition des démarches
 */
	$count=count($aNostraDemarches);
?>
<input type="hidden" id="counter" value="{{$count}}"/>
@foreach($aNostraDemarches as $nostraDemarche)
<div class="item">
	<div>
		<div class="row-fluid">
			<div class="col-md-1">
				<h4 class="from">
					{{ $nostraDemarche->demarche_completeid }}
				</h4>
			</div>
			<div class="col-md-4">
				<h4 class="from">{{ $nostraDemarche->title }}</h4>
				<p class="msg">{{ $nostraDemarche->title_long }}</p>
				@if($nostraDemarche->type=='obligation')
				<span class="label label-primary">Obligation</span>
				@elseif($nostraDemarche->type=='droit')
				<span class="label label-success">Droit</span>
				@endif
				@if($nostraDemarche->demarche_ewbs)
				<span class="label label-info">eWBS</span>
				@endif
				
				<?php 
				$globalState=EwbsAction::globalState($nostraDemarche);
				if($globalState) {
					$tooltip='<ul>';
					foreach(EwbsActionRevision::states() as $state)
						if($count=$nostraDemarche->getAttribute("count_state_{$state}"))
							$tooltip.="<li>".Lang::choice("admin/ewbsactions/messages.wording.{$state}", $count)."</li>";
					$tooltip.='</ul>';
					echo '<a href="'.route('demarchesActionsGetIndex', $nostraDemarche->demarche_id).'" data-toggle="popover" data-content="'.$tooltip.'" data-html="true"><span class="label label-'.EwbsActionRevision::stateToClass($globalState).'">'.Lang::get('admin/demarches/messages.action.longtitle').' : '.Lang::get( "admin/ewbsactions/messages.state.{$globalState}").'</span></a>';
				}
				?>
			</div>
			<div class="col-md-2">
			{{ $nostraDemarche->administrations }}
			</div>
			<div class="col-md-2">
			{{ $nostraDemarche->thematiquesabc }}
			@if($nostraDemarche->thematiquesabc && $nostraDemarche->thematiquesadm), @endif
			{{ $nostraDemarche->thematiquesadm }}
			</div>
			<div class="col-md-2">
			{{ $nostraDemarche->publics }}
			</div>
			<div class="col-md-1">
				@if($nostraDemarche['demarche_id'])
				<a class="btn btn-xs btn-default" title="{{Lang::get('button.view')}}" href="{{route('demarchesGetView', $nostraDemarche->demarche_id)}}"><span class="fa fa-eye"></span></a>
				@elseif ($loggedUser->can('demarches_encode'))
				<a class="btn btn-xs btn-default" title="{{Lang::get('button.view')}}" href="{{route('demarchesGetCreate', $nostraDemarche->id)}}"><span class="fa fa-eye"></span></a>
				@endif
			</div>
		</div>
	</div>
</div>
@endforeach
@if($count==0)
<div id="noItemsFound">
	<div class="item">
		<div>
			<h4 class="from">Aucune démarche ne correspond à vos critères de recherche... peut-être devriez vous supprimer des filtres.</h4>
		</div>
	</div>
</div>
@endif