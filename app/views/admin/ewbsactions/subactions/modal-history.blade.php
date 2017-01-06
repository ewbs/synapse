<?php 
/**
 * Modale pour l'affichage de l'historique d'une sous-action.
 * Générée côté serveur et chargée via le clic sur un élément de classe servermodal.
 * 
 * @var EwbsAction $parent
 * @var EwbsAction $action
 * @var boolean $manage
 */
?>
<div class="modal fade noAuto colored-header" id="servermodal" tabindex="-1" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalCompleteTitle">Historique de la sous-action<span></span></h3>
			</div>
			<div class="modal-body" id="modalCompleteBody">
				@include('admin.ewbsactions.history-timeline', ['modelInstance'=>$action])
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>