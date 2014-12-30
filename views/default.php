<?php
/*
 * Your views should be html as much as possible. Some php may be needed here
 * But do your best to pass informaton through the show_view array
 * Example: show_view('default.php',array( 'foo' => 'foo', 'bar' => 'bar'));
 * If this page is called with the above you can then insert <?php echo $foo?> and <?php echo $bar> 
 * 
 * You may also want to use php to set gettext translations. 
 * Anything text seen by a user should be passed this way so it can be translated.
 * Example:
 * <h1><?php echo _("My Cool Heading")?></h1>
 */ 
?>
<div class="container-fluid">
	<h1><?php echo _('Hello World')?></h1>
	<div class="well well-info">
		<?php echo _('This page is part of the Helloworld Demo module. This module does nothing of value')?>
	</div>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-9">
				<div class="fpbx-container">
					<div class="display full-border">
						<form>
						
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm-3 hidden-xs bootnav">
				<?php show_view(__DIR__.'/rnav.php')?>
		</div>
	</div>
</div>
