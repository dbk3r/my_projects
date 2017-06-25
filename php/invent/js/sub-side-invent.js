


$(document).ready( function() {

  $("#remote").dblclick(function() {

    window.open( $(this).val(), '_blank');
  });

  $('#cat').autocomplete({
    source: function (request, response) {
      $.ajax( {
        url: "db/categories.php",
        dataType: "jsonp",
        data: {
          term: request.term
        },
        success: function( data ) {
          response( data );
        }
        } );
      },
      minLength: 1
   });

   $('#desc').autocomplete({
     source: function (request, response) {
       $.ajax( {
         url: "db/descriptions.php",
         dataType: "jsonp",
         data: {
           term: request.term
         },
         success: function( data ) {
           response( data );
         }
         } );
       },
       minLength: 1
    });

$(".box-header-btn").click(function() {
    $("#"+$(this).attr("box")).toggle();
    var up = "/invent/img/up.png";
    var down = "/invent/img/down.png";
    var src = $(this).attr('src');
    if(src == up){$(this).attr('src',down);}
    else{$(this).attr('src',up);}
});



  $(".action-button").click(function(event) {
    event.stopImmediatePropagation();

    if (this.id == "maintenance") {
      $(".edit-content").load("include/sub-wartung.php?grp="+$(this).attr("mgrp")+"&wp_id="+$(this).attr("wpid")+"&kw="+$(this).attr("kw"));
        $(".edit_container").fadeIn();
    }

    if (this.id == "save-invent") {

      $.post("include/db_execute.php",
      {
        action: "update",
        object: "invent",
        id: $("#invent-id").val(),
        uuid: $("#invent-uuid").val(),
        inventarnr: $("#invent-nr").val(),
        serialnr: $("#sn-nr").val(),
        description: $("#desc").val(),
        bezeichnung: $("#bezeichnung").val(),
        category: $("#cat").val(),
        hostname: $("#host").val(),
        cname: $("#cname").val(),
        location: $("#ort").val(),
        room: $("#room").val(),
        inventgrp: $("#inventgrp").val(),
        wsusgrp: $("#wsusgrp").val(),
        remote: $("#remote").val(),
        maintenancegrp: $("#maintenancegrp").val(),
        ip1: $("#ip-1").val(),
        ip2: $("#ip-2").val(),
        switch1: $("#switch1").val(),
        switch2: $("#switch2").val(),
        switchport1: $("#switchport1").val(),
        switchport2: $("#switchport2").val(),
        ip3: $("#ip-3").val(),
        ip4: $("#ip-4").val(),
        vlan: $("#vlan").val(),
        assignment: $("#zuordnung").val(),
        state: $("#status").val(),
        supportdistributor: $("#support-distributor").val(),
        supportstarttime: $("#invent-nr").val(),
        supportendtime: $("#support-end").val(),
        supportdetails: $("#support-details").val(),
        wartungshinweis: $("#wartungs-hinweis").val()
      },
      function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
        $("#invent-content").load("include/inventload.php?search="+$("#invent-suche").val());
      });
    }

    if (this.id == "new-invent") {

      $.post("include/db_execute.php",
      {
        action: "add",
        object: "invent",
        inventarnr: $("#invent-nr").val(),
        serialnr: $("#sn-nr").val(),
        description: $("#desc").val(),
        bezeichnung: $("#bezeichnung").val(),
        category: $("#cat").val(),
        hostname: $("#host").val(),
        cname: $("#cname").val(),
        location: $("#ort").val(),
        room: $("#room").val(),
        inventgrp: $("#inventgrp").val(),
        wsusgrp: $("#wsusgrp").val(),
        remote: $("#remote").val(),
        maintenancegrp: $("#maintenancegrp").val(),
        ip1: $("#ip-1").val(),
        ip2: $("#ip-2").val(),
        switch1: $("#switch1").val(),
        switch2: $("#switch2").val(),
        switchport1: $("#switchport1").val(),
        switchport2: $("#switchport2").val(),
        ip3: $("#ip-3").val(),
        ip4: $("#ip-4").val(),
        vlan: $("#vlan").val(),
        assignment: $("#zuordnung").val(),
        state: $("#status").val(),
        supportdistributor: $("#support-distributor").val(),
        supportstarttime: $("#invent-nr").val(),
        supportendtime: $("#support-end").val(),
        supportdetails: $("#support-details").val(),
        wartungshinweis: $("#wartungs-hinweis").val()
      },
      function(data, status){

        if(status == "success") {
          alert("Datensatz mit uuid " + data + " erfolgreich angelegt");
        }
        $("#invent-content").load("include/inventload.php?search="+$("#invent-suche").val());
        $(".sub-content").load("include/sub-invent.php?uuid="+data);
      });
    }


  });

});
