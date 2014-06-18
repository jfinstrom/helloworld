<?php

class Smsdest implements BMO {
    private $registeredFunctions = array();
	private $fooTable = 'helloworld_foo';
	private $barTable = 'helloworld_bar';
	private $freepbxUserTable = 'freepbx_users';
	private $message = '';
	public function __construct($freepbx == null) {
		if ($freepx == null){
			include(dirname(__FILE__).'/DB_Helper.class.php');
			$this->db = new Database;
		}else {
			$this->FreePBX = $freepbx;
			$this->db = $freepbx->Database;
		}
		function &create() {
			static $obj;
			if (!isset($obj) || !is_object($obj)) {
				$obj = new Smsdest();
			}
			return $obj;
		}
		public function install() {
		
		}
		public function uninstall() {
		
		}
		public function backup() {
		
		}
		public function restore($backup) {
		
		}
		public function genConfig() {
		
		}
		public function writeConfig($conf){
		
		}
		public function setMessage($message,$type='info') {
			$this->message = array(
				'message' => $message,
				'type' => $type
			);
			return true;
		}
		public function doConfigPageInit($display) {
		
		}
		public function myShowPage() {
		
		}
		public function registerHook($action,$function) {
			$this->registeredFunctions[$action][] = $function;
			return true;
		}

