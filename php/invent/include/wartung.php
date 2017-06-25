<?php

  include_once "config.php";
  include_once "db_functions.php";
  $connection = sqlite_connect("../".$sqlite_db);

?>
  <h3>Wartung</h3>

<div id="maint-tabs">
  <ul>
    <li><a href="#plan-tab">Kalenderwochen</a></li>
    <li><a href="#historie-tab">Letzte Einträge</a></li>
    <li><a href="#mail-tab">Mail</a></li>
  </ul>

  <div id="plan-tab">
    Kalenderwoche : <select id="choose-kw" class="kw-select">
      <?php
        for($ckw=1; $ckw<kw(date("Y")); $ckw++)
        {
          if($ckw == aktuelle_kw()) { $sel = "selected"; } else { $sel = ""; }
          echo "<option ". $sel ." value='". $ckw ."'>". $ckw ."</option>";
        }

      ?>
    </select>
    <div id="kw-ansicht">
      <?php
      $_REQUEST['kw'] = aktuelle_kw();
        include "kw-ansicht.php";
       ?>
    </div>
  </div>

  <div id="historie-tab">
    <table id='maint-table' width="100%" border="0" cellpadding="4" cellspacing="1">
      <tr class="theader">
        <td>Hostname</td>
        <td>Cname</td>
        <td>Beschreibung</td>
        <td>Wartungsgruppe</td>
        <td width=200>letzte Wartung</td>
      </tr>
    <?php
      $sql = "select * from inventar where maintenancegrp!='0' order by maintupdate asc";
      $results = $connection->query($sql);

      while ($row = $results->fetchArray()) {
        $d_result = $connection->querySingle("select * from maintdetail where uuid='" . $row['uuid'] . "' order by datumzeit desc", true);
        if(isset($row['maintupdate'])) { if(isset($d_result['info'])) { $d_info = $d_result['info'];}  if(isset($d_result['wk'])) { $kw=$d_result['kw'];}  } else { $kw=aktuelle_kw(); $d_info="";}
        if(isset($d_result['id'])) { $d_id = $d_result['id']; } else { $d_id= ""; }
        $m_result = $connection->querySingle("select * from maintenancegroups where id='" . $row['maintenancegrp'] . "'", true);
        echo "<tr uuid='". $row['uuid'] ."' hostname='". $row['hostname'] ."' kw='". $kw ."' id='". $row['maintenancegrp']  ."' class='tbl-row-single'><td valign=top>". $row['hostname'] ."</td><td valign=top>". $row['cname'] ."</td><td valign=top>". $row['bezeichnung'] ."</td><td valign=top>" . $m_result['maintenancegrp'] ."</td><td><strong>"
            . $row['maintupdate']  ."</strong><br>". nl2br($d_info) ."</td></tr>";
    }
    echo "</table>";
    ?>
  </div>
  <div id="mail-tab">
    <h3>Wartungsmails verschicken</h3>

    <?php
      $sql = "select * from maintmails";
      $results = $connection->query($sql);
      echo "<table width=100%>";
      while ($row = $results->fetchArray()) {
        echo "<tr><td><button class='smail-button' id='sendmail' uuid='". $row['uuid'] . "'>". $row['mailtitel'] ."</button></td></tr>";
      }
      echo "</table>";
    ?>
  </div>
</div>
