<?php
/* Strings that can be encountered by users should be defined as gettext strings
 * This allows localization. We could put them inline but then
 * you would have to hunt the strings down in the file.  
 */
$heading = _("Hello World");
$param1 = _("Parameter 1");
$param2 = _("Parameter 2");
echo <<< HERE
<div class = 'container'>
	<div class = 'row'>
		<div class = col-xs-12>
			<div class = 'header'>
				<h2>$heading</h2>
			</div>
		</div>
	</div>
	<div class = 'row'>
		<div class = 'col-xs-4'>
			<b>$param1</b>
		</div>
		<div class = 'col-xs-4'>
			$vars[0]
		</div>
	</div>
	<div class = 'row'>
		<div class = 'col-xs-4'>
			<b>$param2</b>
		</div>
		<div class = 'col-xs-4'>
			$vars[1]
		</div>
	</div>
</div>
HERE;
?>
