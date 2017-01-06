<?php

/**
 * Formater un message d'alerte de type info via @info('msg') {
	$pattern = $compiler->createMatcher('info');
	$replace = '
	<div class="alert alert-info alert-white rounded">
		<div class="icon">
			<i class="fa fa-info-circle"></i>
		</div>
		<?php echo $2; ?>
	</div>';
	return preg_replace($pattern,$replace, $view);
});

/**
 * Formater un message d'alerte de type warning via @warning('msg')
 */
Blade::extend(function($view, $compiler) {
	$pattern = $compiler->createMatcher('warning');
	$replace = '
	<div class="alert alert-warning alert-white rounded">
		<div class="icon">
			<i class="fa fa-exclamation-triangle"></i>
		</div>
		<strong>Attention! </strong><?php echo $2; ?>
	</div>';
	return preg_replace($pattern,$replace, $view);
});

/**
 * Formater un message d'alerte de type error via @error('msg')
 */
Blade::extend(function($view, $compiler) {
	$pattern = $compiler->createMatcher('error');
	$replace = '
	<div class="alert alert-danger alert-white rounded">
		<div class="icon">
			<i class="fa fa-exclamation-triangle"></i>
		</div>
		<strong>Aie! </strong><?php echo $2; ?>
	</div>';
	return preg_replace($pattern,$replace, $view);
});
