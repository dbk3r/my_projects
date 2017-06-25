<?php


if(!$_COOKIE["login-success"]) {
 ?>


 <!DOCTYPE html>
 <html>
 <head>
   <meta charset="utf-8"/>
   <link rel="stylesheet" href="../css/tree.css"></link>
   <link rel="stylesheet" href="../css/design.css"></link>
   <link rel="stylesheet" href="../css/jquery-ui.css"></link>
 </head>
 <body>

<div class="login">
  <table width="99%">
    <tr><td align="center" colspan="2"><h3>Anmeldung</h3></td></tr>
    <tr><td>Benutzer</td><td><input id="user" type="text" class=textfield></td></tr>
    <tr><td>Kennwort</td><td><input id="passwd" type="password" class=textfield></td></tr>
    <tr><td></td><td><button  class="action-button" id="login">anmelden</button></td></tr>
  </table>
</div>
</body>

<?php

}

 ?>
