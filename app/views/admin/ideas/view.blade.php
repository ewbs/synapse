<?php 
/**
 * @var Idea $modelInstance
 */
$ideaState = $modelInstance->getLastStateModification ()->ideaState;
?>

@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{DateHelper::year($modelInstance->created_at)}}-{{$modelInstance->id}}</span> {{$modelInstance->name}} @stop
@section('content')
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
				@foreach ( $modelInstance->ministers as $minister )
					@if($minister->canManage())
					<a href="{{$minister->routeGetView()}}"><span class="label label-default">{{$minister->name()}}</span></a>
					@else
					<span class="label label-default">{{$minister->name()}}</span>
					@endif
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
						<table class="table table-hover">
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
		$('.table-responsive.actions table').dataTable();
	});
</script>
@stop