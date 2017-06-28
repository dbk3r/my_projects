<?php
  include_once "config.php";
  include_once "db_functions.php";
  $connection = sqlite_connect("../".$sqlite_db);

  $row_template = "<div id='[tbl]-[db-id]'><table width=100%><tr><td><input column='[column]' id='[tbl]' db_id='[db-id]' class='textfields' value='[row]'></td><td width=8><img width=10 height=10 src='img/trash-16.png' class='del-row' id='[tbl]' db_id='[db-id]'></td></tr></table></div>";
  $maintenance_template = "<div id='[tbl]-[db-id]'><table width=100%><tr><td><input column='[column]' id='[tbl]' db_id='[db-id]' class='textfields' value='[row]'></td><td width=20><input column='[wi]' id='[tbl]' db_id='[db-id]' class='textfields' value='[weekinterval]' title='[weeks]'></td><td align=center width=18> <img width=10 height=10 src='img/trash-16.png' class='del-row' id='[tbl]' db_id='[db-id]'></td></tr></table></div>";


?>

<h3>Settings</h3>

<div id="settings-tabs">
  <ul>
    <li><a href="#invent-tab">Inventar</a></li>
    <li><a href="#wartung-tab">Wartung</a></li>
    <li><a href="#repair-tab">Reparatur</a></li>
  </ul>

  <div id="invent-tab">
    <h3>Inventar Einstellunegn</h3>


    <div id="settings-invent-tabs">
      <ul>
        <li><a href="#invent-tab-locations">Standort</a></li>
        <li><a href="#invent-tab-rooms">Raum</a></li>
        <li><a href="#invent-tab-inventgroups">Inventargruppen</a></li>
        <li><a href="#invent-tab-wsusgroups">WSUS-Gruppen</a></li>
        <li><a href="#invent-tab-assignments">Zurdnung</a></li>
        <li><a href="#invent-tab-states">Status</a></li>
        <li><a href="#invent-tab-descriptions">Gerätebezeichnung</a></li>
        <li><a href="#invent-tab-category">Kategorie</a></li>
      </ul>

      <div id="invent-tab-locations">

        <table width="100%">
          <tr><td><input class="textfield" id="text-locations"></td><td width="20"><button class="smenu-button" id="locations" column="location" >hinzufügen</button></td></tr>
        </table>

        <div class="set-box" id="div-locations">
        <?php
          $sql = "select * from locations";
          $results = $connection->query($sql);
          while ($row = $results->fetchArray()) {
            $c = str_replace("\r", "", utf8_encode($row['location']));
            $c = str_replace("\n", "", utf8_encode($row['location']));
            $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"locations", $c, "localtion"], $row_template);
            echo $row_output;
          }
        ?>
        </div>
      </div>

      <div id="invent-tab-rooms">

        <table width="100%">
          <tr><td><input class="textfield" id="text-rooms"></td><td width="20"><button id="rooms" class="smenu-button" column="room">hinzufügen</button></td></tr>
        </table>

        <div class="set-box" id="div-rooms">
        <?php
          $sql = "select * from rooms order by room";
          $results = $connection->query($sql);
          while ($row = $results->fetchArray()) {
            $c = str_replace("\r", "", utf8_encode($row['room']));
            $c = str_replace("\n", "", utf8_encode($row['room']));
            $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"rooms", $c, "room"], $row_template);
            echo $row_output;
          }
        ?>
        </div>
      </div>

      <div id="invent-tab-inventgroups">

          <table width="100%">
            <tr><td><input class="textfield" id="text-inventgroups"></td><td width="20"><button id="inventgroups" class="smenu-button" column="inventgrp">hinzufügen</button></td></tr>
          </table>

          <div class="set-box" id="div-inventgroups">
          <?php
            $sql = "select * from inventgroups";
            $results = $connection->query($sql);
            while ($row = $results->fetchArray()) {
              $c = str_replace("\r", "", utf8_encode($row['inventgrp']));
              $c = str_replace("\n", "", utf8_encode($row['inventgrp']));
              $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"inventgroups", $c, "inventgrp"], $row_template);
              echo $row_output;
            }
          ?>
          </div>
        </div>


          <div id="invent-tab-wsusgroups">

              <table width="100%">
                <tr><td><input class="textfield" id="text-wsusgroups"></td><td width="20"><button id="wsusgroups" class="smenu-button" column="wsusgrp">hinzufügen</button></td></tr>
              </table>

              <div class="set-box" id="div-wsusgroups">
              <?php
                $sql = "select * from wsusgroups";
                $results = $connection->query($sql);
                while ($row = $results->fetchArray()) {
                  $c = str_replace("\r", "", utf8_encode($row['wsusgrp']));
                  $c = str_replace("\n", "", utf8_encode($row['wsusgrp']));
                  $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"wsusgroups", $c, "wsusgrp"], $row_template);
                  echo $row_output;
                }
              ?>
              </div>
            </div>

        <div id="invent-tab-assignments">

            <table width="100%">
              <tr><td><input class="textfield" id="text-assignments"></td><td width="20"><button id="assignments" class="smenu-button" column="assignment">hinzufügen</button></td></tr>
            </table>

            <div class="set-box" id="div-assignments">
            <?php
              $sql = "select * from assignments";
              $results = $connection->query($sql);
              while ($row = $results->fetchArray()) {
                $c = str_replace("\r", "", utf8_encode($row['assignment']));
                $c = str_replace("\n", "", utf8_encode($row['assignment']));
                $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"assignments", $c, "assignment"], $row_template);
                echo $row_output;
              }
            ?>
            </div>
          </div>

          <div id="invent-tab-states">

              <table width="100%">
                <tr><td><input class="textfield" id="text-states"></td><td width="20"><button id="states" class="smenu-button" column="state">hinzufügen</button></td></tr>
              </table>

              <div class="set-box" id="div-states">
              <?php
                $sql = "select * from states";
                $results = $connection->query($sql);
                while ($row = $results->fetchArray()) {
                  $c = str_replace("\r", "", utf8_encode($row['state']));
                  $c = str_replace("\n", "", utf8_encode($row['state']));
                  $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"states", $c, "state"], $row_template);
                  echo $row_output;
                }
              ?>
              </div>
            </div>

            <div id="invent-tab-descriptions">

              <table width="100%">
                <tr><td><input id="text-descriptions" class="textfield"></td><td width="20"><button id="descriptions" class="smenu-button" column="description">hinzufügen</button></td></tr>
              </table>
              <div class="set-box" id="div-descriptions">
                <?php
                  $sql = "select * from descriptions";
                  $results = $connection->query($sql);
                  while ($row = $results->fetchArray()) {
                    $c = str_replace("\r", "", utf8_encode($row['description']));
                    $c = str_replace("\n", "", utf8_encode($row['description']));
                    $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"descriptions", $c, "description"], $row_template);
                    echo $row_output;
                  }
                ?>
                </div>
              </div>

              <div id="invent-tab-category">

                <table width="100%">
                  <tr><td><input id="text-category" class="textfield"></td><td width="20"><button id="category" class="smenu-button" column="cat">hinzufügen</button></td></tr>
                </table>
                <div class="set-box" id="div-category">
                  <?php
                  $sql = "select * from category";
                  $results = $connection->query($sql);
                  while ($row = $results->fetchArray()) {
                    $c = str_replace("\r", "", utf8_encode($row['cat']));
                    $c = str_replace("\n", "", utf8_encode($row['cat']));
                    $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"category", $c, "cat"], $row_template);
                    echo $row_output;
                    }
                    ?>
                  </div>
                </div>

        </div>
      </div>


    <div id="wartung-tab">

        <div id="settings-wartung-tabs">
          <ul>
            <li><a href="#wartung-tab-groups">Wartungsgruppen</a></li>
            <li><a href="#wartung-tab-plan">Wartungsplan</a></li>
            <li><a href="#wartung-tab-states">Wartungsstatus</a></li>
            <li><a href="#wartung-tab-mail">Wartungsmails</a></li>
          </ul>


          <div id="wartung-tab-groups">
            <h3>Wartungsgruppen bearbeiten</h3>
            <table width="100%">
              <tr><td><input class="textfield" id="text-maintenancegroups"></td><td width="20"><button id="maintenancegroups" class="smenu-button" column="maintenancegrp">hinzufügen</button></td></tr>
            </table>

            <div class="set-box" id="div-maintenancegroups">
            <?php
              $sql = "select * from maintenancegroups";
              $results = $connection->query($sql);
              while ($row = $results->fetchArray()) {
                $c = str_replace("\r", "", utf8_encode($row['maintenancegrp']));
                $c = str_replace("\n", "", utf8_encode($row['maintenancegrp']));
                $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]", "[wi]", "[weekinterval]", "[weeks]"], [$row['id'],"maintenancegroups", $c, "maintenancegrp", "weekinterval", $row['weekinterval'], "wird alle ". $row['weekinterval'] ." Wochen ausgeführt"], $maintenance_template);
                echo $row_output;
              }
            ?>
            </div>
          </div>

          <div id="wartung-tab-plan">
            <h3>Wartungsplan erstellen</h3>
            <table width="100%" border="0" cellpadding="4" cellspacing="1">
              <tr class="theader"><td>Wartungsgruppe</td><td align="center" width="30">Interval</td><td width="100"></td><tr>
            <?php
              $sql = "select * from maintenancegroups";
              $results = $connection->query($sql);
              while ($row = $results->fetchArray()) {
                $m_result = $connection->querySingle("select * from maintenance where mgroup='". $row['id'] ."' AND jahr='". date("Y") ."'", true);
                if (!isset($m_result['jahr'])) { $create_plan = "<button class='wp-button' id='". $row['id'] ."' interval='". $row['weekinterval'] ."'>Wartungsplan erstellen</button>"; } else {$create_plan = "Wartungsplan ". date("Y") . " aktiv";}
                echo  "<tr class='tbl-row'><td>". $row['maintenancegrp'] ."</td><td align='center'>". $row['weekinterval'] ."</td><td align=center>$create_plan</td></tr>";
              }

            ?>
            </table>
          </div>

          <div id="wartung-tab-states">
            <h3>Wartung-Status</h3>
            <table width="100%">
              <tr><td><input class="textfield" id="text-mstates"></td><td width="20"><button id="mstates" class="smenu-button" column="mstate">hinzufügen</button></td></tr>
            </table>

            <div class="set-box" id="div-mstates">
            <?php
              $sql = "select * from mstates";
              $results = $connection->query($sql);
              while ($row = $results->fetchArray()) {
                $c = str_replace("\r", "", utf8_encode($row['mstate']));
                $c = str_replace("\n", "", utf8_encode($row['mstate']));
                $row_output = str_replace(["[db-id]","[tbl]", "[row]", "[column]"], [$row['id'],"mstates", $c, "mstate"], $row_template);
                echo $row_output;
              }
            ?>
            </div>
          </div>

          <div id="wartung-tab-mail">
            <h3>Wartungs Mails</h3>
            <table width="100%">
              <tr><td>Titel</td><td><input class="textfield" id="newmail-titel" placeholder="Titel"></td></tr>
              <tr><td>Absender</td><td><input class="textfield" id="newmail-absender" value="vpms-admin@rbb-online.de"></td></tr>
              <tr><td>Empfänger</td><td><input class="textfield" id="newmail-empfaenger" value="cvd-brandenburg-aktuell@rbb-online.de,ai-potsdam@rbb-online.de,Abendschau@rbb-online.de,mazberlin@rbb-online.de,Zibb@rbb-online.de,cutvd-berlin@rbb-online.de,cutvd-potsdam@rbb-online.de"></td></tr>
              <tr><td>Betreff</td><td><input class="textfield" id="newmail-kopf" value="Info zum aktuellen VPMS-Wartungsfenster"></td></tr>
              <tr><td colspan="2"><textarea id="newmail-text" class="textfield" rows="6" placeholder="Mail Text"></textarea></td></tr>
              <tr><td colspan="2" align="right"><button id="new" class="smail-button">hinzufügen</button></td></tr>
            </table><br><hr><br>

            <!-- <div class="set-box" id="div-maintmail"> -->
            <?php
              $sql = "select * from maintmails";
              $results = $connection->query($sql);
              while ($row = $results->fetchArray()) {

                echo "<div id='mm-". $row['uuid'] ."'><table width=100% border=0>";
                echo "
                    <tr><td>Titel</td><td><input class='textfield' id='mail-titel-". $row['uuid'] ."' value='". $row['mailtitel'] ."'></td></tr>
                    <tr><td>Absender</td><td><input class='textfield' id='mail-absender-". $row['uuid'] ."' value='". $row['mailabsender'] ."'></td></tr>
                    <tr><td>Empfänger</td><td><input class='textfield' id='mail-empfaenger-". $row['uuid'] ."' value='". $row['mailempfaenger'] ."'></td></tr>
                    <tr><td>Betreff</td><td><input class='textfield' id='mail-kopf-". $row['uuid'] ."' value='". $row['mailkopf'] ."'></td></tr>
                    <tr><td colspan='2'><textarea id='mail-text-". $row['uuid'] ."' class='textfield' rows='6' placeholder='Mail Text'>". $row['mailtext'] ."</textarea></td></tr>
                    <tr><td colspan='2' align='right'><button id='save' uuid='". $row['uuid'] ."' class='smail-button'>speichern</button><button id='delete' uuid='". $row['uuid'] ."' class='smail-button'>löschen</button></td></tr>
                ";
                echo "</table></div>";

              }
            ?>
            </div>
          <!--</div> -->

        </div>
    </div>

<?php
  $repair_tbs_template = "<div id='[tbl]-[db-id]'>";
  $repair_tbs_template .= "<table width='100%'>";
  $repair_tbs_template .= "<tr><td><input column1='[column1]' tbl='[tbl]' id='[column1]-[db-id]' db_id='[db-id]' class='textfields' value='[bezeichnung]'></td><td><img width=10 height=10 src='img/trash-16.png' class='del-row' id='[tbl]' db_id='[db-id]'></td></tr>";
  $repair_tbs_template .= "<tr><td><textarea rows='5' column2='[column2]' id='[column2]-[db-id]' db_id='[db-id]' tbl='[tbl]' class='textfields'>[textbaustein]</textarea></td><td></td></tr>";
  $repair_tbs_template .= "<tr><td><button class='sbutton' column1='[column1]' column2='[column2]' db-id='[db-id]' tbl='[tbl]' >speichern</button></td><td></td></tr>";
  $repair_tbs_template .= "</table><hr class='trenner'>";
  $repair_tbs_template .= "</div>";
 ?>

  <div id="repair-tab">
      <h3>Reparatur Einstellunegn</h3>
      <div id="settings-repair-tabs">
      <ul>
        <li><a href="#repair-tab-garantie">Garantie</a></li>
        <li><a href="#repair-tab-kva">Kostenvoranschlag</a></li>
        <li><a href="#repair-tab-lieferschein">Lieferschein</a></li>
        <li><a href="#repair-tab-angebot">Angebot</a></li>
        <li><a href="#repair-tab-auftrag">Auftrag</a></li>
      </ul>

      <div id="repair-tab-garantie">
        <h3>Textbausteine</h3>
        <table width="100%">
          <tr><td><input class="textfield" id="rtext-textbausteine-garantie"></td><td width="20"><button id="textbausteine" class="smenu-button" grp="garantie" column="bezeichnung">hinzufügen</button></td></tr>
        </table>

        <div id="repair-textbausteine-garantie">
          <?php
            $sql = "select * from textbausteine where grp='garantie'";
            $results = $connection->query($sql);
            while ($row = $results->fetchArray()) {
              $row_output = str_replace(["[db-id]","[tbl]", "[bezeichnung]", "[column1]", "[column2]", "[textbaustein]"], [$row['id'],"textbausteine",$row['bezeichnung'], "bezeichnung", "textbaustein", $row['textbaustein']], $repair_tbs_template);
              echo $row_output;
            }
          ?>
        </div>

      </div>


      <div id="repair-tab-kva">
        <h3>Textbausteine</h3>
        <table width="100%">
          <tr><td><input class="textfield" id="rtext-textbausteine-kva"></td><td width="20"><button id="textbausteine" class="smenu-button" grp="kva" column="bezeichnung">hinzufügen</button></td></tr>
        </table>

        <div id="repair-textbausteine-kva">
          <?php
            $sql = "select * from textbausteine where grp='kva'";
            $results = $connection->query($sql);
            while ($row = $results->fetchArray()) {
              $row_output = str_replace(["[db-id]","[tbl]", "[bezeichnung]", "[column1]", "[column2]", "[textbaustein]"], [$row['id'],"textbausteine",$row['bezeichnung'], "bezeichnung", "textbaustein", $row['textbaustein']], $repair_tbs_template);
              echo $row_output;
            }
          ?>
        </div>
      </div>

      <div id="repair-tab-lieferschein">
        <h3>Textbausteine</h3>
        <table width="100%">
          <tr><td><input class="textfield" id="rtext-textbausteine-lieferschein"></td><td width="20"><button id="textbausteine" class="smenu-button" grp="lieferschein" column="bezeichnung">hinzufügen</button></td></tr>
        </table>

        <div id="repair-textbausteine-lieferschein">
          <?php
            $sql = "select * from textbausteine where grp='lieferschein'";
            $results = $connection->query($sql);
            while ($row = $results->fetchArray()) {
              $row_output = str_replace(["[db-id]","[tbl]", "[bezeichnung]", "[column1]", "[column2]", "[textbaustein]"], [$row['id'],"textbausteine",$row['bezeichnung'], "bezeichnung", "textbaustein", $row['textbaustein']], $repair_tbs_template);
              echo $row_output;
            }
          ?>
        </div>
      </div>

      <div id="repair-tab-angebot">
        <h3>Textbausteine</h3>
        <table width="100%">
          <tr><td><input class="textfield" id="rtext-textbausteine-angebot"></td><td width="20"><button id="textbausteine" class="smenu-button" grp="angebot" column="bezeichnung">hinzufügen</button></td></tr>
        </table>

        <div id="repair-textbausteine-angebot">
          <?php
            $sql = "select * from textbausteine where grp='angebot'";
            $results = $connection->query($sql);
            while ($row = $results->fetchArray()) {
              $row_output = str_replace(["[db-id]","[tbl]", "[bezeichnung]", "[column1]", "[column2]", "[textbaustein]"], [$row['id'],"textbausteine",$row['bezeichnung'], "bezeichnung", "textbaustein", $row['textbaustein']], $repair_tbs_template);
              echo $row_output;
            }
          ?>
        </div>
      </div>

      <div id="repair-tab-auftrag">
        <h3>Textbausteine</h3>
        <table width="100%">
          <tr><td><input class="textfield" id="rtext-textbausteine-auftrag"></td><td width="20"><button id="textbausteine" class="smenu-button" grp="auftrag" column="bezeichnung">hinzufügen</button></td></tr>
        </table>

        <div id="repair-textbausteine-auftrag">
          <?php
            $sql = "select * from textbausteine where grp='auftrag'";
            $results = $connection->query($sql);
            while ($row = $results->fetchArray()) {
              $row_output = str_replace(["[db-id]","[tbl]", "[bezeichnung]", "[column1]", "[column2]", "[textbaustein]"], [$row['id'],"textbausteine",$row['bezeichnung'], "bezeichnung", "textbaustein", $row['textbaustein']], $repair_tbs_template);
              echo $row_output;
            }
          ?>
        </div>
      </div>

    </div>
  </div>


</div>
<?php $rn = rand(1, 999999999999); ?>
<script type="text/javascript" charset="utf-8" src="js/side-settings.js?r=<?php echo $rn; ?>"></script>
