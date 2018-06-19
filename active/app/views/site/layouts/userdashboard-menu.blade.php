{{--

    Ce menu est le menu "user" qui se trouve au dessus du contenu
    Il permet à l'utilisateur de naviguer dans les écrans qui sont filtrés et personnalisés.

--}}

<div class="row">
    <div class="col-sm-12 col-md-12">
        <ul class="nav nav-pills">
            <li {{Route::currentRouteName() == "adminDashboardGetIndex" ? 'class="active"' : ''}}>
                <a href="{{route('adminDashboardGetIndex')}}"><span class="fa fa-area-chart"></span> Mon dashboard</a>
            </li>
            <li {{Route::currentRouteName() == "adminDashboardGetMyIdeas" ? 'class="active"' : ''}}><a href="{{route('adminDashboardGetMyIdeas')}}"><span class="fa fa-lightbulb-o"></span> Mes projets</a></li>
            <li {{Route::currentRouteName() == "adminDashboardGetMyDemarches" ? 'class="active"' : ''}}><a href="{{route('adminDashboardGetMyDemarches')}}"><span class="fa fa-briefcase"></span> Mes démarches</a></li>
            <li {{Route::currentRouteName() == "adminDashboardGetMyActions" ? 'class="active"' : ''}}><a href="{{route('adminDashboardGetMyActions')}}"><span class="fa fa-magic"></span> Mes actions</a></li>
            <li {{Route::currentRouteName() == "adminDashboardGetMyCharges" ? 'class="active"' : ''}}><a href="{{route('adminDashboardGetMyCharges')}}"><span class="fa fa-calculator"></span> Mes charges administratives</a></li>
            <li {{Route::currentRouteName() == "userGetFilters" ? 'class="active"' : ''}}><a data-toggle="tooltip" data-placement="right" title="Modifier mes filtres" href="{{ route('userGetFilters') }}" title="Modifier mes filtres"><span class="fa fa-gear"></span></a></li>
        </ul>
    </div>
</div>