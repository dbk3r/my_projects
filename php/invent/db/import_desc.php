<?php
include_once "../include/config.php";
include_once "../include/db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);


$fh = fopen('model.txt','r');
while ($line = fgets($fh)) {
  $felder = explode(";", $line);
}
fclose($fh);


foreach($felder as $feld)
{
  print $feld;
  $sql = "insert into descriptions (description) values('".  $feld ."')";
  $connection->exec($sql);
}
$connection->close();
 ?>
