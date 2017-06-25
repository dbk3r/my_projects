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

 <table id="invent-table" width="100%" border="0" cellpadding="4" cellspacing="1">
   <tr class="theader">
       <td>Hostname</td>
       <td>Cname</td>
       <td>Bezeichnung</td>
       <td>IP-Adresse</td>
       <td>Standort</td>
       <td>Raum</td>
       <td>Aktionen</td>
   </tr>

   <?php
     $results = $connection->query("select * from inventar where bezeichnung like '%".$search."%' or hostname like '%".$search."%' or ip1 like '%".$search."%' or
                                                                serialnr like '%".$search."%' or inventarnr like '%".$search."%' or description like '%".$search."%' or
                                                                category like '%".$search."%' or cname like '%".$search."%' order by hostname LIMIT 50");
     while ($row = $results->fetchArray()) {
       echo "<tr class='invent-row' id='".  $row['id'] ."' uuid='".  $row['uuid'] ."'>";
       echo "<td>" . $row['hostname'] . "</td>";
       echo "<td>" . $row['cname'] . "</td>";
       echo "<td>" . $row['bezeichnung'] . "</td>";
       echo "<td>" . $row['ip1'] . "</td>";
       $res = $connection->querySingle("select * from locations where id='" . $row['location'] . "'", true);
       echo "<td>" . $res['location'] . "</td>";
       $res = $connection->querySingle("select * from rooms where id='" . $row['room'] . "'", true);
       if(isset($res['room'])) { echo "<td>" . $res['room'] . "</td>"; } else { echo "<td></td>";}
       echo "<td class='actions'>
            <img title='Datensatz löschen' width=10 height=10 class='del-invent' id='inventar' src='img/trash-16.png' db_id='". $row['id'] ."'>";
            $mres = $connection->querySingle("select * from maintenance where kw='" . aktuelle_kw() . "' and jahr='". aktuelles_jahr() ."' and mgroup='". $row['maintenancegrp']  ."'", true);
            if ($row['maintenancegrp'] and isset($mres['id'])) {

              echo " <img title='Wartung bearbeiten' width=10 height=10 class='maint-invent' grp='". $row['maintenancegrp'] ."' wpid='". $mres['id'] . "' kw='". aktuelle_kw() . "' src='img/construction.png' db_id='". $row['id'] ."'>";
            }
            echo " <img title='Reparatur auslösen' width=10 height=10 class='repair-invent' uuid='". $row['uuid'] ."' src='img/maintenance.png' db_id='". $row['id'] ."'>";
            echo "</td>";
       echo "</tr>";
     }
   ?>
 </table>


<script>
  $(document).ready( function() {

    $(".del-invent").click(function() {
      if (confirm('Willst Du wirkleich den Eintrag löschen?')) {
        $("#"+$(this).attr('db_id')).remove();
        $.post("include/db_execute.php",
        {
          action: "del",
          tbl: this.id,
          db_id: $(this).attr("db_id")
        },
        function(data, status){

        });
      } else {
      }
    });


    $(".maint-invent").click(function() {
      $(".edit-content").load("include/sub-wartung.php?grp="+$(this).attr("grp")+"&wp_id="+$(this).attr("wpid")+"&kw="+$(this).attr("kw"));
        $(".edit_container").fadeIn();
    });

    $(".invent-row").on( "click", function(event) {
      $(".sub-content").load("include/sub-invent.php?id="+ this.id +"&uuid="+$(this).attr("uuid"));
      $("#invent-table tr").not($(".theader")).css("background-color","#dddddd");
      $("#invent-table tr").not($(".theader")).css("color","black");

      $(this).css("background-color","#3498db");
      $(this).css("color","white");
    });



  });
</script>
