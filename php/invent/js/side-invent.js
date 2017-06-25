
$(document).ready( function() {


  $("#invent-content").load("include/inventload.php");

  $("#add-invent").click(function() {
    $(".sub-content").load("include/sub-invent.php?action="+this.id);
  });

  $("#refresh-invent").click(function() {

  });

  $("#invent-suche").keyup(function() {
    $("#invent-content").load("include/inventload.php?search="+$("#invent-suche").val());
  });

});
