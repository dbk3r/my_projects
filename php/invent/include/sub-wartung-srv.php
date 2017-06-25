<?php

  include_once "config.php";
  include_once "db_functions.php";
  $connection = sqlite_connect("../".$sqlite_db);
  $connection->busyTimeout(5000);

  $sql = "select * from maintdetail where uuid='". $_GET['uuid'] ."' order by datumzeit desc";
  $results = $connection->query($sql);

  echo "<h3>". $_GET['hostname'] ."</h3>";
  echo "<table width=100%>";
  while ($row = $results->fetchArray()) {
    $info =  str_replace("\n", "<br>", $row['info']);
    echo "<tr><td valign=top>KW: ". $row['kw'] ." </td><td valign=top>". $info ."</td></tr>";
  }
  echo "</table>";
?>
