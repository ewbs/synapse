<li><a href="{{route('damusGetDetailPublic', $public->id)}}">{{ $public->title }}</a></li>
@if ( count ( $public->children ) > 0 )
	<ul>
		@foreach ( $public->children as $public ) 
			@include('admin.damus.partials.public', $public)
		@endforeach
	</ul>
@endif
