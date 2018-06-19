@extends('site.layouts.base')
@section('title')Page non trouvée @stop
@section('bodyclass')texture @stop
@section('wrapper')
<div id="cl-wrapper" class="error-container">
	<div class="container">
		<div class="page-error">
			<h1 class="number text-center">500</h1>
			<h2 class="description text-center">Désolé mais il semble qu'un problème serveur nous empêche de vous fournir ce que vous avez demandé.</h2>
			<h3 class="text-center">Un petit tour par la <a href="{{route('getIndex')}}">page d'accueil</a>?</h3>
		</div>
		@include('site.layouts.partial.footer')
	</div>
</div>
@stop