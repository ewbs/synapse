<?php 
/**
 * Template définissant le container (contenu au sein du wrapper) en mode fluide, qui inclut :
 * - les notifications
 * - le page-head (titre + fonctions de navigation de l'élément courant [=features])
 * - le footer
 * 
 * Le template prévoit qu'un template qui l'étend puisse compléter les sections :
 * - title (titre de la page en partie centrale, mais aussi utilisé par base.blade pour le title du head)
 * - content (le contenu de la page)
 */
?>
@extends('site.layouts.wrapper')
@section('container')
<div class="container-fluid" id="pcont">
	@include('notifications')
	<div class="page-head">
		<div class="row">
			<div class="{{isset($features)?'col-md-8':'col-md-12'}}">
				<h2>
					<span class="fa fa-{{$sectionIcon}}"></span>
					@yield('title')
				</h2>
			</div>
			@if(isset($features))
			<div class="col-md-4">
				<div class="pull-right">
					@include('admin.modelInstance.partial-features')
				</div>
			</div>
			@endif
		</div>
	</div>
	<div class="cl-mcont">
		@yield('content')
		@include('site.layouts.partial.footer')
	</div>
</div>
@stop