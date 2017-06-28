
$(document).ready( function() {


  $("#choose-kw").on('change', function() {
    $("#kw-ansicht").empty();
    $("#kw-ansicht").load('include/kw-ansicht.php', {'kw': this.value});
  });

  $(".smail-button").click(function(event) {
    event.stopImmediatePropagation();

      $.post("include/db_execute.php",
      {
        action: "sendmail",
        uuid: $(this).attr("uuid")
      },
      function(data, status){
          alert(data);
      });

  });


  $(".tbl-row-single").click(function() {
    $(".save_maint").unbind('click');
    $(".wp-button").unbind('click');
    $(".sub-content").empty();
    $(".sub-content").load("include/sub-wartung-srv.php?uuid="+$(this).attr('uuid')+"&hostname="+$(this).attr("hostname"));

    $("#maint-table tr").not($(".theader")).css("background-color","#dddddd");
    $("#maint-table tr").not($(".theader")).css("color","black");
    $(this).css("background-color","#3498db");
    $(this).css("color","white");
  });

  $(".tbl-row").click(function() {
    $(".save_maint").unbind('click');
    $(".wp-button").unbind('click');
    $(".sub-content").empty();
    $(".sub-content").load("include/sub-wartung.php?grp="+this.id+"&wp_id="+$(this).attr("wp_id")+"&kw="+$(this).attr("kw"));

    $("#maint-table tr").not($(".theader")).css("background-color","#dddddd");
    $("#maint-table tr").not($(".theader")).css("color","black");
    $(this).css("background-color","#3498db");
    $(this).css("color","white");
  });

  $("#maint-tabs").tabs({
    active: 0
  });



});
