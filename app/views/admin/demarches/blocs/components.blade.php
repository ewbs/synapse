<?php
/*
 * @var ManageableModel $modelInstance
 * @var boolean $manage
 */
if(!isset($manage)) $manage=false;
if(!isset($minimal)) $minimal=false;
?>

<div class="block-flat">
    <div class="header">
        <h4><span class="fa fa-wpforms"></span> Formulaires</h4>
    </div>
    <div class="content">
        <div class="table-responsive">
            <table id="eforms-table" class="table table-hover datatable" data-ajaxurl="{{ route('demarchesEformsGetData', $modelInstance->id) }}?manage={{$manage?1:0}}&minimal={{$minimal?1:0}}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Annexes</th>
                    @if (!$minimal)
                    	<th>Id Nostra</th>
                        <th>Révision</th>
                        <th></th>
                    @endif
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        @if($manage)
            <div class="form-group">
                <a href="{{route('demarchesEformsGetCreate', $modelInstance->id)}}" class="btn btn-sm btn-primary servermodal" data-reload-datatable="#eforms-table"><i class="fa fa-plus"></i> Ajouter un formulaire</a>
            </div>
        @endif
    </div>
</div>


<div class="block-flat">
    <div class="header">
        <h4><span class="fa fa-clipboard"></span> Pièces & tâches</h4>
    </div>
    <div class="content">
        <div class="table-responsive">
            <table id="pieces-table" class="table table-hover datatable" data-ajaxurl="{{ route('demarchesPiecesGetData', $modelInstance->id) }}?manage={{$manage?1:0}}&minimal={{$minimal?1:0}}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
                <thead>
                <tr>
                    <th>Nom</th>
                    @if (!$minimal)
                        <th>Coût admin</th>
                        <th>Coût usager</th>
                    @endif
                    <th>Volume</th>
                    @if (!$minimal)
                        <th>Fréquence</th>
                    @endif
                    <th>Gain pot. admin</th>
                    @if (!$minimal)
                        <th>Gain eff. admin</th>
                    @endif
                    <th>Gain pot. usager</th>
                    @if (!$minimal)
                        <th>Gain eff. usager</th>
                        <th>Révision</th>
                        <th></th>
                    @endif
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        @if($manage)
            <div class="form-group">
                <a href="{{route('demarchesPiecesGetCreate', $modelInstance->id)}}" class="btn btn-sm btn-primary servermodal" data-reload-datatable="#pieces-table"><i class="fa fa-plus"></i> Ajouter une pièce</a>
            </div>
        @endif
        <hr/>
        <div class="table-responsive">
            <table id="tasks-table" class="table table-hover datatable" data-ajaxurl="{{ route('demarchesTasksGetData', $modelInstance->id) }}?manage={{$manage?1:0}}&minimal={{$minimal?1:0}}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
                <thead>
                <tr>
                    <th>Nom</th>
                    @if (!$minimal)
                        <th>Coût admin</th>
                        <th>Coût usager</th>
                    @endif
                    <th>Volume</th>
                    @if (!$minimal)
                        <th>Fréquence</th>
                    @endif
                    <th>Gain pot. admin</th>
                    @if (!$minimal)
                        <th>Gain eff. admin</th>
                    @endif
                    <th>Gain pot. usager</th>
                    @if (!$minimal)
                        <th>Gain eff. usager</th>
                        <th>Révision</th>
                        <th></th>
                    @endif
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        @if($manage)
            <div class="form-group">
                <a href="{{route('demarchesTasksGetCreate', $modelInstance->id)}}" class="btn btn-sm btn-primary servermodal" data-reload-datatable="#tasks-table"><i class="fa fa-plus"></i> Ajouter une tâche</a>
            </div>
        @endif
    </div>
</div>