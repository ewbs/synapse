<li><a href="{{route('damusGetDetailThematiqueadm', $thematique->id)}}">{{ $thematique->title }}</a></li>
@if ( count ( $thematique->children ) > 0 )
	<ul>
		@foreach ( $thematique->children as $thematique ) 
			@include('admin.damus.partials.thematiqueadm', $thematique)
		@endforeach
	</ul>
@endif
