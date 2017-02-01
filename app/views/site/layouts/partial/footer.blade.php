<footer>
	<div class="cl-mcont">
		<hr/>
		<p class="muted">
			Synapse est développé par <a href="http://www.ensemblesimplifions.be" target="_blank">eWBS</a><br />
			<small>Propulsé par <a href="http://www.laravel.com/" target="_blank">Laravel</a> | Mis en forme par <a href="http://getbootstrap.com/" target="_blank">Bootstrap</a> | Version {{Config::get('app.version')}}{{Config::getEnvironment()=='production'?'':'-'.Config::getEnvironment()}}</small>
		</p>
	</div>
</footer>