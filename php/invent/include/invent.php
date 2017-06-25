<?php
if (isset($_GET['id'])){
  $id = $_GET['id'];
} else {
  $id = "";
}
if (isset($_GET['search'])){
  $search = $_GET['search'];
} else {
  $search="";
}

include_once "config.php";
include_once "db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);


 ?>

<table width="100%">
  <tr>
    <td width="100" valign="middle"><h3>Iventar</h3></td>
    <td align=right>
      <button class="action-button" id="refresh-invent"><img width="12" height="10" src="img/reload-16.png"></button>
      <button title="Inventar hinzufügen" class="action-button" id="add-invent">+</button>
    </td>
  </tr>
</table>
<br>
Suche
<table width="100%" border="0" cellpadding="4" cellspacing="0">
  <tr>
      <td>
        <div class="invent-suche">
          <input class="textfields" type="text" id="invent-suche">
        </div>
      </td>
      <td width="20"><img  class="glass" width="22"  height="22" src="img/search.png"></td>
  </tr>
</table>
<br>

<div class="content" id="invent-content">
</div>

<?php $rn = rand(1, 999999999999); ?>
<script type="text/javascript" charset="utf-8" src="js/side-invent.js?r=<?php echo $rn; ?>"></script>
<?php  $connection->close(); ?>
