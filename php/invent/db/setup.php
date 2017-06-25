<?php

include_once "../include/config.php";
include_once "../include/db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);



$sql ="CREATE TABLE category
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  cat CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE descriptions
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  description CHAR(255));";
$connection->exec($sql);


$sql ="CREATE TABLE mstates
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  mstate CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE tasks
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  task CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE maintenance
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  mgroup INT,
  jahr CHAR(4),
  mstate INT,
  kw INT,
  description TEXT,
  enddate CHAR(64));";
$connection->exec($sql);

$sql ="CREATE TABLE uploads
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  uuid CHAR(128),
  refid CHAR(128),
  filename CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE maintmails
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  uuid CHAR(128),
  mailtitel CHAR(255),
  mailkopf CHAR(255),
  mailabsender CHAR(128),
  mailempfaenger TEXT,
  mailtext TEXT);";
$connection->exec($sql);

$sql ="CREATE TABLE maintdetail
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  uuid CHAR(128),
  mstate INT,
  kw INT,
  info TEXT,
  datumzeit DATETIME,
  datum CHAR(64));";
$connection->exec($sql);

$sql ="CREATE TABLE repair
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  inventid INT,
  uuid CHAR(128),
  kind CHAR(255),
  mstate INT,
  task INT,
  startdate CHAR(64),
  enddate CHAR(64));";
$connection->exec($sql);

$sql ="CREATE TABLE anreden
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  anrede CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE kontakte
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  firma CHAR(255),
  vorname CHAR(255),
  name CHAR(255),
  strasse CHAR(255),
  plz INT,
  ort CHAR(255),
  tel CHAR(255),
  fax CHAR(255),
  email CHAR(255),
  www CHAR(255),
  datum CHAR(12),
  anrede INT);";
$connection->exec($sql);

$sql ="CREATE TABLE inventar
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  uuid CHAR(128),
  inventarnr CHAR(255),
  serialnr CHAR(255),
  description CHAR(255),
  bezeichnung CHAR(255),
  category CHAR(255),
  hostname CHAR(255),
  cname CHAR(255),
  location INT,
  room INT,
  inventgrp INT,
  maintenancegrp INT,
  maintupdate DATETIME,
  wsusgrp INT,
  remote CHAR(255),
  ip1 CHAR(255),
  ip2 CHAR(255),
  switch1 CHAR(255),
  switch2 CHAR(255),
  switchport1 CHAR(255),
  switchport2 CHAR(255),
  ip3 CHAR(255),
  ip4 CHAR(255),
  vlan INT,
  assignment INT,
  state INT,
  wartungshinweis TEXT,
  supportdistributor INT,
  supportstarttime CHAR(64),
  supportendtime CHAR(64),
  supportdetails TEXT);";
$connection->exec($sql);

$sql ="CREATE TABLE inventgroups
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  inventgrp CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE wsusgroups
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  wsusgrp CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE maintenancegroups
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  weekinterval INT,
  maintenancegrp CHAR(255));";
$connection->exec($sql);


$sql ="CREATE TABLE locations
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  location CHAR(255));";
$connection->exec($sql);

$sql ="CREATE TABLE rooms
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  room CHAR(255),
  building CHAR(255),
  description CHAR(255));";
$connection->exec($sql);


$sql ="CREATE TABLE textbausteine
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  bezeichnung CHAR(255),
  textbaustein TEXT,
  grp CHAR(255));";
$connection->exec($sql);


$sql ="CREATE TABLE assignments
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  assignment CHAR(255));";
  $connection->exec($sql);

$sql ="CREATE TABLE states
  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  state CHAR(255),
  weight INT);";
  $connection->exec($sql);
echo "table successfully created!";


 ?>
