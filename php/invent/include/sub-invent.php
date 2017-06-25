<?php

include_once "config.php";
include_once "db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);



if(isset($_GET['id']) or isset($_GET['uuid'])) {
  $invent_result = $connection->querySingle("select * from inventar where id='" . $_GET['id'] . "' or uuid='" . $_GET['uuid'] . "'", true);
  echo "<input type=hidden id='invent-id' value='".  $_GET['id'] ."'>";
  echo "<input type=hidden id='invent-uuid' value='".  $_GET['uuid'] ."'>";
}

if (!isset($invent_result)) {
  $invent_result=[];

}

if (isset($_GET['action'])) {
  $action = $_GET['action'];
} else {
  $action="";
}

if($action == "add-invent") {
  $title = "neues Gerät anlegen";
  $btn = "hinzufügen";
  $btn_id = "new-invent";
} else {
  $title = "";
  $btn = "speichern";
  $btn_id = "save-invent";
}

 ?>

<h3><?php echo $title; ?></h3>
<table border="0" cellpadding="0" cellspacing="2" width="99%">
<tr height="25"><td align="right" colspan=5>
  <button class="action-button" id="<?php echo $btn_id; ?>"><?php echo $btn; ?></button>
  <button  class="action-button" id="new-repair" >Reparaturauftrag auslösen</button>
  <button  class="action-button" id="maintenance" kw="<?php echo aktuelle_kw(); ?>" wpid="<?php echo $wpid; ?>" mgrp="<?php echo $invent_result['maintenancegrp']; ?>">Wartung</button>
</td></tr>
<tr height="25"><td colspan=5><hr class="trenner"></td></tr>

  <tr><td width="100">Inventar-Nr.</td><td><input class="textfields" id="invent-nr" value="<?php if(isset($invent_result['inventarnr'])) { echo $invent_result['inventarnr']; } ?>"></td><td width="10"></td><td width="100">Seriennummer</td><td><input class="textfields" id="sn-nr" value="<?php if(isset($invent_result['serialnr'])) { echo $invent_result['serialnr']; } ?>"></td></tr>
  <tr>
    <td width="100">Gerätebezeichnung</td><td>
      <div class="ui-widget">
        <input class="textfields" id="desc" value="<?php echo $invent_result['description']; ?>">
      </div>
    </td><td></td>
    <td width="100">Kategorie</td><td colspan=4>
      <div class="ui-widget">
        <input class="textfields" id="cat" value="<?php echo $invent_result['category']; ?>">
      </div>
    </td>
  </tr>
  <tr height="15"><td colspan=5></td></tr>
  <tr><td width="100">Hostname</td><td><input class="textfields" id="host" value="<?php if(isset($invent_result['hostname'])) { echo $invent_result['hostname']; } ?>"></td><td width="10"></td><td width="100">CName</td><td><input class="textfields" id="cname" value="<?php echo $invent_result['cname']; ?>"></td></tr>
  <tr>
    <td width="100">Bezeichnung</td><td colspan=5><input class="textfields" id="bezeichnung" value="<?php if(isset($invent_result['bezeichnung'])) { echo $invent_result['bezeichnung']; } ?>">
  </tr>

  <tr>
    <td width="100">Standort</td><td>
      <select class="textfields" id="ort">
        <?php
          $results = $connection->query('select * from locations');
          while ($row = $results->fetchArray()) {
            if($row['id'] == $invent_result['location']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $row['id'] .'">' . $row['location'] .'</option>';
          }
        ?>
      </select></td>
    <td width="10"></td>
    <td width="100">Raum</td>
    <td>
      <select class="textfields" id="room">
        <option value='0'></option>
        <?php
          $results = $connection->query('select * from rooms');
          while ($row = $results->fetchArray()) {
            if($row['id'] == $invent_result['room']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $row['id'] .'">' . $row['room'] .'</option>';
          }
        ?>

      </select>
    </td>
  </tr>
  <tr>
    <td width="100">Inventar-Gruppe</td>
    <td>
      <select class="textfields" id="inventgrp">
        <option value='0'></option>
        <?php
          $results = $connection->query('select * from inventgroups');
          while ($row = $results->fetchArray()) {
            if($row['id'] == $invent_result['inventgrp']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $row['id'] .'">' . $row['inventgrp'] .'</option>';
          }
        ?>
      </select>
    </td><td></td>
    <td> Wartungsgruppe</td>
    <td>
      <select class="textfields" id="maintenancegrp">
        <option value='0'></option>
        <?php
          $results = $connection->query('select * from maintenancegroups order by maintenancegrp');
          while ($row = $results->fetchArray()) {
            if($row['id'] == $invent_result['maintenancegrp']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $row['id'] .'">' . $row['maintenancegrp'] .'</option>';
          }
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td width="100">WSUS-Gruppe</td>
    <td>
      <select class="textfields" id="wsusgrp">
        <option value='0'></option>
        <?php
          $results = $connection->query('select * from wsusgroups');
          while ($row = $results->fetchArray()) {
            if($row['id'] == $invent_result['wsusgrp']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $row['id'] .'">' . $row['wsusgrp'] .'</option>';
          }
        ?>
      </select>
    </td>
    <td></td><td>Zugang</td><td><input class="textfields" id="remote" value="<?php echo $invent_result['remote']; ?>"></td>
  </tr>
  <tr>
    <td valign="top">Wartungshinweis</td><td colspan="4"><textarea class="textfields" id="wartungs-hinweis"><?php echo $invent_result['wartungshinweis'] ; ?></textarea></td>
  </tr>
  <tr height="15"><td colspan=5></td></tr>
  <tr><td width="100">IP-Adresse 1</td><td><input class="textfields" id="ip-1" value="<?php echo $invent_result['ip1']; ?>"></td><td width="10"></td><td width="100">IP-Adresse 2</td><td><input class="textfields" id="ip-2" value="<?php echo $invent_result['ip2']; ?>"></td></tr>
  <tr><td width="100">VLAN</td><td><input class="textfields" id="vlan" value="<?php echo $invent_result['vlan']; ?>"></td><td colspan=4></td></tr>
  <tr><td width="100">Kabel-Nr. NIC-1</td><td><input class="textfields" id="ip-3" value="<?php echo $invent_result['ip3']; ?>"></td><td width="10"></td><td width="100">Kabel-Nr. NIC-2</td><td><input class="textfields" id="ip-4" value="<?php echo $invent_result['ip4']; ?>"></td></tr>
    <tr><td width="100">Switch NIC-1</td><td><input class="textfields" id="switch1" value="<?php echo $invent_result['switch1']; ?>"></td><td width="10"></td><td width="100">Modul/Port</td><td><input class="textfields" id="switchport1" value="<?php echo $invent_result['switchport1']; ?>"></td></tr>
  <tr><td width="100">Switch NIC-2</td><td><input class="textfields" id="switch2" value="<?php echo $invent_result['switch2']; ?>"></td><td width="10"></td><td width="100">Modul/Port</td><td><input class="textfields" id="switchport2" value="<?php echo $invent_result['switchport2']; ?>"></td></tr>
  <tr height="15"><td colspan=5></td></tr>
  <tr>
    <td width="100">Zurdnung</td>
    <td>
      <select class="textfields" id="zuordnung">
        <option value='0'></option>
        <?php
          $results = $connection->query('select * from assignments');
          while ($row = $results->fetchArray()) {
            if($row['id'] == $invent_result['assignment']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $row['id'] .'">' . $row['assignment'] .'</option>';
          }
        ?>
      </select>
    </td><td colspan=4></td></tr>
  </tr>
  <tr>
    <td width="100">Status</td>
    <td>
      <select class="textfields" id="status">
        <option value='0'></option>
        <?php
          $results = $connection->query('select * from states');
          while ($row = $results->fetchArray()) {
            if($row['id'] == $invent_result['state']) { $sel = "selected"; } else { $sel= "";}
            echo '<option '. $sel .' value="' . $row['id'] .'">' . $row['state'] .'</option>';
          }
        ?>
      </select>
    </td><td colspan=4></td></tr>
  </tr>

  <tr height="25"><td colspan=5><hr class="trenner"></td></tr>
  <tr><td colspan=5>Support<br><br></td></tr>
  <tr><td width="100">Hersteller/Lieferant</td><td><input class="textfields" id="support-distributor"></td><td width="10"></td><td width="100">endet am</td><td><input class="textfields" id="support-end" value="<?php echo $invent_result['supportendtime'] ; ?>"></td></tr>
  <tr><td valign="top" width="100">Details</td><td colspan="4"><textarea class="textfields" id="support-details"><?php echo $invent_result['supportdetails'] ; ?></textarea></td></tr>

  <tr height="25"><td colspan=5><hr class="trenner"></td></tr>

  <tr><td colspan=5>Historie<br><br></td></tr>

  <?php
  if ($invent_result['uuid'])
  {
    $count = $connection->querySingle("SELECT COUNT(*) as count FROM  maintdetail where uuid='".$invent_result['uuid'] ."'");
  }
   ?>

  <tr><td>Wartung</td><td colspan="4" class="box-header" align="center"><img class="box-header-btn" box="maint-box" src="/invent/img/down.png"></td></tr>
  <tr><td valign="top" align="left"><?php if ($count > 0) { echo "<strong>[" .$count . "]</strong>" ;} ?></td><td colspan=4>
  <div id="maint-box" class="info-box">
  <?php

  if ($invent_result['uuid'])
  {
    $sql = "select * from maintdetail where uuid='".$invent_result['uuid'] ."'";
    $m_results = $connection->query($sql);
    while ($m_row = $m_results->fetchArray()) {
      echo "<strong>". $m_row['datumzeit'] ." (KW-". $m_row['kw'] .")</strong> : <br><br>". nl2br($m_row['info']). "<br><br>";
    }
  }
  ?>
  </div></td></tr>
  <tr><td>Reparatur</td><td colspan="4" class="box-header" align="center"><img class="box-header-btn" box="repair-box" src="/invent/img/down.png"></td></tr>
  <tr><td></td><td colspan=4>
  <div id="repair-box" class="info-box">
  <?php

  if ($invent_result['uuid'])
  {
    $sql = "select * from repair where uuid='".$invent_result['uuid'] ."'";
    $m_results = $connection->query($sql);
    while ($m_row = $m_results->fetchArray()) {
      echo "<strong>". $m_row['datumzeit'] ." (KW-". $m_row['kw'] .")</strong> : <br><br>". nl2br($m_row['info']);
    }
  }
  ?>
  </div></td></tr>


  <tr height="25"><td align="right" colspan=5><button class="action-button" id="<?php echo $btn_id; ?>"><?php echo $btn; ?></button></td></tr>

</table>
<?php $rn = rand(1, 999999999999); ?>
<script type="text/javascript" charset="utf-8" src="js/sub-side-invent.js?r=<?php echo $rn; ?>"></script>


<?php $connection->close(); ?>
