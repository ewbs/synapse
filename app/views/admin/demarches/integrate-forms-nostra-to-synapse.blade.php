<?php
/**
 * Modale permettant d'intégrer les formulaires nostra à la démarche
 * @var Demarche $demarche
 * @var array aIdeas
 */
?>

@extends('site.layouts.container-fluid')
@section('title')Intégrer les formulaires Nostra à la démarche : <br> <em>{{ $demarche->name()}}</em>@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="post" autocomplete="off" action="{{ route('demarchesIntegrateFormsNostraToSynapsePost', $demarche->id) }}">
                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
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
                <br> <br>
                <table style="width: 500px">
                    <thead>
                    <tr>
                        <th>Lier ce formulaire</th>
                        <th>Id du fomulaire Nostra</th>
                        <th>Intégré dans Synapse</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($nostra_forms as $form)
                        <tr>
                            <td><input type="checkbox" name="fid_{{$form->nostra_id}}" checked="checked" style="margin-right: 10px;"></td>
                            <td>#{{$form->nostra_id}}</td>
                            <td>@if(count($form->eform) == 0) Non @else Oui @endif </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br>
                <button type="submit" class="btn btn-primary" id="integrate_form">Intégrer et lier tous les formulaires cochés</button>
            </form>
        </div>
    </div>
@stop