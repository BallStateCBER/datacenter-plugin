<?php if (Configure::read('debug') != 0): ?>
	<div>
		<a href="#sql_dump" id="sql_dump_toggler" onclick="$('#sql_dump').toggle()">Show SQL dump</a>
	</div>
	<div id="sql_dump" style="display: none; background-color: #fff; padding: 10px; width: 1000px; margin: 20px auto auto;">
		<a name="sql_dump"></a>
		<?php echo $this->element('sql_dump'); ?>
	</div>
<?php endif; ?>