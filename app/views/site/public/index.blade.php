@extends('site.layouts.default', ['notFluid' => true]) {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<h2 class="text-center"><span class="text-primary">Synapse</span>, l'outil de pilotage des idées et projets de simplification administrative</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			{{ HTML::image('images/screenshot-dashboard.jpg', 'capture ecran du dashboard', ['class' => 'img-responsive']) }}
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<p>
				<strong>e-Wallonie-Bruxelles Simplification</strong> est l’organe
				en charge de la simplification administrative et de
				l’administration électronique en Wallonie et en Fédération
				Wallonie-Bruxelles.
			</p>
			<p>
				<strong>eWBS</strong> coordonne toutes les mesures de lutte contre
				la complexité et les contraintes administratives imposées aux
				usagers des services publics. Il aide, mobilise et incite les
				administrations et les organismes publics wallons et de la
				Fédération Wallonie-Bruxelles à mettre en oeuvre les mesures
				proposées.
			</p>
			<p>
				Fonctionnellement, <strong>eWBS</strong> est rattaché au
				Secrétariat général du Service Public de Wallonie et au
				Secrétariat général du Ministère de la Fédération
				Wallonie-Bruxelles. Il relève directement de l’autorité
				hiérarchique des secrétaires généraux. Par ailleurs, pour
				accroître l’efficacité et le rayonnement de la dynamique de
				simplification, <strong>eWBS</strong> s’appuie sur un réseau de
				correspondants administratifs désignés au sein de chaque
				administration et organisme d’intérêt public. <strong>eWBS</strong>
				travaille en étroite collaboration avec les administrations, les
				cabinets ministériels et les partenaires sociaux. Pour la période
				2010 – 2014, les activités prises en charge par <strong>eWBS</strong>
				sont guidées principalement par le plan « Ensemble Simplifions ».
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center">Principales fonctionnalités</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<span class="pull-right"><i class="fa fa-2x fa-lightbulb-o color-primary"></i></span>
					<h5>Gestion des projets de simplif'</h5>
				</div>
				<div class="content">
					Collecte et priorisation des projets de simplification adminsitrative.
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<span class="pull-right"><i class="fa fa-2x fa-briefcase color-success"></i></span>
					<h5>Pilotage des démarches</h5>
				</div>
				<div class="content">
					Suivi et reporting du catalogue des démarches administratives.
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<span class="pull-right"><i class="fa fa-2x fa-calculator color-danger"></i></span>
					<h5>Gains de charge</h5>
				</div>
				<div class="content">
					Analyse et calculs des gains de charges grâce à la méthode SCM Light.
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<span class="pull-right"><i class="fa fa-2x fa-magic"></i></span>
					<h5>Suivi des actions</h5>
				</div>
				<div class="content">
					Gestion du travail day-to-day des équipes de eWBS.
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<span class="pull-right"><i class="fa fa-2x fa-wpforms"></i></span>
					<h5>Formulaires électroniques</h5>
				</div>
				<div class="content">
					Inventaire des formulaires électroniques et apport d'informations.
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="header">
					<span class="pull-right"><i class="fa fa-2x fa-gears"></i></span>
					<h5>Reporting</h5>
				</div>
				<div class="content">
					Automatisation du reporting et des relevés auprès des comités de direction.
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center">Vous avez dit <em>logiciel libre</em>?</h3>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="block-flat">
				<div class="content no-padding text-center">
					<a href="http://www.ensemblesimplifions.be" target="_blank">
						{{ HTML::image('images/logo-ewbs.png', 'logo ewbs') }}
						<br/><br/>
						Par e-Wallonie-Bruxelles Simplification
					</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="content no-padding text-center">
					<a href="https://www.gnu.org/licenses/quick-guide-gplv3.html" target="_blank">
						{{ HTML::image('images/logo-gplv3.png', 'logo gplv3') }}
						<br/><br/>
						Distribué sous licence GNU/GPLv3
					</a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="content no-padding text-center">
					<a href="https://github.com/ewbs/synapse" target="_blank">
						{{ HTML::image('images/logo-github.png', 'logo githb') }}
						<br/><br/>
						Forkez-nous sur github!
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
