<?php
//Let's make magic folks
/* Class Helloworld
 * This is a code example for those who like to learn by code. May this be useful
 * @install manditory used durring your module install
 * @uninstall clean up after yourself when uninstalled
 * @backup not implemented Future: used to generate a backup array for later restore
 * @restore not implemented Future uses said array to restore things
 * @doConfigPageInit calls given page. If present $_REQUEST passed
 *  Future: Helper class to sanatize
 * Above are the base methods required in all BMO modules. The rest I just made up to demo stuff
 * There are other built in methods for BMO I will do my best to document inline.
 * 
 */
class Helloworld implements BMO {
	
	/* BMO doesn't require you to have a constructor but it is generally a
	 * fantastic idea to initialize the object ans allows us to set some 
	 * stuff up that will be useful later. The construct "should" be called
	 * with a object.
	 */ 
	
	public function __construct($freepbx = null) {
		// If we were called without an object we should throw up an exception 
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		// lets say who we are.
		$this->FreePBX = $freepbx;
		//lets connect to the database
		$this->db = $freepbx->Database;
		//Change all references to a common variable in one place
		$this->module_name = 'helloworld';
	}
    public function install() {
		$db = $this->db;
		//make a settings table. Naming is usually rawname_purpose
		$sql = "CREATE TABLE IF NOT EXISTS helloworld_settings (
		id int NOT NULL AUTO_INCREMENT PRIMARY KEY, 
		name VARCHAR( 10 ),
		value VARCHAR( 50 )
		)";
		$q = $db->prepare($sql);
		//Tell the user whats happening Note wrapping text in this fashion allows it to be translated
		echo _("Creating Database \n");
		$q = $q->execute();
		unset($sql);
		unset($q);
		//add some defaults...
		echo _("Adding Default Settings\n");
		$sql = "INSERT INTO helloworld_settings (name,value) VALUES (?,?)";
		$q = $db->prepare($sql);
		$q->execute(array('hwstr','hello world'));
		$q->execute(array('foo','fooval'));
		$q->execute(array('bar','val'));
		unset($q);
		unset($sql);
	}
    public function uninstall() {
		//Johnnie drop tables
		echo _("Removing Settings table");
		$sql = "DROP table helloworld_settings";
		$q = $db->prepare($sql);
		$q->execute();
		}
    public function backup() {}
    public function restore($backup) {}
    public function doConfigPageInit($page) {}
    public function showPage(){
			//Default Landing
			show_view(__DIR__.'/views/default.php', array('foo','bar'));
	}
    
    
}
