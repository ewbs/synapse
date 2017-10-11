<?php 
/**
 * @var Illuminate\Database\Eloquent\Collection $regions
 * @var Illuminate\Database\Eloquent\Collection $publics
 * @var Illuminate\Database\Eloquent\Collection $taxonomyCategories
 * @var Illuminate\Database\Eloquent\Collection $expertises
 * @var array $selectedAdministrationsIds
 * @var array $selectedTagsIds
 * @var array $selectedPublicsIds
 * @var array $selectedExpertisesIds
 */
?>
@extends('site.layouts.container-fluid')
@section('title')Mes filtres @stop
@section('content')
    <div class="cl-mcont">
        @include('site.layouts.userdashboard-menu')


        <div class="row">

            <div class="col-md-12">
                <div class="block-flat">
                    <div class="header">
                        <h3>Mes filtres</h3>
                    </div>
                    <div class="content">
                        <p>
                            Les filtres vous permettent de personnaliser les données affichées dans Synapse.<br/>
                            Les filtres fontionnent par union puis par intersection.
                            {{-- #desactivatedtags--}}{{--Par exemple, si vous sélectionnez les administrations "DGO6" et "DGO7", le public cible "citoyen" et les tags "cabinet" et "formulaires",--}}
                            Par exemple, si vous sélectionnez les administrations "DGO6" et "DGO7" et le public cible "citoyen",
                            le filtre vous retournera les élements
                            <ul>
                                <li>relatifs à la DGO6 <strong>ou</strong> à la DGO7</li>
                                <li>et qui sont associés au public citoyen"</li>
                                {{-- #desactivatedtags--}}{{--<li><strong>et</strong> qui possèdent le tag "cabinet" <strong>ou</strong> le tag "formulaires"--}}
                            </ul>
                        </p>
                        <form method="post" autocomplete="off" action="{{ route('userPostFilters') }}">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
                            <!-- ./ csrf token -->
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Par administrations</h4>
                                    <p>N'afficher que les éléments relatifs à ces administrations:</p>
                                    <select class="select2" multiple name="administrations[]" id="administrations">
                                        @foreach($regions as $region)
                                            <optgroup label="{{$region->name}}">
                                                @foreach($region->administrations()->orderBy('name')->get() as $administration)
                                                    <option
                                                            {{ in_array($administration->id, $selectedAdministrationsIds) ? 'selected' : '' }}
                                                            value="{{$administration->id}}">{{$administration->name}}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <h4>Par public cible</h4>
                                    <p>N'afficher que les éléments relatifs à ces publics :</p>
                                    <select class="select2" multiple name="publics[]" id="publics">
                                        @foreach($publics as $public)
                                            <option
                                                    {{ in_array($public->id, $selectedPublicsIds) ? 'selected' : '' }}
                                                    value="{{$public->id}}">{{$public->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- #desactivatedtags--}}
                                <div class="col-md-4 hidden">
                                    <h4>Par tags</h4>
                                    <p>N'afficher que les éléments relatifs à ces tags :</p>
                                    <select class="select2" multiple name="tags[]" id="tags">
                                        @foreach ($taxonomyCategories as $category)
                                            <optgroup label="{{$category->name}}">
                                                @foreach ($category->tags as $tag)
                                                    <option
                                                            {{ in_array($tag->id, $selectedTagsIds) ? 'selected' : '' }}
                                                            value="{{$tag->id}}">{{$tag->name}}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <h4>Par action (identification)</h4>
                                    <p>N'afficher que les éléments relatifs à ces actions :</p>
                                    <select class="select2" multiple name="expertises[]" id="expertises">
                                        @foreach($expertises as $expertise)
                                            <option
                                                    {{ in_array($expertise->id, $selectedExpertisesIds) ? 'selected' : '' }}
                                                    value="{{$expertise->id}}">{{$expertise->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="btn btn-primary" type="submit" value="Sauvegarder mes filtres"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


@stop