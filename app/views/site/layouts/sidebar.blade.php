<div class="cl-sidebar" data-position="right" data-step="1" data-intro="Menu utilisateur">
	<div class="cl-toggle">
		<i class="fa fa-bars"></i>
	</div>
	<div class="cl-navblock">
		<div class="menu-space">
			<div class="content">
				<ul class="cl-vnavigation">
					@each('site.layouts.partial.navigation-menu', $sidebarMenu, 'item')
				</ul>
			</div>
		</div>
		<div class="text-right collapse-button" style="padding: 7px 9px;">
			<button id="sidebar-collapse" class="btn btn-default" style="">
				<i style="color: #fff;" class="fa fa-angle-left"></i>
			</button>
		</div>
	</div>
</div>