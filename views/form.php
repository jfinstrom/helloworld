<?php

echo <<< HERE
<form class="form-horizontal" method = 'POST'>
	<input type = 'hidden' name='display' value='helloworld'/>
	<input type = 'hidden' name='action' value='edit'/>
	<fieldset>
		<legend>Settings</legend>
		<div class="control-group">
			<label class="control-label" for="hwstring">Hello String</label>
			<div class="controls">
				<input id="hwstring" name="hwstring" placeholder="placeholder" class="input-medium" type="text">
				<p class="help-block">The hello world string</p>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="foo">Foo Value</label>
			<div class="controls">
				<input id="foo" name="foo" placeholder="placeholder" class="input-small" type="text">
				<p class="help-block">Value of foo</p>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="bar">Bar Value</label>
			<div class="controls">
				<input id="bar" name="bar" placeholder="placeholder" class="input-small" type="text">
				<p class="help-block">Value of bar</p>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="submit"></label>
			<div class="controls">
				<button id="submit" name="submit" class="btn btn-default">Submit</button>
			</div>
		</div>
	</fieldset>
</form>

HERE;
