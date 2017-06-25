function resizeElemets() {
  $(".logo").width($(".navigation").width() + 10);
  $(".sub-content").width($(window).width() - $(".main-content").width() - 50);
  //$(".sub-content").width($(window).width() - $(".main-content").width() - $(".navigation").width() - 63);
}


$(document).ready( function() {

  $("#close_edit_container").click(function() {
    $(".edit_container").fadeOut();
  });

  
  $(".edit_container").draggable({handle:".container_header"});



  $(".main-content").load("include/invent.php");

  $.ajaxPrefilter(function( options, original_Options, jqXHR ) {
    options.async = true;
  });

  setInterval(function(){ resizeElemets(); }, 100);

  $(window).resize(function() {
      $(".logo").width($(".navigation").width() + 10);
      $(".sub-content").width($(window).width() - $(".main-content").width() - 50);
      //$(".sub-content").width($(window).width() - $(".main-content").width() - $(".navigation").width() - 63);
  });

  $(".menu-button").click(function() {
    $(".main-content").load("include/" + this.id + ".php");
  });



});
