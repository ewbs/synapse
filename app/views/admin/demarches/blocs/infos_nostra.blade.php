<div class="block-flat">
    <div class="header">
        <h4><span class="fa fa-connectdevelop"></span> Infos Nostra</h4>
    </div>
    <div class="content">
<ul class="list-group">
    <li class="list-group-item">
        <p><strong>Publics cibles : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraPublicsNames()) }}</p>
        <p><strong>Thématiques usager : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraThematiquesabcNames()) }}</p>
        <p><strong>Thématiques administration : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraThematiquesadmNames()) }}</p>
        <p><strong>Evénements déclencheurs : </strong> {{ implode(', ',$modelInstance->nostraDemarche->getNostraEvenementsNames()) }}</p>
    </li>
    <li class="list-group-item">
        <strong>Formulaires :</strong>
        @if ( count($modelInstance->nostraDemarche->nostraForms) )
            <ul>
                @foreach ($modelInstance->nostraDemarche->nostraForms as $form)
                    <li>
                        @if (strlen($form->url))
                            <a href="{{$form->url}}" target="_blank">{{$form->title}} <span class="fa fa-external-link"></span></a>
                        @else
                            {{$form->title}}
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            Aucun
        @endif
        {{-- on vérifie ici une différence éventuelle entre le nombre de formulaires présents dans Nostra et ceux attachés à la démarche dans Synapse --}}
        {{-- TODO: on ne compare qu'un nombre de formulaires pour le moment ... il serait bon de comparer les ids --}}
        @if (isset($modelInstance))
            @if ( count($modelInstance->nostraDemarche->nostraForms) != count($modelInstance->getLastRevisionEforms()) )
                <p class="color-danger">
                    <span class="badge badge-danger"><i class="fa fa-exclamation"></i></span>
                    Il y a une incohérence entre les formulaires renseignés dans Nostra et dans Synapse
    @endif
    @endif
    <li class="list-group-item">
        <strong>Documents :</strong>
        @if (count($modelInstance->nostraDemarche->nostraDocuments))
            <ul>
                @foreach ($modelInstance->nostraDemarche->nostraDocuments as $doc)
                    <li>
                        @if (strlen($doc->url))
                            <a href="{{$doc->url}}" target="_blank">{{$doc->title}} <span class="fa fa-external-link"></span></a>
                        @else
                            {{$doc->title}}
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            Aucun
        @endif
    </li>
    <li class="list-group-item">
        <strong>Simplifié : </strong> @if ($modelInstance->nostraDemarche->simplified > 0) oui @else non @endif
    </li>
    <li class="list-group-item">
        <strong>Version allemande : </strong>@if ($modelInstance->nostraDemarche->german_version > 0) oui @else non @endif
    </li>
    <li class="list-group-item">
        <strong>Type : </strong>
        @if ($modelInstance->nostraDemarche->type == 'droit')
            <span class="label label-primary">Droit</span> - Obligation - Information
        @elseif ($modelInstance->nostraDemarche->type == 'obligation')
            Droit - <span class="label label-primary">Obligation</span> - Information
        @else
            Droit - Obligation - <span class="label label-primary">Information</span>
        @endif
    </li>
</ul>
<p>
    <a class="btn btn-info servermodal" href="{{route('damusNostraGetDemarche',$modelInstance->nostraDemarche->nostra_id)}}">
        <span class="fa fa-question-circle"></span> Voir la démarche en détail
    </a>
    @if($modelInstance->canManage())
        <a class="btn btn-warning" href="{{route('damusGetRequestDemarche',$modelInstance->nostraDemarche->demarche->id)}}"><i class="fa fa-bug" aria-hidden="true"></i>Signaler une erreur</a>
    @endif
</p>
</div>
</div>