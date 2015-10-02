<?php
$dbh = \FreePBX::Database();
out(_('Removing the database table'));
$table = 'helloworld';

try{
  $sql = "DROP TABLE IF EXISTS $table;";
  $sth = $dbh->prepare($sql);
  $result = $sth->execute();

} catch(PDOException $e) {
  $result = $e->getMessage();
}

if($result === true){
  out(_('Table Deleted'));
}else{
  out(_('Something went wrong'));
  out($result);
}
