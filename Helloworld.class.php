<?php
namespace FreePBX\modules;
use BMO;
use FreePBX_Helpers;
use PDO;
class Helloworld extends FreePBX_Helpers implements BMO {
	public $FreePBX = null;

	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->Database = $freepbx->Database;
	}
	/**
	 * Installer run on fwconsole ma install
	 *
	 * @return void
	 */
	public function install(){}

	/**
	 * Uninstaller run on fwconsole ma uninstall
	 *
	 * @return void
	 */
	public function uninstall(){}
	
	/**
	 * Processes form submission and pre-page actions.
	 *
	 * @param string $page Display name
	 * @return void
	 */
	public function doConfigPageInit($page) {
		/** getReq provided by FreePBX_Helpers see https://wiki.freepbx.org/x/0YGUAQ */
		$action = $this->getReq('action','');
		$id = $this->getReq('id','');
		$subject = $this->getReq('subject','');
		$body = $this->getReq('body');

		if('add' == $action){
			return $this->addItem($subject, $body);
		}

		if('delete' == $action){
			return $this->deleteItem($id);
		}
		
		if('edit' == $action){
			$this->updateItem($id, $subject, $body);
		}
	}

	/**
	 * Adds buttons to the bottom of pages per set conditions
	 *
	 * @param array $request $_REQUEST
	 * @return void
	 */
	public function getActionBar($request) {
		if('helloworld' == $request['display']){
			if(!isset($_GET['view'])){
				return [];
			}
			$buttons = [
				'delete' => [
					'name' => 'delete',
					'id' => 'delete',
					'value' => _('Delete'),
				],
				'reset' => [
					'name' => 'reset',
					'id' => 'reset',
					'value' => _("Reset"),
				],
				'submit' => [
					'name' => 'submit',
					'id' => 'submit',
					'value' => _("Submit"),
				],
			];
			if(!isset($_GET['id']) || empty($_GET['id'])){
				unset($buttons['delete']);
			}
			return $buttons;
		}
	}

	/**
	 * Returns bool permissions for AJAX commands
	 * https://wiki.freepbx.org/x/XoIzAQ
	 * @param string $command The ajax command
	 * @param array $setting ajax settings for this command typically untouched
	 * @return bool
	 */
	public function ajaxRequest($command, &$setting) {
		//The ajax request
		if ("getJSON" == $command ) {
			return true;
		}
		return false;
	}

	/**
	 * Handle Ajax request
	 * @url ajax.php?module=helloworld&command=getJSON&jdata=grid
	 * 
	 * @return array
	 */
	public function ajaxHandler() {
		if('getJSON' == $_REQUEST['command'] && 'grid' == $_REQUEST['jdata']){
			return $this->getList();
		}
		return json_encode(['status' => false, 'message' => _("Invalid Request")]);
	}


	//Module getters These are all custom methods
	/**
	 * getOne Gets an individual item by ID
	 * @param  int $id Item ID
	 * @return array Returns an associative array with id, subject and body.
	 */
	public function getOne($id){
		$sql = "SELECT id,subject,body FROM helloworld WHERE id = :id";
		$stmt = $this->Database->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetchObject();
		return [
			'id' => $row->id,
			'subject' => $row->subject,
			'body' => $row->body
		];
	}
	/**
	 * getList gets a list od subjects and their respective id.
	 * @return array id => subject
	 */
	public function getList(){
		$ret = [];
		$sql = 'SELECT id, subject FROM helloworld';
		$data = $this->Database->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
		array_walk($data,function(&$value,$key){
			$value = ['id' => $key, 'subject' => $value];
		});
		return $data;
	}
	//Module setters these are all custom methods.

	/**
	 * addItem Add an Item
	 * @param string $subject The Subject of the item
	 * @param [type] $body    The body of the item
	 */
	public function addItem($subject,$body){
		$sql = 'INSERT INTO helloworld (subject, body) VALUES (:subject, :body)';
		$stmt = $this->Database->prepare($sql);
		$stmt->bindParam(':subject', $subject, \PDO::PARAM_STR);
		$stmt->bindParam(':body', $body, \PDO::PARAM_STR);
		$stmt->execute();
		return $this;
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
		$stmt = $this->Database->prepare($sql);
		$stmt->bindParam(':subject', $subject, \PDO::PARAM_STR);
		$stmt->bindParam(':body', $body, \PDO::PARAM_STR);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$stmt->execute();
		rerturn $this;
	}
	/**
	 * deleteItem Deletes the given ID
	 * @param  int $id      Item ID
	 * @return bool          Returns true on success or false on failure
	 */
	public function deleteItem($id){
		$sql = 'DELETE FROM helloworld WHERE id = :id';
		$stmt = $this->Database->prepare($sql);
		$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
		$stmt->execute();
		return $this;
	}

	/**
	 * Do we want to add to the dialplan?
	 * https://wiki.freepbx.org/display/FOP/BMO+Hooks#BMOHooks-DialplanHooks
	 *
	 * @return bool or int 500 priority
	 */
	Public function myDialplanHooks(){
		return true;
	}

	/**
	 * Dialplan generation
	 *
	 * @param object $ext The dialplan object we add to
	 * @param string $engine This will always be asterisk
	 * @param int $priority 500?
	 * @return void
	 */
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

	/**
	 * This returns html to the main page
	 *
	 * @return string html
	 */
	public function showPage(){
		$subhead = _('Item List');
		$content = load_view(__DIR__ . '/views/grid.php');

		if('form' == $_REQUEST['view']){
			$subhead = _('Add Item');
			$content = load_view(__DIR__ . '/views/form.php');	
			if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
				$subhead = _('Edit Item');
				$content = load_view(__DIR__.'/views/form.php', $this->getOne($_REQUEST['id']));
			}
		}
		 echo load_view(__DIR__.'/views/default.php', array('subhead' => $subhead, 'content' => $content));
	}
}
