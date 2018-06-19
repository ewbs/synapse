<?php
//TODO mettre ca dans les fichiers lang ;-)
$successWords = array (
		"Super!",
		"Magnifique!",
		"Yippie!",
		"Hourra!",
		"Génial!" 
);
$errorWords = array (
		"Ooops!",
		"Aie!",
		"Glups!",
		"Ho!",
		":-(" 
);

shuffle ( $successWords );
shuffle ( $errorWords );
?>
@if ( $message = (isset($success) ? $success : Session::get('success')) )
<div class="modal fade notification success colored-header" id="mod-success" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<div class="i-circle success">
						<i class="fa fa-check"></i>
					</div>
					<h4>{{$successWords[0]}}</h4>
					<p>
						@if(is_array($message)) @foreach ($message as $m) {{ $m }}<br />
						@endforeach @else {{ $message }} @endif
					</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@endif

@if ( $message = (isset($error) ? $error : Session::get('error')) )
<div class="modal fade notification error colored-header danger" id="mod-error" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="modalCompleteTitle2">Erreur<span></span></h3>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<div class="i-circle danger">
						<i class="fa fa-times"></i>
					</div>
					<h4>{{$errorWords[0]}}</h4>
					<p>
						@if(is_array($message)) @foreach ($message as $m) {{ $m }}<br />
						@endforeach @else {{ $message }} @endif
					</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@endif

@if ( $message = (isset($warning) ? $warning : Session::get('warning')) )
<div class="modal fade notification warning colored-header" id="mod-warning" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<div class="i-circle warning">
						<i class="fa fa-warning"></i>
					</div>
					<h4>Popopop!</h4>
					<p>
						@if(is_array($message)) @foreach ($message as $m) {{ $m }}<br />
						@endforeach @else {{ $message }} @endif
					</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Ok</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@endif

@if ( $message = (isset($info) ? $info : Session::get('info')) )
<div class="modal fade notification info colored-header" id="mod-info" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<div class="i-circle info">
						<i class="fa fa-info"></i>
					</div>
					<h4>Hep!</h4>
					<p>
						@if(is_array($message)) @foreach ($message as $m) {{ $m }}<br />
						@endforeach @else {{ $message }} @endif
					</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" data-dismiss="modal">Ok</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
@endif
