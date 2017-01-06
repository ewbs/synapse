<?php
/**
 * Modale pour l'affichage de l'historique d'un composant liée à une démarche.
 * Générée côté serveur et chargée via le clic sur un élément de classe servermodal.
 * 
 * @var Demarche $demarche
 * @var DemarcheComponent $demarche_component
 * @var boolean $manage
 */
?>
<div class="modal fade noAuto colored-header" id="servermodal" tabindex="-1" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-xlg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalCompleteTitle">
				{{Lang::get("admin/demarches/messages.{$demarche_component->componentType()}.history.title")}}
				<span></span></h3>
			</div>
			<div class="modal-body" id="modalCompleteBody">
				<div class="table-responsive">
					<table class="table table-hover datatable" data-ajaxurl="{{$demarche_component->routeGetHistoryData(['demarche'=>$demarche->id, $demarche_component->componentType()=>$demarche_component->componentId(), 'name'=>$demarche_component->name, 'manage'=>$manage])}}">
						<thead>
							<tr>
								<th>Détail</th>
								<th>Coût admin</th>
								<th>Coût usager</th>
								<th>Volume</th>
								<th>Frq</th>
								<th>Gain pot. admin</th>
								<th>Gain eff. admin</th>
								<th>Gain pot. usager</th>
								<th>Gain eff. usager</th>
								<th>Révision</th>
								@if($manage)
								<th>Actions</th>
								@endif
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>