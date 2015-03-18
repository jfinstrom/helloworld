<form action="" method="post" class="fpbx-submit" id="hwform" name="hwform" data-fpbx-delete="config.php?display=helloworld&action=delete&id=<?php echo $id?>">
<input type="hidden" name='action' value="<?php echo $id?'edit':'add' ?>">
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
						<input type="text" class="form-control" id="subject" name="subject" value="<?php echo $subject?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<span id="subject-help" class="help-block fpbx-help-block"><?php echo _("Enter a subject for your note")?></span>
		</div>
	</div>
</div>
<!--END Subject-->
<!--Body-->
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
						<textarea class="form-control" id="body" name="body"><?php echo $body?>
						</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<span id="body-help" class="help-block fpbx-help-block"><?php echo _("Enter the contents of your note")?></span>
		</div>
	</div>
</div>
<!--END Body-->
</form>