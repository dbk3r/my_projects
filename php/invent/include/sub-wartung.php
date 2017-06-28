<?php

include_once "config.php";
include_once "db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);
$connection->busyTimeout(5000);

$sql = "select * from inventar where maintenancegrp='". $_GET['grp'] ."'";
$results = $connection->query($sql);
$mgroup_result = $connection->querySingle("select * from maintenancegroups where id='" . $_GET['grp'] . "'", true);
?>
<h3><?php  echo "KW: " . $_GET['kw'] . " [" . $mgroup_result['maintenancegrp']."]"; ?> </h3>
<h3>beteiligte Server</h3>
zeige Textfelder <input type="checkbox" id="show_infos" checked><br><br>
<table width="100%" border="0" cellpadding="4" cellspacing="1">
  <tr class="theader">
    <td></td>
    <td>Hostname</td>
    <td>Cname</td>
    <td>Beschreibung</td>
    <td>Zugang</td>
    <td>Status</td>
  </tr>
<?php
while ($row = $results->fetchArray()) {
  $uuid = gen_uuid();
  $sql = "update inventar set uuid='". $uuid ."' where id='". $row['id'] ."' and uuid is NULL";
  $connection->exec($sql);

  $m_result = $connection->querySingle("select * from maintdetail where kw='" . $_GET['kw'] . "' and uuid = '". $row['uuid'] ."'", true);
  if (isset($m_result['mstate'])) {
    if ($m_result['mstate'] == '1') { $checked = "checked"; $st="class='tbl-row-checked'"; } else { $checked=""; $st="class='tbl-row-nc'";}
  } else { $checked=""; $st="class='tbl-row-nc'";}
  if (isset($m_result['info'])) { $info = $m_result['info']; } else { $info = "";}

  if($row['wartungshinweis'] != "" ) { $wh="<img src='img/attention2.png' width=14 height=14>"; $title = "title='" . $row['wartungshinweis'] . "'";} else { $title = ""; $wh="";}
  echo "<tr class='tbl-row-nc'><td ". $st . " " . $title ." width=5 align='center'>$wh</td><td>". $row['hostname'] ."</td><td>". $row['cname'] ."</td><td>". $row['bezeichnung'] ."</td><td><a target='_blank' href='". $row['remote'] ."'> "
        . $row['remote'] ."</a></td><td width='80'><input title='erledigt' ". $checked ." type=checkbox kw='". $_GET['kw'] ."' uuid='". $row['uuid'] ."' class='checkstate' id='check-". $row['uuid'] ."'>
          <img title='Inventar bearbeiten' class='btn edit_invent' id='". $row['id'] ."' uuid='". $row['uuid'] ."' width=12 height=12 src=img/edit.png>
          <img title='Datei hinzufügen' class='btn upload_invent' id='". $row['id'] ."' uuid='". $row['uuid'] ."' width=12 height=12 src=img/upload.png>
          <img title='Datensatz speichern' class='btn save_maint' kw='". $_GET['kw'] ."' uuid='". $row['uuid'] ."' width=12 height=12 src=img/save.png>
          </td></tr>";
  echo "<tr><td colspan=5><div class=infos><textarea rows=6 class=textfield kw='". $_GET['kw'] ."' uuid='". $row['uuid'] ."' id='mdinfo-". $row['uuid']  ."'>". $info ."</textarea></div></td>
          <td valign='top'>";
  echo "<table width='100%' height='100%'>";
  echo "<tr><td valign='top'>";
  echo "<div class='invent_uploads' id='invent_uploads-". $row['uuid'] ."'>";
  $sql = "select * from uploads where refid='". $row['uuid'] ."'";
  $upload_results = $connection->query($sql);
  while ($u_row = $upload_results->fetchArray()) {
    echo "";
    echo "<div class='upload_content' id='". $u_row['uuid'] ."'><div title='Content löschen' class='close' filename='". $u_row['filename'] ."' uuid='". $u_row['uuid'] ."'></div><a href='". $u_row['filename'] ."' data-featherlight='image' ><img width=14 height=14 src='". $u_row['filename'] ."'></a></div>";
  }
  echo "</div>";
  echo "</td><td width='2' valign='bottom'>";
  echo "<div class='upload_progress' id='invent_uploads-progress-". $row['uuid'] ."'>";

  echo "</div></td>";
  echo "</table>";
  echo  "</td></tr>";
  echo "<tr><td colspan=6 align='center'><img id='title-". $row['uuid'] ."' title='zeige vergange Wartungseinträge' class='box-header-btn' uuid='". $row['uuid'] ."' src='/invent/img/down.png'></td></tr>";
  echo "<tr><td colspan=6 align='center'><div class='maint-history-box' id='maint-history-". $row['uuid'] ."' ></div></td></tr>";
  echo "<tr><td colspan=6 align='center'><hr class=trenner></td></tr>";
}


$wp = $connection->querySingle("select * from maintenance where id='" . $_GET['wp_id'] . "'", true);
?>
</table>
<br><br>
Notizen & Dokumentation:<br>
<textarea rows="7" class="textfield" id="wp_desc">
<?php echo $wp['description']; ?>
</textarea>

<br><br>Status:<br>
<table width="100%" border="0" cellpadding="4" cellspacing="1">
  <tr>
    <td width="200">
      <select class="dropdowns" id="wp_status">
        <option value='0'></option>
        <?php
          $mresults = $connection->query('select * from mstates');
          while ($mrow = $mresults->fetchArray()) {
            if($mrow['id'] == $wp['mstate']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $mrow['id'] .'">' . $mrow['mstate'] .'</option>';
          }
        ?>
      </select>

  </td><td></td>
<td width="150"><button class="wp-button" id="wp-save" tbl="maintenance" column="description" column2="mstate" dbid="<?php echo $_GET['wp_id'] ;?>">speichern</button></td>
</tr>
</table>
<div class="fileupload" style="display:none;"><input type="file" id="fileupload-dialog" name="filename"></div>
<?php $rn = rand(1, 999999999999); ?>
<script type="text/javascript" charset="utf-8" src="js/sub-side-wartung.js?r=<?php echo $rn; ?>"></script>
<script type="text/javascript" charset="utf-8" src="js/featherlight.js"></script>
