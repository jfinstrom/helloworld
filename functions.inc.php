<?php

function setup_smsdest() {
	if(version_compare(getVersion(), '12.0', '<')) {
		if(!interface_exists('BMO')) {
			include(dirname(__FILE__).'/BMO.class.php');
			include(dirname(__FILE__).'/Helloworld.class.php');
		}
		$smsdest = SMSDest::create();
		return $smsdest;
	} else {
		return FreePBX::create()->Smsdest;
	}
}
include('functions.inc/functions.php');
