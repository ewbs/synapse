<?php 
/**
 * Template définissant le wrapper (contenu du body) qui inclut :
 * - la navbar
 * - la sidebar
 * - et délègue l'affichage du container à un template qui l'étendra
 * 
 * Le template prévoit qu'un template qui l'étend puisse compléter la section "wrapper".
 */
?>
@extends('site.layouts.base')

@section('wrapperstyles')
{{ HTML::style('js/jquery.nanoscroller/nanoscroller.css') }}
{{ HTML::style('js/jquery.datatables/bootstrap-adapter/css/datatables.css') }}
{{ HTML::style('js/bootstrap.switch/bootstrap-switch.css')}}
{{ HTML::style('js/jquery.icheck/skins/square/blue.css') }}
{{ HTML::style('js/jquery.select2/css/select2.min.css') }}
{{ HTML::style('js/bootstrap.switch/bootstrap-switch.css') }}
{{ HTML::style('js/bootstrap.slider/css/slider.css') }}
{{ HTML::style('js/dropzone/css/basic.css') }}
{{ HTML::style('js/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css') }}
{{ HTML::style('js/jquery.ui/jquery-ui-1.10.4.custom.min.css') }}
@stop

@section('wrapper')
{{-- navbar --}}
<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="fa fa-gear"></span>
			</button>
			<a class="navbar-brand" href="#"><span>Synapse</span></a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li {{(Request::is('/') ? ' class="active"' : '')}}><a href="{{route('getIndex')}}"><span class="fa fa-home"></span> Accueil</a></li>
				<li {{(Request::is('contact') ? ' class="active"' : '')}}><a href="{{route('getContact')}}"><span class="fa fa-envelope"></span> Contact</a></li>
				@if (Auth::check() && count(Auth::user()->EWBSMember))
					<li {{(Request::is('tracker') ? ' class="active"' : '')}}><a href="http://tracking.e-wbs.be" target="_blank"><span class="fa fa-bug"></span> Signaler un bug</a></li>
				@endif
			</ul>
			<ul class="nav navbar-nav navbar-right user-nav">
				@if (Auth::check())
				<li class="dropdown profile_menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						{{Gravatarer::make( ['email' => Auth::user()->email, 'size' => 30, 'secured' => true] )->html()}}{{{ Auth::user()->username }}} <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="{{route('userGetIndex')}}">Mon profil</a></li>
						<li><a href="{{ route('userGetFilters') }}">Mes filtres</a></li>
						<li class="divider"></li>
						<li><a href="{{route('userGetLogout')}}">Déconnexion</a></li>
					</ul>
				</li>
				@else
				<li class="button"><a href="{{route('userGetLogin')}}" title="Connexion"><span class="fa fa-user"></span></a></li>
				@endif
			</ul>
		</div>
	</div>
</div>

<div id="cl-wrapper" class="@if (Auth::check()) fixed-menu @endif">
	@if (Auth::check()) @include('site.layouts.sidebar') @endif
	@yield('container')
</div>
@stop

@section('wrapperscripts')
{{ HTML::script('js/jquery.ui/jquery-ui.js') }}
{{ HTML::script('js/jquery.ui/jquery-ui-1.10.4.custom.min.js') }}
{{ HTML::script('js/jquery.nanoscroller/jquery.nanoscroller.js') }}
{{ HTML::script('js/jquery.datatables/jquery.datatables.min.js') }}
{{ HTML::script('js/jquery.datatables/jquery.datatables.refresh.js') }}
{{ HTML::script('js/jquery.datatables/jquery.datatables.columndata.js') }}
{{ HTML::script('js/jquery.datatables/bootstrap-adapter/js/datatables.js') }}
{{ HTML::script('js/bootstrap.switch/bootstrap-switch.min.js') }}
{{ HTML::script('js/jquery.icheck/icheck.min.js') }}
{{ HTML::script('js/jquery.select2/js/select2.min.js') }}
{{ HTML::script('js/jquery.select2/js/i18n/fr.js') }}
{{ HTML::script('js/bootstrap.switch/bootstrap-switch.min.js') }}
{{ HTML::script('js/bootstrap.slider/js/bootstrap-slider.js') }}
{{ HTML::script('js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js') }}
{{ HTML::script('js/bootstrap.datetimepicker/js/locales/bootstrap-datetimepicker.fr.js') }}
{{ HTML::script('js/jquery.flot/jquery.flot.min.js') }}
{{ HTML::script('js/jquery.flot/jquery.flot.pie.min.js') }}
{{ HTML::script('js/jquery.flot/jquery.flot.resize.min.js') }}
{{ HTML::script('js/jquery.flot/jquery.flot.labels.js') }}
{{ HTML::script('js/jquery.easy-overlay/jquery.easy-overlay.js') }}
{{ HTML::script('js/jquery.number/jquery.number.min.js') }}
{{ HTML::script('js/jquery.nestable/jquery.nestable.js') }}
{{ HTML::script('js/synapse/modIdeas.js') }}
{{-- HTML::script('js/synapse/modDemarches-list.js') --}}
{{ HTML::script('js/synapse/modDemarches-edit.js') }}
@stop