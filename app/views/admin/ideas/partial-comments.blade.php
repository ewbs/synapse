<?php
/**
 * Tout le brol pour l'affichage et la gestion des commentaires dans les projets de simplfif
 */
?>


<div id="comments-template" class="hidden">
	<div class="chat-conv sent">
		<img class="comments-useravatar c-avatar ttip" alt=""/>
		<div class="c-bubble">
			<div class="msg">
				@if ($loggedUser->can ( 'ideas_manage' ))<a href="javascript:void(0);" class="pull-right"><i class="fa fa-pencil pull-right"></i></a>@endif
				<p><strong class="comments-username"></strong></p>
				<p class="comment-content"></p>
				<div><small class="date"></small></div>
			</div>
		</div>
	</div>
</div>

<div id="comments-template-state" class="hidden">
	<div class="chat-conv">
		<img class="comments-useravatar c-avatar ttip" alt=""/>
		<div class="c-bubble">
			<div class="msg">
				<strong class="comments-username"></strong> a modifié l'état du
				projet en <strong class="comments-state"></strong>
				<div><small class="date"></small></div>
			</div>
		</div>
	</div>
</div>
<?php /*
{{-- Template d'un commentaire (<li>)
<ul id="comments-template" class="hidden">
	<li>
		<i class="fa fa-comment"></i> <span class="date"></span>
		<div class="content">
			<?php //FIXME : Ne peut-on éditer qu'avec cette permission? Quid si on est propriétaire de l'idée? ?>
			@if ($loggedUser->can ( 'ideas_manage' ))<a href="javascript:void(0);" class="pull-right"><i class="fa fa-pencil pull-right"></i></a>@endif
			<p>
				<img class="comments-useravatar" alt=""/> <strong class="comments-username"></strong>
			</p>
			<p class="comment-content"></p>
		</div>
	</li>
</ul>

{{-- Template d'un changement de statut (<li>)
<ul id="comments-template-state" class="hidden">
	<li>
		<i class="fa fa-cogs purple"></i> <span class="date"></span>
		<div class="content">
			<p>
				<img class="comments-useravatar" alt=""/> <strong class="comments-username"></strong> a modifié l'état du
				projet en <strong class="comments-state"></strong>
			</p>
		</div>
	</li>
</ul> --}} */ ?>
	
{{-- Modale de modification de commentaire et de suppression --}}
<div class="modal fade noAuto colored-header" id="comments-modal-edit" tabindex="-1" role="dialog" aria-labelledby="modalCompleteTitle">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalCompleteTitle">Modifier ou supprimer le commentaire de <span></span></h3>
			</div>
			<div class="modal-body" id="modalCompleteBody">
				<div class="form-group">
					<input type="hidden" id="comment-id" value="-1" />
					<textarea class="form-control"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="comments-modal-button-delete" class="btn btn-danger pull-left">Supprimer le commentaire</button>
				<button type="button" id="comments-modal-button-edit" class="btn btn-primary">Modifier</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			</div>
		</div>
	</div>
</div>

{{-- Modale de modification de commentaire et de suppression --}}
<div class="modal fade noAuto colored-header danger" id="comments-modal-delete" tabindex="-1" role="dialog" aria-labelledby="modalCompleteTitle2">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title" id="modalCompleteTitle2">Confirmer la suppression ?<span></span></h3>
			</div>
			<div class="modal-body" id="modalCompleteBody2">
				<p>Sûr et certain ? Pas de regret ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" id="comments-modal-confirm-button-delete" class="btn btn-danger">Supprimer le commentaire</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
			</div>
		</div>
	</div>
</div>