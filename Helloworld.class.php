<?php
/*
 * Copyright (c) 2014 "James Finstrom"
 * http://github.com/jfinstrom
 * 
 * This module was written for the FreePBX project at http://freepbx.org
 * 
 * This file is part of the helloworld FreePBX demo module
 * 
 * helloworld is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
 
class Helloworld implements BMO {
	//BMO Methods... These are required by BMO.
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}
	public function install() {
		$db = $this->db;
		$sql = 'CREATE TABLE `helloworld` (
					`id` INT NOT NULL AUTO_INCREMENT,
					`timestamp` TIMESTAMP,
					`subject` TEXT,
					`body` MEDIUMTEXT,
					PRIMARY KEY  (`id`)
				)  DEFAULT CHARACTER SET=utf8;';
		out(_('Creating Database for Hello World'));
		$q = $db->prepare($sql);
		$ret = $q->execute();
		if($ret){
			out(_('Database Created'));
		}else{
			out(_('Database Creation Failed'));
		}
		unset($sql);
		unset($q);
	}
	public function uninstall() {
		
	}
	public function backup() {}
	public function restore($backup) {}
	public function doConfigPageInit($page) {
		$this->request = $_REQUEST;
		$request = $this->request;
		$action = $request['action'];
		switch($action){
			case "add":
				$this->addNote($request['subject'],$request['body']);
				redirect_standard();
			break;
			case "del":
				$this->delNote($request['id']);
				redirect_standard();
			break;
			case "edit":
				$this->editNote($request['id'], $request['subject'],$request['body']);
				redirect_standard();
			break;
			
		}
		
	}
	//This is a built in BMO method but only for 13. Prior to 13 you add buttons in to the form. In 13+ we use the action bar (floating buttons).
	public function getActionBar($request) {
		$buttons = array();
		switch($request['display']){
			case "helloworld":
				//Set the buttons for this module
				$buttons = array(
					'delete' => array(
						'name' => 'delete',
						'id' => 'delete',
						'value' => _('Delete')
					),
					'reset' => array(
						'name' => 'reset',
						'id' => 'reset',
						'value' => _('Reset')
					),
					'submit' => array(
						'name' => 'submit',
						'id' => 'submit',
						'value' => _('Submit')
					)
				);
				//turn off Delete button on the "add" page
				if($request['view'] == "add"){ 
					unset($buttons['delete']);
				}
			break;
		}
	}
	
	//Module methods This is stuff you create and is not manditory
	public function showPage(){
		$view = $this->request['view'];
		switch($view){
			case add:
				show_view(__DIR__.'/views/addForm.php');
			break;
			case edit:
			break;
			case view:
			break;
			default:
				show_view(__DIR__.'/views/default.php', $this->listNotes());
			break;
		}
	}
	public function listNotes(){
		$db = $this->db;
		$sql = 'SELECT * FROM helloworld ORDER BY timestamp DESC;';
		$q = $db->prepare($sql);
		if($q->execute()){
			return $q->fetchAll();
		}else{
			return false; 
		}
	}
	public function getNote($id){
		$db = $this->db;
		$sql = 'SELECT * FROM helloworld WHERE id = ?;';
		$q = $db->prepare($sql);
		$ret = $q->execute(array($id));
		return $ret->fetchAll();
	}
	public function addNote($subject,$body){
		$db = $this->db;
		$sql = 'INSERT INTO helloworld (subject,body) VALUES (?,?)';
		$q = $db->prepare($sql);
		$ret = $q->execute(array($subject,$body));
		return $ret;
	}
	public function editNote($id,$subject,$body){
		$db = $this->db;
		$sql = 'UPDATE helloworld SET subject = ?, body = ? WHERE id = ?';
		$q = $db->prepare($sql);
		$ret = $q->execute(array($subject,$body, $id));	
		return $ret;
	}
	public function delNote($id){
		$db = $this->db;
		$sql = 'DELETE FROM helloworld WHERE id = ?';
		$q = $db->prepare($sql);
		$ret = $q->execute(array($id));	
		return $ret;
	}
}
