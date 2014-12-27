<?php
class Helloworld implements BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}
	public function install() {}
	public function uninstall() {}
	public function backup() {}
	public function restore($backup) {}
	public function doConfigPageInit($page) {
		$this->request = $_REQUEST;
		
	}
	public function showPage(){
		$view = $this->request['view'];
		switch($view){	
			default:
				show_view(__DIR__.'/views/default.php');
			break;
		}
	}
}
