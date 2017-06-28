

<?php
  include_once "include/config.php";
  include_once "include/db_functions.php";
  $connection = sqlite_connect($sqlite_db);

  if($_COOKIE["login-success"]) {
    header("Location: include/login.php");
    die();
  }

 ?>
<!DOCTYPE html>
<html>
<head>
  <title>TPS Invent</title>
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="css/tree.css"></link>
  <link rel="stylesheet" href="css/design.css"></link>
<link rel="stylesheet" href="css/featherlight.css"></link>
  <link rel="stylesheet" href="css/jquery-ui.css"></link>
</head>
<body>
    <div class="header"><?php  include "include/header.php"  ?></div>
    <div class="navigation" style="display:none;">
      <div class="tv"><?php  include "include/tv.php"  ?></div>
    </div>
    <div class="main-content"><?php  ?></div>
    <div class="sub-content"></div>
    <div class="edit_container">
      <div class="container_header">
        <table width=100%><tr><td valign=top class="conent_header">Inventar Editor</td><td width="14" valign=top align=right><img class="btn_" id="close_edit_container" width="14" height="14" src="img/close.png"></td></tr></table>
      </div>
      <div class="edit-content"></div>
      <div class="container_footer">
        <table width=100%><tr><td valign=top class="content_footer"></td><td width="14" valign=top align=right><img class="resize_edit_container" id="resize_edit_container" width="20" height="20" src="img/resize-handle.png"></td></tr></table>
      </div>
    </div>
    <div class=footer><?php  include "include/footer.php"  ?></div>
</body>

</html>

<?php
  $connection->close();
 ?>
