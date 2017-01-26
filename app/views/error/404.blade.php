@extends('site.layouts.base')

@section('title')Page non trouvée @stop

@section('bodyclass')texture @stop

@section('wrapper')
<div id="cl-wrapper" class="error-container">
	<div class="page-error">
		<h1 class="number text-center">404</h1>
		<h2 class="description text-center">Désolé mais cette page n'existe pas.</h2>
		<h3 class="text-center">Un petit tour par la <a href="{{{URL::secure('/')}}}">page d'accueil</a>?</h3>
	</div>
	<div class="text-center copy">
		2015 <a href="http://www.ensemblesimplifions.be">eWBS</a>
	</div>
</div>
@stop