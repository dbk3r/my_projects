<?php
include_once "config.php";
include_once "db_functions.php";
$connection = sqlite_connect("../".$sqlite_db);
$connection->busyTimeout(5000);

  $kw = $_REQUEST['kw'];
  $jahr = date("Y");
  for ($kw ; $kw < kw(date("Y")); $kw++ )
  {

    $ts_mo = strtotime("{$jahr}-W{$kw}");
    $ts_so = strtotime("{$jahr}-W{$kw}-7");

    if ($kw == aktuelle_kw()) {
      echo "<div id='curr_week'><h3>KW ". $kw . " (" . date("d.m.Y", $ts_mo) . " - ". date("d.m.Y", $ts_so) .")</h3></div>";
    } else {
      echo "<br>KW " . $kw . " (" . date("d.m.Y", $ts_mo). " - ". date("d.m.Y", $ts_so) .")<br>";
    }

    echo "<table id='maint-table' width='100%' border='0' cellpadding='4' cellspacing='1'>";
    echo "<tr class='theader'><td colspan=2>Wartungsgruppe</td></tr>";
    $sql = "select * from maintenance where kw='". $kw ."' and jahr='". aktuelles_jahr() ."'";
    $results = $connection->query($sql);
    while ($row = $results->fetchArray()) {
      if ($row['mstate'] == '2') { $state="green"; $title= "erledigt"; }
      elseif ($row['mstate'] == '3') { $state = "red" ; $title= "wichtig";}
      elseif ($row['mstate'] == '4') { $state="yellow";$title= "offen";}
      elseif ($row['mstate'] == '5') { $state="brown";$title= "pausiert";}
      elseif ($row['mstate'] == '6') { $state="orange";$title= "in Arbeit";}

      else { $state= ""; $title= "";}

      $mgroup_result = $connection->querySingle("select * from maintenancegroups where id='" . $row['mgroup'] . "'", true);
      echo "<tr class='tbl-row' wp_id='". $row['id'] ."' kw='". $kw ."' id='". $row['mgroup']  ."'><td width=12 title='". $title. "' bgcolor='". $state . "'></td><td>".  $mgroup_result['maintenancegrp'] ."</td></tr>";
    }
    echo "</table>";
  }
 ?>

 <?php $rn = rand(1, 999999999999); ?>
 <script type="text/javascript" charset="utf-8" src="js/side-wartung.js?r=<?php echo $rn; ?>"></script>
