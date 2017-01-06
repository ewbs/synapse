<?php
/**
 * Modale pour l'affichage du détail d'une démarche récupérée depuis Nostra
 * Générée côté serveur et chargée via le clic sur un élément de classe servermodal.
 * 
 * @var array $demarche
 */
?>
<div class="modal fade noAuto" id="servermodal" tabindex="-1" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalCompleteTitle">{{$demarche['node_title']}}<span></span></h3>
			</div>
			<div class="modal-body" id="modalCompleteBody">
				@if(!empty($demarche['title_user_long']) || !empty($demarche['title_user_short']))
				<h4 class="color-primary">Autres titres</h4>
				<ul>
					@if(!empty($demarche['title_user_long']))
						<li><strong>Titre long : </strong>{{$demarche['title_user_long']}}</li>
					@endif
					@if(!empty($demarche['title_user_short']))
						<li><strong>Titre court : </strong>{{$demarche['title_user_short']}}</li>
					@endif
				</ul>
				@endif
				
				@if(!empty($demarche['body']))
					<h4 class="color-primary">Description</h4>
					<div>{{$demarche['body']}}</div>
				@endif
				
				<?php //TODO Finir l'affichage du détail, cf. modDemarches-edit.js?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default cancel" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>