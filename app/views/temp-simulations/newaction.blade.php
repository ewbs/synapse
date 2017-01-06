@extends('temp-simulations.layouts.default')
@section('title')
    Bienvenue
    @parent
@stop



{{-- Content --}}
@section('content')

<div class="modal fade noAuto colored-header in" id="servermodal" role="dialog" aria-labelledby="servermodal-title" aria-hidden="false" style="display: block;">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form class="form-horizontal" method="post" autocomplete="off" action="https://synapse.dev.wallonie.be/admin/demarches/179/actions/create">
                <input type="hidden" name="_token" value="0Z2l4NUAnG1XunlDpKqmNjtnGK4r3tdAjzQEFhnV">
                <input type="hidden" name="fromTriggerUpdate" value="">
                <div class="modal-header">
                    <button class="close" aria-label="Fermer" type="button" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                    <h3 class="modal-title" id="servermodal-title">
                        Création d'une action
                        <span></span>
                    </h3>
                </div>
                <div class="modal-body">

                    <!-- Nom -->
                    <div class="form-group ">
                        <label class="col-md-2 control-label" for="name">Nom</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="name" value="Lisibilité sur le formulaire" placeholder="Nom">

                        </div>
                    </div>
                    <!-- ./ Nom -->

                    <!-- Description -->
                    <div class="form-group ">
                        <label class="col-md-2 control-label" for="description">Description</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="description" placeholder="Description" rows="6">Steph, peux tu prévoir de revoir les 3 premiers blocs de ce formulaire ? Merci.</textarea>

                        </div>
                    </div>
                    <!-- ./ Description -->

                    <!--Service -->
                    <div class="form-group ">
                        <label class="col-md-2 control-label" for="ewbsservice">
                            Service
                        </label>
                        <div class="col-md-10">
                            <select multiple name="ewbsservice" class="select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option></option>
                                <option selected>Accompagnement à la dématérialisation de formulaire</option>
                            </select>
                        </div>
                    </div>
                    <!-- ./ Service -->

                    <!-- Taxonomie -->
                    <div class="form-group ">
                        <label class="col-md-2 control-label" for="taxonomy">
                            Tags
                        </label>
                        <div class="col-md-10">
                            <select multiple name="taxonomy" class="select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                <option></option>
                                <option selected>(Expertise) Démarche et services en ligne</option>
                                <option selected>(E-Gov/e-Service) gestionnaire d'<événement></événement></option>
                            </select>
                        </div>
                    </div>
                    <!-- ./ Taxonomie -->


                    <!-- State -->
                    <input type="hidden" name="state" value="todo">
                    <!-- ./ State -->

                </div>
                <div class="modal-footer">
                    <button type="submit" name="action" value="save" class="btn btn-primary">Ajouter l'action</button>
                    <button class="btn btn-default" type="button" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop