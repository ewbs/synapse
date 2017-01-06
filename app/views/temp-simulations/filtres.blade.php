@extends('temp-simulations.layouts.default')
@section('title')
	Bienvenue
	@parent
@stop



{{-- Content --}}
@section('content')
	<div class="cl-mcont">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<ul class="nav nav-pills">
				    <li class="active"><a href="/simulations/"><span class="fa fa-home"></span> Mon dashboard</a></li>
					<li><a href="/simulations/projets"><span class="fa fa-lightbulb-o"></span> Mes projets</a></li>
					<li><a href="/simulations/demarches"><span class="fa fa-briefcase"></span> Mes démarches</a></li>
					<li><a href="/simulations/actions"><span class="fa fa-magic"></span> Mes actions</a></li>
					<li><a href="/simulations/charges"><span class="fa fa-calculator"></span> Mes charges administratives</a></li>
                </ul>
            </div>
        </div>

		<div class="row">

                        <div class="col-md-12">
                            <div class="block-flat">
                                <div class="header">
                                    <h3>Mes filtres</h3>
                                </div>
                                <div class="content">
                                    <p>
                                        Les filtres vous permettent de personnaliser les données affichées dans Synapse.<br/>
                                        Les filtres fontionnent par union, pas par intersection.
                                        Par exemple, si vous sélectionnez les administrations DGO6 et DGO7, le filtre vous retournera les élements relatifs
                                        à la DGO6 <strong>et</strong> à la DGO7.
                                    </p>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4>Par administrations</h4>
                                                <p>N'afficher que les éléments relatifs à ces administrations:</p>
                                                <select class="select2" multiple name="administrations[]" id="administrations">
                                                    @foreach(Region::all() as $region)
                                                        <optgroup label="{{$region->name}}">
                                                            @foreach($region->administrations()->orderBy('name')->get() as $administration)
                                                                <option value="{{$administration->id}}">{{$administration->name}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Par public cible</h4>
                                                <p>N'afficher que les éléments relatifs à ces publics:</p>
                                                <select class="select2" multiple name="administrations[]" id="administrations">
                                                    @foreach(NostraPublic::orderBy('title')->get() as $public)
                                                        <option value="{{$public->id}}">{{$public->title}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Par tags</h4>
                                                <p>N'afficher que les éléments relatifs à ces tags:</p>
                                                <select class="select2" multiple name="tags[]" id="tags">
                                                    <optgroup label="Expertise">
                                                        <option>(Expertise) Approche intégrée</option>
                                                        <option>(Expertise) Charges administratives</option>
                                                        <option>(Expertise) Utilisation du droit</option>
                                                        <option>(Expertise) Sécurité et vie privée</option>
                                                    </optgroup>
                                                    <optgroup label="eGov/eServices">
                                                        <option>(eGov/eServices) ABC des démarches</option>
                                                        <option>(eGov/eServices) Dématérialisation</option>
                                                        <option>(eGov/eServices) BCED-wi</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input class="btn btn-primary" value
                                                ="Sauvegarder mes filtres"></input>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

        </div>

    </div>
@stop
