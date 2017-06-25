<?php
include_once "../include/config.php";
include_once "../include/db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);

$term = $_REQUEST['term'];
$sql = "select * from category where cat LIKE '%". $term . "%'";
$results = $connection->query($sql);
$categories = array();
while ($row = $results->fetchArray()) {

  $c = str_replace("\r", "", utf8_encode($row['cat']));
  $c = str_replace("\n", "", utf8_encode($row['cat']));
  array_push($categories, $c);
}
$categories = str_replace("\""," zoll", $categories);
echo $_GET['callback'] . '(' . json_encode($categories) . ')';

?>
