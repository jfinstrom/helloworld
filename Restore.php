<?php
namespace FreePBX\modules\Helloworld;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
	public function runRestore($jobid){
		$settings = $this->getConfigs();
		foreach ($settings as $key => $value) {
			$this->freepbx->Helloworld->setMultiConfig($value, $key);
		}
	}
	public function processLegacy($pdo, $data, $tables, $unknownTables, $tmpfiledir){
		return $this->transformLegacyKV($pdo,'helloworld', $this->freepbx)
				->transformNamespacedKV($pdo,'helloworld', $this->freepbx);
	}
}