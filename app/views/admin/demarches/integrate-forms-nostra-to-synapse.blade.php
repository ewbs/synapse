<?php
/**
 * Modale permettant d'intégrer les formulaires nostra à la démarche
 * @var Demarche $demarche
 * @var array aIdeas
 */
?>
<div class="modal fade noAuto colored-header" id="servermodal" role="dialog" aria-labelledby="servermodal-title" data-url="{{ route('demarchesIntegrateFormsNostraToSynapse', $demarche->id) }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </a>
                    <h3 class="modal-title" id="servermodal-title">Intégrer les formulaires Nostra à cette démarche Synapse<span></span></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Vous êtes sur le point d'intégrer et de lier des formulaires Nostra à cette démarche.  </strong> <br>
                            <br>
                            2 actions vont être réalisées : <br>
                            <br>
                            <ul>
                                <li>Vérifier que les formulaires ci-dessous sont intégrés dans Synapse.
                                Si ce n'est pas le cas, ils seront intégrer dans Synapse automatiquement.</li>
                                <li>Lier ces formulaires à cette démarche</li>
                            </ul>
                            <br>
                            <u>Liste des formulaires : </u>
                            {{$show_legend = false}}
                            <ul>
                                @foreach($nostra_forms as $form)
                                    @if(count($form->eform) == 0)
                                        <li>#{{$form->nostra_id}} <sup style="font-size: 10px;">*</sup>
                                            <?php $show_legend = true ?>
                                        </li>
                                    @else
                                        <li>#{{$form->nostra_id}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @if($show_legend) <i>* Pas encore intégré à Synapse</i> @endif
                            <br> <br>
                                <a href="{{ route('demarchesIntegrateFormsNostraToSynapsePost', $demarche->id) }}" class="btn btn-primary">Intégrer et lier tous les formulaires</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-default cancel" data-dismiss="modal">{{ Lang::get('button.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>