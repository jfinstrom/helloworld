<?php
namespace FreePBX\modules;

class Helloworld implements \BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}
	//BMO Methods
    public function install() {
    	out(_('Creating the database table'));
    	$result = $this->createTable();
    	if($result === true){
    		out(_('Table Created'));
    	}else{
    		out(_('Something went wrong'));
    		out($result);
    	}
    }
    public function uninstall() {
    	out(_('Removing the database table'));
    	$result = $this->deleteTable();
    	if($result === true){
    		out(_('Table Deleted'));
    	}else{
    		out(_('Something went wrong'));
    		out($result);
    	}
    }
    public function backup() {}
    public function restore($backup) {}
    public function doConfigPageInit($page) {
    	$id = $_REQUEST['id']?$_REQUEST['id']:'';
    	$action = $_REQUEST['action']?$_REQUEST['action']:'';
    	$subject = $_REQUEST['subject']?$_REQUEST['subject']:'';
    	$body = $_REQUEST['body']?$_REQUEST['body']:'';
    	//Handle form submissions
    	switch ($action) {
    		case 'add':
    			$id = $this->addItem($subject,$body);
    			$_REQUEST['id'] = $id;
    		break;
    		case 'edit':
    			$this->updateItem($id,$subject,$body);
    		break;
    		case 'delete':
    			$this->deleteItem($id);
    			unset($_REQUEST['action']);
    			unset($_REQUEST['id']);
    	} 	
    }
	public function getActionBar($request) {
		$buttons = array();
		switch($request['display']) {
			case 'modulename':
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
				if (empty($request['extdisplay'])) {
					unset($buttons['delete']);
				}
			break;
		}
		return $buttons;
	}
	//Module getters
	/**
	 * getOne Gets an individual item by ID
	 * @param  int $id Item ID
	 * @return array Returns an associative array with id, subject and body.
	 */
	public function getOne($id){
		$sql = "SELECT id,subject,body FROM helloworld WHERE id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id',$id, \PDO::PARAM_INT);
		$stmt->execute();
		$row =$stmt->fetchObject();
		return array(
			'id' => $row->id,
			'subject' => $row->subject,
			'body' => $row->body 
			);
	}
	/**
	 * getList gets a list od subjects and their respective id.
	 * @return array id => subject
	 */
	public function getList(){
		$ret = array();
		$sql = 'SELECT id,subject from helloworld';
		foreach ($this->db->query($sql) as $row) {
			$ret[$row['id']] = $row['subject'];
		}
		return $ret;
	}
	//Module setters
	
	/**
	 * addItem Add an Item
	 * @param string $subject The Subject of the item
	 * @param [type] $body    The body of the item
	 */
	public function addItem($subject,$body){
		$sql = 'INSERT INTO helloworld (subject, body) VALUES (:subject, :body)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':subject', $subject, \PDO::PARAM_STR);
		$stmt->bindParam(':body', $body, \PDO::PARAM_STR);
		$stmt->execute();
		return $this->db->lastInsertId();
	}
	/**
	 * updateItem Updates the given ID
	 * @param  int $id      Item ID
	 * @param  string $subject The new subject
	 * @param  string $body    The new body
	 * @return bool          Returns true on success or false on failure
	 */
	public function updateItem($id,$subject,$body){
		$sql = 'UPDATE helloworld SET subject = :subject, body = :body WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':subject', $subject, \PDO::PARAM_STR);
		$stmt->bindParam(':body', $body, \PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		return $stmt->execute();
	}
	/**
	 * deleteItem Deletes the given ID
	 * @param  int $id      Item ID
	 * @return bool          Returns true on success or false on failure
	 */
	public function deleteItem($id){
		$sql = 'DELETE FROM helloworld WHERE id = :id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		return $stmt->execute();
	}
	//Module General Methods
	
	//Install
	private function createTable(){
		$table = 'helloworld';

		try{
			$sql = "CREATE TABLE IF NOT EXISTS $table(
				id INT(11) AUTO_INCREMENT PRIMARY KEY,
				subject VARCHAR(60),
				body TEXT;";
			return $this->db->execute($sql);
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}
	//Uninstall
	private function deleteTable(){
		$table = 'helloworld';

		try{
			$sql = "DROP $table;";
			return $this->db->execute($sql);
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}
	//View
	public function showPage(){
		switch ($_REQUEST['view']) {
			case 'form':
				if(isset($_REQUEST['id'] && !empty($_REQUEST['id'])){
					$subhead = _('Edit Item');
					$content = load_view(__DIR__.'/views/form.php', $this->getOne($_REQUEST['id']));					
				}else{
					$subhead = _('Add Item');
					$content = load_view(__DIR__.'/views/form.php');										
				}
			break;
			default:
				$subhead = _('Item List');
				$content = load_view(__DIR__.'/views/grid.php');
			break;
		}
		 echo load_view(__DIR__.'/views/default.php', array('subhead' => $subhead, 'content' => $content));
	}
}
