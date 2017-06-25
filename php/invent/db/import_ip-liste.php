<?php
include_once "../include/config.php";
include_once "../include/db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);

$fh = fopen('ip-liste-bln.csv','r');
while ($line = fgets($fh)) {
  $felder = explode(";", $line);
  $column = 0;
  if (strpos($felder[1], "D") === 0 || strpos($felder[1], "S") === 0) {
    $sql = "insert into inventar (assignment,hostname, cname, location, ip1, ip3, remote, inventgrp, switch1, switchport1, bezeichnung) values ('1', '$felder[3]', '$felder[4]', '2', '$felder[2]', '$felder[1]', '$felder[8]' ,'1', '$felder[33]', '$felder[34]/$felder[36]', '$felder[7]')";

    $invent_result = $connection->querySingle("select * from inventar where hostname='" . $felder[3] . "'", true);
    if ($invent_result) {
      $sql = "update inventar set ip4='". $felder[1] ."', switch2='". $felder[33] ."', switchport2='". $felder[34]."/".$felder[36] ."' where id='". $invent_result['id'] ."'";
    }

  $connection->exec($sql);
  print $sql. "\n";
  }
}
fclose($fh);



$connection->close();
 ?>
