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
						<form class="fpbx-submit" name="addNote" action="" method="post" onsubmit="" data-fpbx-delete="config.php?display=helloworld&action=delete">
							<!--Subject-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="subject"><?php echo _("Subject") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="subject"></i>
												</div>
												<div class="col-md-9">
													<input type="text" class="form-control" id="subject" name="subject" value="">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="subject-help" class="help-block fpbx-help-block"><?php echo _("Enter a subject for this note")?></span>
									</div>
								</div>
							</div>
							<!--END SUBJECT-->
							<!--BODY-->
							<div class="element-container">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="form-group">
												<div class="col-md-3">
													<label class="control-label" for="body"><?php echo _("Body") ?></label>
													<i class="fa fa-question-circle fpbx-help-icon" data-for="body"></i>
												</div>
												<div class="col-md-9">
													<textarea class="form-control" id="body" name="body" rows="15"></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<span id="body-help" class="help-block fpbx-help-block"><?php echo _("Enter your note")?></span>
									</div>
								</div>
							</div>
							<!--END BODY-->
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm-3 hidden-xs bootnav">
				<?php show_view(__DIR__.'/rnav.php')?>
		</div>
	</div>
</div>
