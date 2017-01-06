<?php
/*
 * @var ManageableModel $modelInstance
 * @var boolean $manage
 */
if(!isset($manage)) $manage=false;
?>

<div class="block-flat">
    <div class="header">
        <h4><span class="fa fa-lightbulb-o"></span> Projets liés</h4>
    </div>
    <div class="content">
        @if(count($aIdeas)==0)
            @warning('Cette démarche n\'est liée à aucun projet de simplif\'. Il est recommandé d\'en créer au moins un pour assurer le suivi et le reporting.')
        @else
            <ul class="list-group">
                @foreach ($aIdeas as $idea)
                    <li class="list-group-item">
                        <strong>{{DateHelper::year($idea->created_at) . '-' . str_pad ( $idea->id, 4, "0", STR_PAD_LEFT )}}</strong>
                        <a href="{{ route('ideasGetView', $idea->id) }}">{{$idea->name}}</a>
                    </li>
                @endforeach
            </ul>
        @endif
        @if ($manage)
            <a class="btn btn-primary servermodal" href="{{route('demarchesIdeasGetLink', $modelInstance->id)}}">Lier la démarche à un projet</a>
        @endif
    </div>
</div>