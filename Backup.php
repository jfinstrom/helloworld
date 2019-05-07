<?php
namespace FreePBX\modules\Helloworld;
use FreePBX\modules\Backup as Base;
class Backup Extends Base\BackupBase{
	public function runBackup($id,$transaction){
		$kvstoreids = $this->FreePBX->Helloworld->getAllids();
		$kvstoreids[] = 'noid';
		$settings = [];
		foreach ($kvstoreids as $value) {
			$settings[$value] = $this->FreePBX->Helloworld->getAll($value);
		}
		$this->addConfigs($settings);
	}
}
