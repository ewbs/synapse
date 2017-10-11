<?php
	/**
	 * 
	 * @var Demarche $modelInstance
	 */

	/**
	 * A partir des nostra_forms et eforms liés à ce DemarcheEform, trouver les liaisons manquantes entre nostra et synapse.
	 * nb : Je sens que cela va devoir vite changer, je garde donc cette logique pr l'instant dans la vue par facilité.
	 */
	$nostra_forms=$modelInstance->nostraDemarche->nostraForms()->orderBy('nostra_forms.title')->get();
	$nostra_forms_ids=[];
	foreach($nostra_forms as $form) $nostra_forms_ids[]='#'.$form->nostra_id;
	
	$eforms=DemarcheEform::lastRevision()->joinEforms()->forDemarche($modelInstance)->get();
	$eforms_ids=[];
	foreach($eforms as $form) if($form->nostra_id) $eforms_ids[]='#'.$form->nostra_id;
	$moreFormsInSynapse=array_diff($eforms_ids, $nostra_forms_ids);
	$moreFormsInNostra=array_diff($nostra_forms_ids, $eforms_ids);
?>
<div class="block-flat">
	<div class="header">
		<h4>
			<span class="fa fa-connectdevelop"></span> Infos Nostra
		</h4>
	</div>
	<div class="content">
		<ul class="list-group">
			<li class="list-group-item">
				<p><strong>Id Nostra : </strong>{{ManageableModel::formatId($modelInstance->nostraDemarche->nostra_id)}}</p>
				<p>
					<strong>Publics cibles : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraPublicsNames()) }}
				</p>
				<p>
					<strong>Thématiques usager : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraThematiquesabcNames()) }}
				</p>
				<p>
					<strong>Thématiques administration : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraThematiquesadmNames()) }}
				</p>
				<p>
					<strong>Evénements déclencheurs : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraEvenementsNames()) }}
				</p>
			</li>
			<li class="list-group-item"><strong>Formulaires :</strong>
				@if ( !$nostra_forms->isEmpty() )
				<ul>
					@foreach ($nostra_forms as $form)
					<li>
						@if (strlen($form->url))<a href="{{$form->url}}" target="_blank">{{$form->title}}, {{$form->formatedId()}} <span class="fa fa-external-link"></span></a>
						@else {{$form->title}}, {{$form->formatedId()}}
						@endif
					</li>
					@endforeach
				</ul>
				@else Aucun
				@endif
				@if (isset($modelInstance) && ($moreFormsInSynapse||$moreFormsInNostra))
				<div class="alert alert-danger alert-white rounded">
					<div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
					<div><strong>Attention! </strong>Il y a une incohérence entre les formulaires renseignés dans Nostra et dans Synapse :</div>
					<ul>
					@if($moreFormsInSynapse)
					<li>Présent dans Synapse et pas dans Nostra :<br/>{{implode(', ', $moreFormsInSynapse)}}</li>
					@endif
					@if($moreFormsInNostra)
					<li>Présent dans Nostra et pas dans Synapse :<br/>{{implode(', ', $moreFormsInNostra)}}</li>
					@endif
					</ul>
				</div>
				@endif
			</li>
			
			<li class="list-group-item"><strong>Documents :</strong>
				@if (count($modelInstance->nostraDemarche->nostraDocuments))
				<ul>
					@foreach ($modelInstance->nostraDemarche->nostraDocuments as $doc)
					<li>
						@if (strlen($doc->url))
						<a href="{{$doc->url}}" target="_blank">{{$doc->title}}<span class="fa fa-external-link"></span></a>
						@else {{$doc->title}}
						@endif
					</li>
					@endforeach
				</ul>
				@else Aucun
				@endif
			</li>
			<li class="list-group-item"><strong>Simplifié : </strong>@if ($modelInstance->nostraDemarche->simplified > 0) oui @else non @endif</li>
			<li class="list-group-item"><strong>Version allemande : </strong>@if ($modelInstance->nostraDemarche->german_version > 0) oui @else non @endif</li>
			<li class="list-group-item"><strong>Type : </strong>
				@if ($modelInstance->nostraDemarche->type == 'droit') <span class="label label-primary">Droit</span> - Obligation - Information
				@elseif ($modelInstance->nostraDemarche->type == 'obligation') Droit - <span class="label label-primary">Obligation</span> - Information
				@else Droit - Obligation - <span class="label label-primary">Information</span>
				@endif
			</li>
		</ul>
		<p>
			<a class="btn btn-info servermodal" href="{{route('damusNostraGetDemarche',$modelInstance->nostraDemarche->nostra_id)}}">
				<span class="fa fa-question-circle"></span> Voir la démarche en détail
			</a>
			@if($modelInstance->canManage())
			<a class="btn btn-warning" href="{{route('damusGetRequestDemarche',$modelInstance->nostraDemarche->demarche->id)}}">
				<i class="fa fa-bug" aria-hidden="true"></i>Signaler une erreur
			</a>
			@endif
		</p>
	</div>
</div>