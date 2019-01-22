<?php
/* Affichage des fonctionnalités disponibles pour l'instance du modèle courant
 * @var ModelInstance $modelInstance
 * @var array $features
 */
	$currentUrl=Request::fullUrl();
	$currentUrl=str_replace('http://','',$currentUrl);
	$currentUrl=str_replace('https://','',$currentUrl);
?>

<div class="btn-group" role="group">
	<a href="{{{ (isset($returnTo) && $returnTo) ? route($returnTo) : $modelInstance->routeGetIndex() }}}" title="Retour à la liste" class="btn btn-flat btn-cancel" ><span class="fa fa-arrow-left"></span></a>
	@foreach ($features as $feature)
		@if (isset($feature['sub'])) {{-- sous menu --}}
			<div class="btn-group no-margin">
				<?php
				$submenu_active = false;
				foreach($feature['sub'] as $sub){
					if(strpos($sub['url'],$currentUrl) !== false) $submenu_active = true;
				}
				?>
				<button type="button" class="btn btn-flat btn-default dropdown-toggle {{ $submenu_active ? 'active' : '' }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					{{ isset($feature['icon']) ? '<span class="fa fa-'.$feature['icon'].'"></span>' : '' }} <span class="hidden-xs hidden-sm hidden-md">{{$feature['label']}} </span><span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					@foreach ($feature['sub'] as $sub)
						<li class="{{(strpos($sub['url'],$currentUrl) !== false) ? 'active' : ''}}">
							<a href="{{$sub['url']}}" title="{{$sub ['label']}}">
								{{ isset($sub['icon']) ? '<span class="fa fa-'.$sub['icon'].'"></span>' : '' }}
								{{$sub['label']}}
							</a>
						</li>
					@endforeach
				</ul>
			</div>
		@else {{-- élément de menu "normal" --}}

			<a
					href="{{ $feature['url'] }}"
					class="btn btn-flat {{ isset($feature['class']) ? $feature['class'] : 'btn-default' }}{{ (strpos($feature['url'],$currentUrl) !== false)?' active':'' }}"
					title="{{$feature['label']}}"
			>
				<span class="{{ isset($feature['icon']) ? 'fa fa-'.$feature['icon'] : 'btn-primary' }}"></span>
				{{--{{ ($feature['url']==$currentUrl) ? '' : '<span class="hidden-xs hidden-sm hidden-md">'.$feature['label'].'</span>'}}--}}
				<span class="hidden-xs hidden-sm hidden-md">{{$feature['label']}}</span>
			</a>
		@endif
	@endforeach
</div>