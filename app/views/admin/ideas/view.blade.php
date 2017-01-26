<?php
// on va plusierus fois l'utiliser ... on le stocke en var pour éviter de refaire la requete à chaque fois.
$ideaState = $modelInstance->getLastStateModification ()->ideaState;
?>


@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Bienvenue sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')

	<div class="page-head">
		<h2>
			<span class="text-primary"><span class="fa fa-lightbulb-o"></span> {{DateHelper::year($modelInstance->created_at)}}-{{$modelInstance->id}}</span></span>
			{{$modelInstance->name}}
		</h2>
	</div>

	<div class="cl-mcont">

		<div class="row">

			<div class="col-md-4">

				<div class="block-flat">
					<div class="header">
						<h4>Description</h4>
					</div>
					<div class="content">
						<p class="text-justify read-more" data-readmore-link-text="Lire la suite">
							{{$modelInstance->description}}
						</p>
					</div>
				</div>

				@if (strlen($modelInstance->reference))
					<div class="block-flat">
						<div class="header">
							<h4>Référence externe</h4>
						</div>
						<div class="content">
							<strong>{{$modelInstance->reference}}</strong>
						</div>
					</div>
				@endif

				<div class="block-flat">
					<div class="header">
						<h4>Administrations impliquées</h4>
					</div>
					<div class="content">
						@foreach ( $modelInstance->administrations as $adm )
							<span class="label label-default">{{$adm->name}}</span>
						@endforeach
					</div>
				</div>

				<div class="block-flat">
					<div class="header">
						<h4>Public(s) cible</h4>
					</div>
					<div class="content">
						@foreach ( $modelInstance->getNostraPublics() as $n )
							<span class="label label-default">{{$n->title}}</span>
						@endforeach
						@if (strlen ( $modelInstance->freeencoding_nostra_publics ))
							<span class="label label-default"><span class="fa fa-flash" title="Entrée libre"></span>{{$modelInstance->freeencoding_nostra_publics}}</span>
						@endif
						<hr/>
						<h5>Thématiques usager</h5>
						@if ( count($modelInstance->getNostraThematiquesabc()) )
							<ul>
							@foreach ($modelInstance->getNostraThematiquesabc() as $item)
								<li>{{$item->title}}</li>
							@endforeach
							</ul>
						@endif
						<h5>Thématiques administratives</h5>
						@if ( count($modelInstance->getNostraThematiquesadm()) )
							<ul>
							@foreach ($modelInstance->getNostraThematiquesadm() as $item)
								<li>{{$item->title}}</li>
							@endforeach
							</ul>
						@endif
						<h5>Evenements déclencheurs</h5>
						@if ( count($modelInstance->getNostraEvenements()) )
							<ul>
							@foreach ($modelInstance->getNostraEvenements() as $item)
								<li>{{$item->title}}</li>
							@endforeach
							</ul>
						@endif
					</div>
				</div>

				<div class="block-flat">
					<div class="header">
						<h4>Ministre(s) compétent(s)</h4>
					</div>
					<div class="content">
						@foreach ( $modelInstance->ministers as $min )
							<span class="label label-default">{{$min->firstname}} {{$min->lastname}}</span>
						@endforeach
					</div>
				</div>

				<div class="block-flat">
					<div class="header">
						<h4>Source du document</h4>
					</div>
					<div class="content">
						<p>
							<strong><em>{{{ $modelInstance->doc_source_title }}}</em></strong><br>
							Page {{{ $modelInstance->doc_source_page }}}<br/>
							<a target="_blank href="{{{ $modelInstance->doc_source_link }}}">{{{ $modelInstance->doc_source_link }}}</a>
						</p>
					</div>
				</div>
			</div>


			<div class="col-md-4">

				<div class="block-flat">
					<div class="header">
						<h4>Etat</h4>
					</div>
					<div class="content">
						@if ($modelInstance->prioritary) <span class="label label-primary">Prioritaire</span>@endif
						@if ($modelInstance->transversal) <span class="label label-info">Générique</span>@endif
						<span class="label label-primary">{{Lang::get('admin/ideas/states.'.$ideaState->name)}}</span>
					</div>
				</div>

				<div class="block-flat">
					<div class="header">
						<h4>Taxonomie</h4>
					</div>
					<div class="content">
						@if (count($modelInstance->tags))
							@foreach ($modelInstance->tags as $tag)
								<span class="badge badge-default">{{$tag->name}}</span>
							@endforeach
						@else
							Aucun tag
						@endif
					</div>
				</div>

				<div class="block-flat">
					<div class="header">
						<h4><span class="fa fa-briefcase"></span> Démarches liées</h4>
					</div>
					<?php $aDemarches = $modelInstance->getDemarches(); ?>
					@if ( ! count($aDemarches) )
						@warning('<br/>Ce projet n\'est liée à aucune démarche.')
					@else
						<ul class="list-group">
							@foreach ($aDemarches as $demarche)
								<li class="list-group-item">
									<strong>{{DateHelper::year($demarche->created_at) . '-' . str_pad ( $demarche->id, 4, "0", STR_PAD_LEFT )}}</strong>
									<a href="{{ route('demarchesGetView', $demarche->id) }}">{{$demarche->nostraDemarche->title}}</a>
								</li>
							@endforeach
						</ul>
					@endif
				</div>

				<div class="block-flat">
					<div class="header">
						<h4><span class="fa fa-magic"></span> Actions</h4>
					</div>
					<div class="content">

						@foreach($modelInstance->nostraDemarches as $nd)
							<h4><span class="fa fa-briefcase"></span> {{{ $nd->title }}}</h4>
							<?php $demarche=$nd->demarche; ?>
							@if($demarche)
								<?php $actions=EwbsAction::each()->forDemarche($demarche)->get(); ?>
								@if($actions)
									<div class="table-responsive actions">
										<table class="table table-hover datatable">
											<thead>
												<tr>
													<th>Actions</th>
													<th class="col-md-1">Etat</th>
												</tr>
											</thead>
											<tbody>
											@foreach($actions as $item)
													<tr>
														<td><strong>{{{ $item->name }}}</strong><br/><em>{{{ $item->description }}}</em></td>
														<?php /* <td>{{ ($item->demarche_piece_name ? ('<span title="'.Lang::get ( 'admin/demarches/messages.piece.piece' ).'"><i class="fa fa-clipboard"></i>'.$item->demarche_piece_name.'</span>') : ($item->demarche_task_name ? ('<span title="'.Lang::get ( 'admin/demarches/messages.task.task' ).'"><i class="fa fa-tasks"></i>'.$item->demarche_task_name.'</span>') : '')) }}</td> */ ?>
														<td>{{ EwbsActionRevision::graphicState($item->state) }}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								@endif
							@endif
							<hr/>
						@endforeach

					</div>

				</div>

			</div>

			<div class="col-md-4">

				<div class="block-flat">
					<div class="content no-padding">
						@include('admin.modelInstance.partial-features')
					</div>
				</div>

				<div class="block-flat">
					<div class="header">
						<h4><span class="fa fa-comment-o"></span> Commentaires</h4>
					</div>
					@if($modelInstance->canManage())
						<div class="form-group">
							<textarea class="form-control" id="comments-content" placeholder="Ajouter un commentaire ..."></textarea>
						</div>
						<div class="form-group">
							<button type="submit" id="comments-submit" class="btn btn-xs btn-primary pull-right">Ajouter mon commentaire</button>
						</div>
						<div class="clear"></div>
					@endif
					<div class="content">
						<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
						{{--<ul id="comments-list" class="timeline" data-idea-id="{{ $modelInstance->id }}"></ul>--}}
						<div class="chat-wi">
							<div class="chat-content content" id="comments-list" data-idea-id="{{ $modelInstance->id }}"></div>
						</div>
						<p id="comments-none">Aucun commentaire pour le moment</p>
					</div>
				</div>
				@include("admin.ideas.partial-comments")

				<div class="block-flat">
					<div class="header">
						<h4>Informations complémentaires</h4>
					</div>
					<div class="content">
						<p><span class="fa fa-user"></span> Créé par <strong>{{$modelInstance->user->username}}</strong> ({{HTML::mailto($modelInstance->user->email)}})</p>
						<p><span class="fa fa-calendar"></span> Le {{DateHelper::datetime($modelInstance->created_at)}}</p>
						@if($modelInstance->ewbs_member_id)<p><span class="fa fa-users"></span> Relai eWBS <strong>{{$modelInstance->ewbsMember->firstname}} {{$modelInstance->ewbsMember->lastname}}</strong></p>@endif
						@if($modelInstance->ext_contact)<p><span class="fa fa-users"></span> Contact <strong>{{$modelInstance->ext_contact}}</strong></p>@endif
					</div>
				</div>
			</div>
		</div>

	</div>


@stop


@section('scripts')

	<script type="text/javascript">
		<?php /* TODO : Mutualiser ceci dans un .js */ ?>
		$(document).ready( function () {

			$(".read-more").each ( function () {

				var maxWords = 60;
				var maxLength = 300;
				var linkText = $(this).data("readmore-link-text");
				var completeText = $(this).html();
				var $container = $(this);

				//trim le texte à la longueur max
				var shortText = completeText.substr(0, maxLength);
				//re-trim si on est au milieu d'un mot
				shortText = shortText.substr(0, Math.max(shortText.length, shortText.lastIndexOf(" ")));

				if (shortText.length < completeText.length) {
					shortText += " ...";
					$container.html(shortText);
					$('<a href="#"><span class="fa fa-caret-down"></span> ' + linkText + '</a>')
							.insertAfter($container)
							.click( function () {
								$container.html(completeText);
								$(this).remove();
							});
				}

			});

		});

	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.table-responsive.actions table.datatable').dataTable();
		});
	</script>

@stop




<?php /*
				<div class="content">
					<ol class="breadcrumb">
						<li>{{$ideaState->name == 'ENCODEE' ? '<span class="label label-primary">'.Lang::get('admin/ideas/states.ENCODEE').'</span>' : Lang::get('admin/ideas/states.ENCODEE')}}</li>
						<li>{{$ideaState->name == 'REVUE' ? '<span class="label label-primary">'.Lang::get('admin/ideas/states.REVUE').'</span>' : Lang::get('admin/ideas/states.REVUE')}}</li>
						<li>{{$ideaState->name == 'VALIDEE' ? '<span class="label label-primary">'.Lang::get('admin/ideas/states.VALIDEE').'</span>' : Lang::get('admin/ideas/states.VALIDEE')}}</li>
						<li>{{$ideaState->name == 'ENREALISATION' ? '<span class="label label-primary">'.Lang::get('admin/ideas/states.ENREALISATION').'</span>' : Lang::get('admin/ideas/states.ENREALISATION')}}</li>
						<li>{{$ideaState->name == 'REALISEE' ? '<span class="label label-primary">'.Lang::get('admin/ideas/states.REALISEE').'</span>' : Lang::get('admin/ideas/states.REALISEE')}}</li>
						<li>{{$ideaState->name == 'SUSPENDUE' ? '<span class="label label-primary">'.Lang::get('admin/ideas/states.SUSPENDUE').'</span>' : Lang::get('admin/ideas/states.SUSPENDUE')}}</li>
						<li>{{$ideaState->name == 'ABANDONNEE' ? '<span class="label label-primary">'.Lang::get('admin/ideas/states.ABANDONNEE').'</span>' : Lang::get('admin/ideas/states.ABANDONNEE')}}</li>
					</ol>


					</ul>
					<h5>Thématiques(s) usager</h5>
					<ul>
					<?php
					foreach ( $modelInstance->nostraThematiquesabc as $n ) {
						print ('<li>' . $n->title . '</li>') ;
					}
					if (strlen ( $modelInstance->freeencoding_nostra_thematiquesabc )) {
						print ('<li><span class="fa fa-flash" title="Entrée libre"></span> ' . $modelInstance->freeencoding_nostra_thematiquesabc . '</li>') ;
					}
					?>
					</ul>
					<h5>Evenements(s) déclencheur(s)</h5>
					<ul>
					<?php
					foreach ( $modelInstance->nostraEvenements as $n ) {
						print ('<li>' . $n->title . '</li>') ;
					}
					if (strlen ( $modelInstance->freeencoding_nostra_evenements )) {
						print ('<li><span class="fa fa-flash" title="Entrée libre"></span> ' . $modelInstance->freeencoding_nostra_evenements . '</li>') ;
					}
					?>
					</ul>
					
					<h5 id="demarches">Démarche(s)</h5>
					<ul>
						@foreach($modelInstance->nostraDemarches as $nd)
						<li>
						{{{ $nd->title }}}
						<?php $demarche=$nd->demarche; ?>
						@if($demarche)
							<?php $actions=EwbsAction::each()->forDemarche($demarche)->get(); ?>
							@if($actions)
							<div class="table-responsive actions">
								<table class="table table-hover datatable">
									<thead>
										<tr>
											<th>Actions</th>
											<th class="col-md-4">Pièce / Tâche</th>
											<th class="col-md-1">Etat</th>
										</tr>
									</thead>
									<tbody>
										@foreach($actions as $item)
										<tr>
											<td><strong>{{{ $item->name }}}</strong><br/><em>{{{ $item->description }}}</em></td>
											<td>{{ ($item->demarche_piece_name ? ('<span title="'.Lang::get ( 'admin/demarches/messages.piece.piece' ).'"><i class="fa fa-clipboard"></i>'.$item->demarche_piece_name.'</span>') : ($item->demarche_task_name ? ('<span title="'.Lang::get ( 'admin/demarches/messages.task.task' ).'"><i class="fa fa-tasks"></i>'.$item->demarche_task_name.'</span>') : '')) }}</td>
											<td>{{ EwbsActionRevision::graphicState($item->state) }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							@endif
						@endif
						</li>
						@endforeach
					<?php
					
					if (strlen ( $modelInstance->freeencoding_nostra_demarches )) {
						print ('<li><span class="fa fa-flash" title="Entrée libre"></span> ' . $modelInstance->freeencoding_nostra_demarches . '</li>') ;
					}
					?>
					</ul>
					<h5>Thématiques(s) administration</h5>
					<ul>
					<?php
					foreach ( $modelInstance->nostraThematiquesadm as $n ) {
						print ('<li>' . $n->title . '</li>') ;
					}
					if (strlen ( $modelInstance->freeencoding_nostra_thematiquesadm )) {
						print ('<li><span class="fa fa-flash" title="Entrée libre"></span> ' . $modelInstance->freeencoding_nostra_thematiquesadm . '</li>') ;
					}
					?>
					</ul>
					<hr />
					

				</div>
			</div>
		</div>
		@include('admin.ideas.partial-sidebar')
	</div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('.table-responsive.actions table.datatable').dataTable();
	});
</script>
@stop */ ?>