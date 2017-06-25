
$.fn.insertAtCaret = function(myValue) {
	return this.each(function() {
		var me = this;
		if (document.selection) { // IE
			me.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
			me.focus();
		} else if (me.selectionStart || me.selectionStart == '0') { // Real browsers
			var startPos = me.selectionStart, endPos = me.selectionEnd, scrollTop = me.scrollTop;
			me.value = me.value.substring(0, startPos) + myValue + me.value.substring(endPos, me.value.length);
			me.focus();
			me.selectionStart = startPos + myValue.length;
			me.selectionEnd = startPos + myValue.length;
			me.scrollTop = scrollTop;
		} else {
			me.value += myValue;
			me.focus();
		}
	});
};


function insertreplace(state,info,kw,uuid) {

  $.post("include/db_execute.php",
  {
    action: "update",
    object: "wartung",
    tbl:    "maintdetail",
    kw:     kw,
    state:  state,
    info:   info,
    uuid:   uuid
  },
  function(data, status){
    alert(data + "\n " + status);
  });

}



$(document).ready( function() {

	$('.upload_content').hover(function(){
    $(this).find('.close').animate({opacity:1},200)
	},function(){$(this).find('.close').animate({opacity:0},200)
	});

	$(".close").click(function(){
		if (confirm('Willst Du wirkleich den Eintrag löschen?')) {
			$("#"+$(this).attr("uuid")).remove();
			$.post("include/db_execute.php",
			{
				action: "delupload",
				uuid: $(this).attr("uuid"),
				filename: $(this).attr("filename")
			},
			function(data, status){

			});
		} else {
		}

	});

	$(".upload_invent").click(function(event){
		event.stopImmediatePropagation();
		$("#fileupload-dialog").click();
		var refid = $(this).attr("uuid");
		$("#fileupload-dialog").change(function(event){
			event.stopImmediatePropagation();

			$(this).simpleUpload("include/upload.php?refid="+refid, {

			allowedExts: ["jpg", "jpeg", "tif", "png", "gif"],
			allowedTypes: ["image/jpeg", "image/png", "image/x-png", "image/gif", "image/tiff"],
			maxFileSize: 5000000, //5MB in bytes

			start: function(file){
				//upload started
				console.log("upload started");
			},

			progress: function(progress){
				//received progress
				console.log("upload progress: " + Math.round(progress) + "%");
			},

			success: function(data){
				//upload successful
				console.log("upload successful!");
				console.log(data);
				udata = JSON.parse(data);

				var newConent = "<div class='upload_content' id='"+ udata.uuid +"'><div title='Content löschen' class='close' uuid='"+ udata.uuid +"'></div><a href='"+ udata.filename + "' data-featherlight='image' ><img width=14 height=14 src='"+ udata.filename + "'></a></div>";
				$("#invent_uploads-"+refid).prepend(newConent);

			},

			error: function(error){
				//upload failed
				alert(error.name + ": " + error.message);
				console.log("upload error: " + error.name + ": " + error.message);
			}

		});
		});

	});


  $(".textfield").keydown(function(e) {
          if (e.keyCode == 190 && e.ctrlKey) {
            var dNow = new Date();
            currentMonth = dNow.getUTCMonth() + 1;
            currentMonth = ("0" + currentMonth).slice(-2);
            currentDay = dNow.getUTCDate();
            currentDay =  ("0" + currentDay).slice(-2);
            currentYear = dNow.getUTCFullYear();
            currentHours = dNow.getHours();
            currentHours = ("0" + currentHours).slice(-2);
            currentMinutes = dNow.getMinutes();
            currentMinutes = ("0" + currentMinutes).slice(-2);
            var localtime = currentDay + '.' + currentMonth + '.' + currentYear + ' ' + currentHours + ':' + currentMinutes + ' #  ';

            $(this).insertAtCaret(localtime);
          }
      });

$(".edit_invent").click(function(event){
  event.stopImmediatePropagation();
  $(".edit-content").load("include/sub-invent.php?id="+this.id+"&uuid="+$(this).attr("uuid"));
    $(".edit_container").fadeIn();

});

$(".save_maint").click(function(event){
  event.stopImmediatePropagation();
  var uuid = $(this).attr("uuid");
  var infobox = $("#mdinfo-"+uuid).val();
  if($("#check-" + $(this).attr("uuid")).is(':checked')) {
    console.log('state on : ' + infobox);
    insertreplace("1", infobox, $(this).attr("kw"), $(this).attr("uuid"));
  } else {
    console.log('state off : ' + infobox);
    insertreplace("0", infobox, $(this).attr("kw"), $(this).attr("uuid"));
  }

});


$("#show_infos").change(function() {
  if($("#show_infos").is(':checked')) {
    $(".infos").show();
  } else {
    $(".infos").hide();
  }
});



$(".wp-button").click(function(event) {
  event.stopImmediatePropagation();
  $.post("include/db_execute.php",
  {
    action: "update",
    object: "settings",
    tbl:  $("#wp-save").attr("tbl"),
    column: $("#wp-save").attr("column"),
    value:  $("#wp_desc").val(),
    value2: $("#wp_status").val(),
    column2: $("#wp-save").attr("column2"),
    db_id: $("#wp-save").attr("dbid")

  },
  function(data, status){
    alert(data + "\n " + status);
  });
});

});
