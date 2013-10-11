<div id="content_wrapper" class="inner_wrapper two_col">
	<div id="content_inner_wrapper">
		<div id="two_col_wrapper">
			<div id="menu_col_stretcher" class="col_stretcher"></div>
			<div id="content_col_stretcher" class="col_stretcher"></div>
			<div id="menu" class="col">
				<ul>
					<?php for ($n = 1; $n <= 20; $n++): ?>
						<li><?php echo $n; ?></li>
					<?php endfor; ?>
				</ul>
			</div>
			<div id="content" class="col">
				<?php echo $this->fetch('content'); ?>
				<br class="clear" />
			</div>
		</div>
		<?php echo $this->element('datacenter\sql_dump_toggler'); ?>
	</div>
	<br class="clear" />
</div>