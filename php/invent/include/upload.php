<?php


include_once "config.php";
include_once "db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);

$upload_folder = '../uploads/'; //Das Upload-Verzeichnis
$filename = pathinfo($_FILES['filename']['name'], PATHINFO_FILENAME);
$extension = strtolower(pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION));

$refid = $_GET['refid'];
$uuid = gen_uuid();

$filename = $uuid;
$new_path = $upload_folder.$filename.'.'.$extension;


if(file_exists($new_path)) {
 $id = 1;
 do {
 $new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
 $id++;
 } while(file_exists($new_path));
}


if (move_uploaded_file($_FILES['filename']['tmp_name'], $new_path))
{
  $sql = "insert into uploads (uuid,refid,filename) values('". $uuid ."','". $refid ."','uploads/". $filename . "." . $extension . "')";
  $connection->exec($sql);
}
$output = array("uuid" => $uuid, "filename" => "uploads/". $filename . "." . $extension);
echo json_encode($output);





 ?>
