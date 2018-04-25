@extends('site.layouts.container-fluid')
@section('title')Intégration de tous les formulaires dans Synapse @stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="block-flat">
                <div class="content">
                    {{-- Delete Post Form --}}
                    <form id="deleteForm" class="form-horizontal" method="post"
                          action="{{route('eformsUndocumentedPostIntegrer')}}"
                          autocomplete="off">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                        <p>
                            Vous allez intégrer tous les formulaires dans Synapse simultanément <br/>
                            Cette action est irréverssible
                        </p>

                        <!-- Form Actions -->
                        <div class="form-group">
                            <div class="controls">
                                <a class="btn btn-cancel" href="{{route('eformsUndocumentedGetIndex')}}">{{Lang::get('button.cancel')}}</a>
                                <button type="submit" class="btn btn-primary">Confirmer cette action</button>
                            </div>
                        </div>
                        <!-- ./ form actions -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop