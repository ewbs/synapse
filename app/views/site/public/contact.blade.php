@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Contactez eWBS @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>Contact</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="content">
					<iframe width="100%" height="350" frameborder="0" scrolling="no"
						marginheight="0" marginwidth="0"
						src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=fr&amp;geocode=&amp;q=chauss%C3%A9e+de+charleroi+83b+salzinnes&amp;aq=&amp;sll=37.0625,-95.677068&amp;sspn=40.460237,86.572266&amp;ie=UTF8&amp;hq=&amp;hnear=Chauss%C3%A9e+de+Charleroi+83,+Namur+5000+Namur,+R%C3%A9gion+wallonne,+Belgique&amp;t=m&amp;z=14&amp;ll=50.462016,4.840701&amp;output=embed"></iframe>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="block-flat">
				<div class="header">
					<h3>En wallonie</h3>
				</div>
				<div clas="content">
					<p>
						Chaussée de Charleroi, 83b<br /> 5000 Salzinnes<br /> <br /> <abbr
							title="Téléphone">Tél.:</abbr> +32 (0)81 40 98 40<br /> <abbr
							title="Fax">Fax.:</abbr> +32 (0)81 40 98 41
					</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="block-flat">
				<div class="header">
					<h3>En Fédération Wallonie-Bruxelles</h3>
				</div>
				<div class="content">
					<p>
						Boulevard Léopold II, 44<br /> 1080 Bruxelles <br /> <abbr
							title="Téléphone">Tél.:</abbr> +32 (0)2 413 25 10<br /> <abbr
							title="Fax">Fax.:</abbr> +32 (0)2 413 35 10
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
