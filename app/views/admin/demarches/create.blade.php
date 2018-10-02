@extends('site.layouts.container-fluid')
@section('title')Création d'une démarche Synapse  @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="block-flat">
                <div class="header"><h3>Formulaire de création de la démarche</h3></div>
                <div class="content">
                    <form class="form-horizontal" method="post" autocomplete="off" action="{{route('demarchesPostCreate')}}">
                        <input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />

                        <div class="form-group {{{ $errors->has('title') ? 'has-error' : '' }}}">
                            <label class="col-md-2 control-label" for="title">Titre de la démarche</label>
                            <div class="col-md-10">
                                <input name="title" class="form-control"
                                       type="text" value="{{{Input::old('title') }}}" />
                                {{ $errors->first('title', '<span class="help-inline">Ce champ est obligatoire</span>') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="title">Issue du plan demat ?</label>
                            <div class="col-md-10">
                                <div class="switch">
                                    <input type="checkbox" name="from_plan_demat" id="from_plan_demat" value="1"  />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <a class="btn btn-cancel" href="{{route('demarchesGetIndex')}}">{{Lang::get('button.cancel')}}</a>
                            <button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop