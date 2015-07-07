<?php
//All module classes should be namespaced
namespace FreePBX\modules;

//This setting is for AJAX calls. We want calls to be authenticated and so not want cross origin calls
$setting = array('authenticate' => true, 'allowremote' => false);

//The class name should match the file name with an upper-case first letter
class Helloworld implements \BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		//This is only needed for database stuff. If you are not doing database stuff you don't need this
		$this->db = $freepbx->Database;
	}
	//BMO Methods
	
	//Required: Called during module install
	public function install() {
		out(_('Creating the database table'));
		$result = $this->createTable();
		if($result === true){
			out(_('Table Created'));
		}else{
			out(_('Something went wrong'));
			out($result);
		}

		// Register FeatureCode - Create Welcome message
		$fcc = new \featurecode('helloworld', 'helloworld');
		$fcc->setDescription('Hello World Welcome Message');
		$fcc->setDefault('*43556');  // default is set to *-H-E-L-L-O
		$fcc->update();
		unset($fcc);
	}
	//Required: Called during module uninstall
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
	//Required: Can be empty, not yet used
	public function backup() {}
	//Required: Can be empty, not yet used
	public function restore($backup) {}
	//Required Processes $_REQUEST stuff
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
			break;
		} 	
	}
	//Optional: Add buttons to your page(s) Buttons should not be added in html. Note this is a 13+ method.
	//Prior to 13 you add the button to the html.
	public function getActionBar($request) {
		$buttons = array();
		switch($request['display']) {
			//this is usually your module's rawname
			case 'helloworld':
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
				//We hide the delete button if we are not editing an item. "id" should be whatever your unique element is.
				if (empty($request['id'])) {
					unset($buttons['delete']);
				}
				//If we are not in the form view lets 86 the buttons
				if (empty($request['view'])){
					unset($buttons);
				}
			break;
		}
		return $buttons;
	}
	//Optional: Ajax stuff
	
	//This method declares valid ajax commands...
	public function ajaxRequest($req, &$setting) {
		//The ajax request
		if ($req == "getJSON") {
			//Tell BMO This command is valid. If you are doing a lot of actions use a switch
			return true;
		}else{
			//Deny everything else
			return false;
		}	
	}
	//This handles the AJAX via ajax.php?module=helloworld&command=getJSON&jdata=grid
	public function ajaxHandler() {
		if($_REQUEST['command'] == 'getJSON'){
			switch ($_REQUEST['jdata']) {
				case 'grid':
					return $this->getList();
				break;
				
				default:
					print json_encode(_("Invalid Request"));
				break;
			}
		}
	}


	//Module getters These are all custom methods
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
		$sql = 'SELECT id,subject,body from helloworld';
		foreach ($this->db->query($sql) as $row) {
			$ret[] = array('id' => $row['id'],'subject' => $row['subject']);
		}
		return $ret;
	}
	//Module setters these are all custom methods.
	
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


	// Dialplan Methods
	
	// This method required, what is is used for?
	Public function myDialplanHooks(){
		return true;
	}

	// Method 'doDialplanHook' used to generate Asterisk dialplan
	public function doDialplanHook(&$ext, $engine, $priority){
		$modulename = 'helloworld';

		// Retrieve module's feature code
		$fcc = new \featurecode($modulename, 'helloworld');
		$hw_fc = $fcc->getCodeActive();
		unset($fcc);

		$id = 'app-helloworld';
		$ext->addInclude('from-internal-additional', $id); // Add the include to from-internal
		$ext->add($id, $hw_fc, '', new \ext_goto('1', 's', 'app-helloworld-playback'));  // feature code goes to playback context

		$id = 'app-helloworld-playback';
		$c = 's';
		$ext->add($id, $c, 'label', new \ext_answer());
		$ext->add($id, $c, '', new \ext_wait(1));
		$ext->add($id, $c, '', new \ext_playback('hello-world'));
		$ext->add($id, $c, '', new \ext_playback('demo-congrats'));
		$ext->add($id, $c, 'hangup', new \ext_hangup());

	}

	//Module General Methods
	
	//Install
	private function createTable(){
		$table = 'helloworld';

		try{
			$sql = "CREATE TABLE IF NOT EXISTS $table(
				`id` INT(11) AUTO_INCREMENT PRIMARY KEY,
				`subject` VARCHAR(60),
				`body` TEXT);";
//			return $this->db->execute($sql);
			$sth = $this->db->prepare($sql);
			return $sth->execute();

		} catch(PDOException $e) {
			return $e->getMessage();
		}
		return;
	}
	//Uninstall
	private function deleteTable(){
		$table = 'helloworld';

		try{
			$sql = "DROP TABLE IF EXISTS $table;";
//			return $this->db->execute($sql);
$sth = $this->db->prepare($sql);
return $sth->execute();

		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}
	//View called by page.helloworld.php
	public function showPage(){
		switch ($_REQUEST['view']) {
			case 'form':
				if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
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
