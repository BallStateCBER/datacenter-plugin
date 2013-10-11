<?php 
	/* This creates the hidden #flash_messages container and fills it with 
	 * flash messages and displayed via a javascript animation if there are
	 * messages to display. Regardless, the container is put onto the page
	 * so that asyncronous activity can load messages into it as needed. */
	if (! empty($flash_messages)) {
		$this->Js->buffer("showFlashMessages();");
	}
?>
<div id="flash_messages" style="display: none;">
	<div>
		<div>
			<div class="close"><a href="#" id="close_flash_msg">Close</a></div>
			<?php $this->Js->buffer("
				$('#close_flash_msg').click(function(event) {
					event.preventDefault();
					hideFlashMessages();
				});
			"); ?>
			<div class="messages_wrapper">
				<ul>
					<?php if (! empty($flash_messages)): ?>
						<?php foreach ($flash_messages as $msg): ?>
							<li class="<?php echo $msg['class']; ?>">
								<?php echo $msg['message']; ?>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
				<br class="clear" />
			</div>
		</div>
	</div>
</div>