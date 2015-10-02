<?php
out(_('Creating the database table'));
//Database
$table = 'helloworld';
$dbh = \FreePBX::Database();
try{
  $sql = "CREATE TABLE IF NOT EXISTS $table(
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `subject` VARCHAR(60),
    `body` TEXT);";
  $sth = $dbh->prepare($sql);
  $result = $sth->execute();

} catch(PDOException $e) {
  $result = $e->getMessage();
}

if($result === true){
  out(_('Table Created'));
}else{
  out(_('Something went wrong'));
  out($result);
}

// Register FeatureCode
$fcc = new featurecode('helloworld', 'helloworld');
$fcc->setDescription('Hello World Welcome Message');
$fcc->setDefault('*43556');  // default is set to *-H-E-L-L-O
$fcc->update();
unset($fcc);
