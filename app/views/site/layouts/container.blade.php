@extends('site.layouts.wrapper')
@section('container')
@include('notifications')
<div class="cl-mcont">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2><span class="{{$sectionIcon}}"></span>@yield('title')</h2>
			</div>
		</div>
		@yield('content')
		@include('site.layouts.partial.footer')
	</div>
</div>
@stop

@section('containerscripts')
<script type="text/javascript">
	console.log("container");
	$(document).ready(function(){
		$("#sidebar-collapse").trigger("click"); //fermer la sidebar
	});
</script>
@stop
