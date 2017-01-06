<?php
/**
 * Modale d'historique de le l'annexe liée au formulaire
 * 
 * @var Eform $eform
 * @var AnnexeEform $annexe_eform
 */
?>
<div class="modal fade noAuto colored-header" id="servermodal" tabindex="-1" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalCompleteTitle">Historique de l'annexe<span></span></h3>
			</div>
			<div class="modal-body" id="modalCompleteBody">
				<div class="table-responsive">
					<table class="table table-hover datatable" data-ajaxurl="{{ route('eformsAnnexesGetHistoryData', [$eform->id, $annexe_eform->id]) }}">
						<thead>
							<tr>
								<th class="col-md-2">Etat courant</th>
								<th class="col-md-2">Etat suivant</th>
								<th>Commentaire</th>
								<th class="col-md-2">Révision</th>
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