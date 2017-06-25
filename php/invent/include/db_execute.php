<?php

include_once "config.php";
include_once "db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);
$uploadPath = "";

$action = $_POST['action'];


$result="";

if ($action == "delupload") {
  $sql = "delete from uploads where uuid='". $_POST['uuid'] ."'";
  $connection->exec($sql);
  unlink("../".$_POST['filename']);
  $result = "Eintrag wurde gelöscht";
}

if ($action == "delmaintmail") {
  $sql = "delete from maintmails where uuid='". $_POST['uuid'] ."'";
  $connection->exec($sql);
  $result = "Eintrag wurde gelöscht";
}

if ($action == "sendmail") {
  $sql = "select * from maintmails where uuid='". $_POST['uuid'] ."'";
  $mr = $connection->querySingle($sql,true);
  $headers = array
  (
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset="UTF-8";',
    'From: <'. $mr['mailabsender'] .'>',
  );
  $text = str_replace("\n", "<br>", $mr['mailtext']);

  mail($mr['mailempfaenger'], $mr['mailkopf'], $text, implode("\n", $headers));
  $result = " Mail wurde gesendet!\n";
}

if ($action == "savemaintmail") {
  $sql=" update maintmails set mailtitel='". $_POST['mailtitel'] ."',
        mailabsender='". $_POST['mailabsender'] ."',
        mailempfaenger='". $_POST['mailempfaenger'] ."',
        mailkopf='". $_POST['mailkopf'] ."',
        mailtext='". $_POST['mailtext'] ."' where uuid='". $_POST['uuid'] ."'";
  $connection->exec($sql);
  $result = "Wartungsmail mit der id: ". $_POST['uuid'] . " wurde gespeichert";
}

if ($action == "addmaintmail") {
 $uuid = gen_uuid();
  $sql = "insert into maintmails (uuid,mailtitel,mailabsender,mailempfaenger,mailkopf,mailtext) values (
          '". $uuid ."',
          '". $_POST['mailtitel'] ."',
          '". $_POST['mailabsender'] ."',
          '". $_POST['mailempfaenger'] ."',
          '". $_POST['mailkopf'] ."',
          '". $_POST['mailtext'] ."'
          )";
  $connection->exec($sql);
  $result = "Wartungsmail angelegt";
}

if ($action == "createWP") {
  for($kw=$_POST['startKW']; $kw < kw(date("Y")); $kw = $kw + $_POST['interval'])
  {
    $sql = "insert into maintenance (mgroup,jahr,kw,mstate) values ('". $_POST['mgroup'] ."','". date("Y") ."','". $kw ."','1')";
    $connection->exec($sql);
  }
  $result="Wartungsplan angelegt!";

}

if ($action == "add")
{
  if ($_POST['object'] == "settings")
  {
    if($_POST['table'] == "maintenancegroups")
     {
       $sql =  "insert into " . $_POST['table'] ." (weekinterval,". $_POST['column'] .") values ('4','". $_POST['value'] ."')";
     }
     else
     {
       $sql = "insert into " . $_POST['table'] ." (". $_POST['column'] .") values ('". $_POST['value'] ."')";
     }
    $connection->exec($sql);
  }

  if ($_POST['object'] == "tbs")
  {
    $sql = "insert into " . $_POST['table'] ." (". $_POST['column'] .",grp) values ('". $_POST['value'] ."','". $_POST['grp'] ."')";
    $connection->exec($sql);
  }

  if ($_POST['object'] == "invent")
  {

    if(isset($_POST['wartungshinweis'])) {
      $wartungshinweis = str_replace("'", "", $_POST['wartungshinweis']);
      $wartungshinweis = str_replace('"', '', $wartungshinweis);
    }
    $uuid = gen_uuid();
    $sql = "INSERT INTO inventar (bezeichnung,inventarnr,serialnr,description,category,hostname,cname,location,room,inventgrp,wsusgrp,remote,maintenancegrp,ip1,ip2,switch1,switch2,switchport1,switchport2,
                                  ip3,ip4,vlan,assignment,state,supportdistributor,supportstarttime,supportendtime,supportdetails,wartungshinweis,uuid) VALUES
                                  ('" . $_POST['bezeichnung'] . "','" . $_POST['inventarnr'] . "','" . $_POST['serialnr']. "', '" . $_POST['description']. "','" . $_POST['category'] . "','". $_POST['hostname'] . "',
                                  '" . $_POST['cname'] ."',
                                  '" . $_POST['location']. "','" . $_POST['room']. "','" . $_POST['inventgrp'] . "','" . $_POST['wsusgrp'] . "','" . $_POST['remote'] . "','" . $_POST['maintenancegrp'] . "',
                                  '" . $_POST['ip1'] . "','" . $_POST['ip2'] . "','" . $_POST['switch1'] . "','" . $_POST['switch2'] . "','" . $_POST['switchport1'] . "','" . $_POST['switchport2'] . "',
                                  '" . $_POST['ip3'] . "','" . $_POST['ip4'] . "','" . $_POST['vlan'] . "','" . $_POST['assignment'] . "',
                                  '" . $_POST['state']. "','" . $_POST['supportdistributor'] . "','" . $_POST['supportstarttime'] . "','" . $_POST['supportendtime'] . "','" . $_POST['supportdetails'] . "','" . $wartungshinweis . "','". $uuid ."');";

    $connection->exec($sql);
    $result = $uuid;
  }
}


if ($action == "update")
{
  if ($_POST['object'] == "settings")
  {
    if (isset($_POST['column2'])) {
      $sql2 = ",". $_POST['column2'] . "='". $_POST['value2']. "'";
    }
    else {
       $sql2 = "";
    }

    $sql = "UPDATE " . $_POST['tbl'] . " SET ". $_POST['column'] . "='". $_POST['value'] ."'". $sql2 ." where id='". $_POST['db_id'] ."'";
    $connection->exec($sql);
    $result = "Datensatz Speicherung ";
    #$result = $sql;
  }

  if ($_POST['object'] == "wartung")
  {

    $sql = "insert or replace into maintdetail (id,kw,uuid,mstate,info,datumzeit) VALUES ((select id from maintdetail where kw='". $_POST['kw'] ."' and uuid='". $_POST['uuid'] ."'), '". $_POST['kw'] ."','". $_POST['uuid'] ."','". $_POST['state'] ."','". $_POST['info'] ."',DateTime('now', 'localtime'));";
    $connection->exec($sql);
    $sql="update inventar set maintupdate=DateTime('now', 'localtime') where uuid='". $_POST['uuid'] ."'";
    $connection->exec($sql);
    $result="Datensatz wurde aktualisiert.";

  }

  if ($_POST['object'] == "invent")
  {

    if(isset($_POST['wartungshinweis'])) {
      $wartungshinweis = str_replace("'", "", $_POST['wartungshinweis']);
      $wartungshinweis = str_replace('"', '', $wartungshinweis);
    }

    $sql = "UPDATE inventar SET bezeichnung='" . $_POST['bezeichnung'] . "',inventarnr='" . $_POST['inventarnr'] . "',serialnr='" . $_POST['serialnr']. "',description='" . $_POST['description']. "', category='" . $_POST['category']. "',
                                hostname='" . $_POST['hostname'] . "',cname='" . $_POST['cname'] ."',location='" . $_POST['location']. "',room='" . $_POST['room']. "',
                                inventgrp='" . $_POST['inventgrp'] . "',wsusgrp='" . $_POST['wsusgrp'] . "',maintenancegrp='" . $_POST['maintenancegrp'] . "',ip1='" . $_POST['ip1'] . "',ip2='" . $_POST['ip2'] . "',
                                switch1='" . $_POST['switch1'] . "',switch2='" . $_POST['switch2'] . "',switchport1='" . $_POST['switchport1'] . "',switchport2='" . $_POST['switchport2'] . "',
                                ip3='" . $_POST['ip3'] . "',ip4='" . $_POST['ip4'] . "',remote='" . $_POST['remote'] . "',
                                vlan='" . $_POST['vlan'] . "',assignment='" . $_POST['assignment'] . "',state='" . $_POST['state']. "',supportdistributor='" . $_POST['supportdistributor'] . "',
                                supportstarttime='" . $_POST['supportstarttime'] . "',supportendtime='" . $_POST['supportendtime'] . "',supportdetails='" . $_POST['supportdetails'] . "',
                                wartungshinweis='" . $wartungshinweis . "'  WHERE uuid='". $_POST['uuid'] ."';";

    $connection->exec($sql);
    $result = "Datensatz erfolgreich gesichert.";
  }
}

if ($action == "del")
{
  $sql = "delete from ". $_POST['tbl'] . " where id='". $_POST['db_id'] ."'";
  $connection->exec($sql);
  $result = "Datensatz erfolgreich gelöscht.";
}

$connection->close();
echo $result;
 ?>
