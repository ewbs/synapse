<?php 
/**
 * Template définissant le container (contenu au sein du wrapper) en mode non fluide), qui inclut :
 * - les notifications
 * - le page-head (titre)
 * - le footer
 * 
 * Ce template referme par défaut le menu principal. Il est surtout dédié à des écrans généraux comme la HP, contact,...
 * 
 * Le template prévoit qu'un template qui l'étend puisse compléter les sections :
 * - title (titre de la page en partie centrale, mais aussi utilisé par base.blade pour le title du head)
 * - content (le contenu de la page)
 */
?>
@extends('site.layouts.wrapper')
@section('container')
@include('notifications')
<div class="cl-mcont">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2><span class="{{$sectionIcon}}"></span>@yield('title')</h2>
			</div>
		</div>
		@yield('content')
		@include('site.layouts.partial.footer')
	</div>
</div>
@stop

@section('containerscripts')
<script type="text/javascript">
	$(document).ready(function(){
		$("#sidebar-collapse").trigger("click"); //fermer la sidebar
	});
</script>
@stop
