<?php
	/**
	 * c'est la version vide des infos nostra (car démarche créer hors nostra)
	 */
	/**
 * @var Idea $modelInstance
 */
?>
<div class="block-flat">
	<div class="header">
		<h4>
			<span class="fa fa-connectdevelop"></span> Infos Nostra
		</h4>
	</div>
	<div class="content">
		<div class="alert alert-danger">Cette démarche n'est pas présente dans Nostra. Elle n'est renseignée que dans Synapse</div>
		<ul class="list-group">
			<li class="list-group-item">
				<p><strong>Id Nostra : /</strong></p>
				<p>
					<strong>Publics cibles : /</strong>
				</p>
				<p>
					<strong>Thématiques usager : /</strong>
				</p>
				<p>
					<strong>Thématiques administration : /</strong>
				</p>
				<p>
					<strong>Evénements déclencheurs : /</strong>
				</p>
			</li>
			<li class="list-group-item"><strong>Formulaires :</strong>
				/
			</li>
			
			<li class="list-group-item"><strong>Documents :</strong>
				/
			</li>
			<li class="list-group-item"><strong>Simplifié : /</strong></li>
			<li class="list-group-item"><strong>Version allemande : /</strong></li>
			<li class="list-group-item"><strong>Type : /</strong></li>
		</ul>
		<p>
		</p>



	</div>

	<div class="header">
		<h4>
			Infos temporaires
		</h4>
	</div>

	<div class="content">
		<ul class="list-group">
			<li class="list-group-item">
				<p>
					@if(isset($manage) && $manage)
						<strong>Publics cibles : </strong>
						<div class="form-group" id="demarches_nostralight_public">
							<div class="col-md-12">
								<select class="select2" multiple name="nostra_publics[]" id="nostra_publics">
									<?php
									// recherche de l'élément à selectionner
									$selectedNostraPublics = [];
									if ($modelInstance)
										$selectedNostraPublics = $aSelectedNostraPublics; //passée par le controlleur (voir function getManage());
									if (Input::old('nostra_publics'))
										$selectedNostraPublics = Input::old('nostra_publics');
									?>
									@foreach($aNostraPublics as $public)
										<option value="{{$public->id}}"{{ in_array($public->id, $selectedNostraPublics) ? ' selected': '' }}>{{$public->title}}</option>
									@endforeach
								</select>
							</div>
						</div>
					@else
						<strong>Publics cibles : </strong> {{ implode(', ',$modelInstance->getNostraPublicsNames()) }}
					@endif
				</p>
				<p>
					@if(isset($manage) && $manage)
						<strong>Thématiques usager : </strong>
						<div class="form-group" id="demarches_nostralight_thematiquesabc">
							<div class="col-md-12">
								<select class="select2" multiple name="nostra_thematiquesabc[]" id="nostra_thematiquesabc">
									<?php
									// recherche de l'élément à selectionner
									$selectedNostraThematiqueabc = [];
									if ($modelInstance)
										$selectedNostraThematiqueabc = $aSelectedNostraThematiqueabc; //passée par le controlleur (voir function getManage());
									if (Input::old('nostra_thematiqueabc'))
										$selectedNostraThematiqueabc = Input::old('nostra_thematiqueabc');
									?>
									@foreach($aNostraThematiqueabc as $thematiqueabc)
										<option value="{{$thematiqueabc->id}}"{{ in_array($thematiqueabc->id, $selectedNostraThematiqueabc) ? ' selected': '' }}>{{$thematiqueabc->title}}</option>
									@endforeach
								</select>
							</div>
						</div>
					@else
						<strong>Thématiques usager : </strong> {{ implode(', ',$modelInstance->getNostraThematiqueabcNames()) }}
					@endif
				</p>
				<p>
					@if(isset($manage) && $manage)
						<strong>Thématiques administration : </strong>
						<div class="form-group" id="demarches_nostralight_thematiquesadm">
							<div class="col-md-12">
								<select class="select2" multiple name="nostra_thematiquesadm[]" id="nostra_thematiquesadm">
									<?php
									// recherche de l'élément à selectionner
									$selectedNostraThematiqueadm = [];
									if ($modelInstance)
										$selectedNostraThematiqueadm = $aSelectedNostraThematiqueadm; //passée par le controlleur (voir function getManage());
									if (Input::old('nostra_thematiqueadm'))
										$selectedNostraThematiqueadm = Input::old('nostra_thematiqueadm');
									?>
									@foreach($aNostraThematiqueadm as $thematiqueadm)
										<option value="{{$thematiqueadm->id}}"{{ in_array($thematiqueadm->id, $selectedNostraThematiqueadm) ? ' selected': '' }}>{{$thematiqueadm->title}}</option>
									@endforeach
								</select>
							</div>
						</div>
					@else
						<strong>Thématiques administration : </strong> {{ implode(', ',$modelInstance->getNostraThematiqueadmNames()) }}
					@endif
				</p>
			</li>
		</ul>


	</div>

</div>