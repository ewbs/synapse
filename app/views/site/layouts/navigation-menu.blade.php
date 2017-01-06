<li{{(array_key_exists('active', $item)) ? ' class="active"' : ''}}>
	<a href="{{ array_key_exists('route', $item) ? URL::route($item['route']) : '#' }}">
		@if (array_key_exists('icon', $item))<i class="fa fa-{{ $item['icon'] }}"></i>@endif
		<span>{{{$item['label']}}}</span>
	</a>
	@if (array_key_exists('submenu', $item))
	<ul>
		@each('site.layouts.navigation-menu', $item['submenu'], 'item')
	</ul>
	@endif
</li>

