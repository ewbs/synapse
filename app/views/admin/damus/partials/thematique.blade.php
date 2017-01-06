<li><a href="{{route('damusGetDetailThematiqueabc', $thematique->id)}}">{{ $thematique->title }}</a></li>
@if ( count ( $thematique->children ) > 0 )
	<ul>
		@foreach ( $thematique->children as $thematique ) 
			@include('admin.damus.partials.thematique', $thematique)
		@endforeach
	</ul>
@endif
