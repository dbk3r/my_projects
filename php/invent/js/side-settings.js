


$(document).ready( function() {

  $("#settings-tabs").tabs({
    active: 0
  });
  $("#settings-invent-tabs").tabs({
    active: 0
  });
  $("#settings-repair-tabs").tabs({
    active: 0
  });
  $("#settings-wartung-tabs").tabs({
    active: 0
  });


  $(".sbutton").click(function() {
    $.post("include/db_execute.php",
    {
      action: "update",
      object: "settings",
      db_id : $(this).attr("db-id"),
      tbl: $(this).attr("tbl"),
      column: $(this).attr("column1"),
      column2: $(this).attr("column2"),
      value: $("#"+$(this).attr("column1")+"-"+$(this).attr("db-id")).val(),
      value2: $("#"+$(this).attr("column2")+"-"+$(this).attr("db-id")).val()
    },
    function(data, status){
        alert(data);
    });

  });


  $(".smail-button").click(function() {    

    if(this.id == "save") {
      $.post("include/db_execute.php",
      {
        action: "savemaintmail",
        uuid: $(this).attr("uuid"),
        mailtitel: $("#mail-titel-"+$(this).attr("uuid")).val(),
        mailabsender: $("#mail-absender-"+$(this).attr("uuid")).val(),
        mailempfaenger: $("#mail-empfaenger-"+$(this).attr("uuid")).val(),
        mailkopf: $("#mail-kopf-"+$(this).attr("uuid")).val(),
        mailtext: $("#mail-text-"+$(this).attr("uuid")).val()
      },
      function(data, status){
          alert(data);
      });
    }

    if(this.id == "delete") {

      if (confirm('Willst Du wirkleich den Eintrag löschen?')) {
        $("#mm-" + $(this).attr("uuid")).remove();
        $.post("include/db_execute.php",
        {
          action: "delmaintmail",
          uuid: $(this).attr("uuid")
        },
        function(data, status){
          alert(data);
        });
      } else {
      }

    }

    if(this.id == "new") {
      if(!$("#newmail-titel").val()){
        var fehler = "Folgende Felder müssen ausgefüllt sein \n\n- Titel";
      }
      if(!$("#newmail-absender").val()){
        var fehler = fehler + "\n- Absender";
      }
      if(!$("#newmail-empfaenger").val()){
        var fehler = fehler + "\n- Empfänger";
      }
      if(!$("#newmail-kopf").val()){
        var fehler = fehler + "\n- Betreff";
      }
      if(!$("#newmail-text").val()){
        var fehler = fehler + "\n- Mail Text";
      }
      if (fehler) {
        alert(fehler);
      } else {

        $.post("include/db_execute.php",
        {
          action: "addmaintmail",
          mailtitel: $("#newmail-titel").val(),
          mailabsender: $("#newmail-absender").val(),
          mailempfaenger: $("#newmail-empfaenger").val(),
          mailkopf: $("#newmail-kopf").val(),
          mailtext: $("#newmail-text").val()
        },
        function(data, status){
            alert(data);
        });
        $("#newmail-titel").val("");
        $("#newmail-text").val("");
      }

    }
  });


  $(".smenu-button").click(function() {

    if($("#text-"+this.id).val()) {
      $.post("include/db_execute.php",
      {
        action: "add",
        object: "settings",
        table: this.id,
        column: $(this).attr("column"),
        value: $("#text-"+this.id).val()
      },
      function(data, status){

      });

      $("#div-"+this.id).prepend("<input alt='Seite neu laden, um Feld zu bearbeiten' title='Seite neu laden, um Feld zu bearbeiten' disabled class='textfields' value='"+ $("#text-"+this.id).val() +"'>");
      $("#text-"+this.id).val("");
    }

    if($("#rtext-"+this.id).val()+$(this).attr("grp")) {

      $.post("include/db_execute.php",
      {
        action: "add",
        object: "tbs",
        table: this.id,
        grp: $(this).attr("grp"),
        column: $(this).attr("column"),
        value: $("#rtext-" + this.id + "-" + $(this).attr("grp")).val()
      },
      function(data, status){

      });
      $("#repair-"+this.id+"-"+$(this).attr("grp")).prepend("<input disabled class='textfields' value='"+ $("#rtext-" + this.id + "-" + $(this).attr("grp")).val() +"'>");
      $("#rtext-"+this.id).val("")+$(this).attr("grp");

    }

  });



  $(".wp-button").click(function() {

      var start_kw=prompt("Start Kalenderwoche");
      if (start_kw) {
        $.post("include/db_execute.php",
        {
          action: "createWP",
          mgroup: this.id,
          interval: $(this).attr("interval"),
          startKW: start_kw
        },
        function(data, status){
            alert(data);
        });
        $("#"+this.id).remove();
    }
  });


  $(".textfields").keyup(function() {

    $.post("include/db_execute.php",
    {
      action: "update",
      object: "settings",
      db_id:   $(this).attr("db_id"),
      tbl: this.id,
      column: $(this).attr("column"),
      value: $(this).val()
    },
    function(data, status){

    });

  });



  $(".del-row").click(function() {

    if (confirm('Willst Du wirkleich den Eintrag löschen?')) {
      $("#"+this.id + "-" + $(this).attr("db_id")).remove();
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

});
