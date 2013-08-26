$(document).ready(function() {
var currentTime = new Date()
var h = currentTime.getHours()
var m = currentTime.getMinutes()
$.uid = function(p,s) {
return ((p) ? p : '') + Math.random().toString().replace('.','') + ((s) ? s : '');
};
var uid = $.md5(h + m + $.uid());
$('#footer').css("display","none");
$("#domains").val("http://yahoo.com\nhttp://google.com"); 
$("#scan_paste").click(function () {
var domains = $.base64.encode($('#domains').val())  ;
$("#footer").html("<center>loading....</center>");
$('#footer').fadeIn(2000);
$.post("engine/process.php", { domain_list: domains, engine: "start", UID: uid  })
.done(function(data) {
$("#footer").html("<center><pre>" + data + "</pre></center>");
var obj = jQuery.parseJSON(data);
if(obj.Status == "ok") {
$("#footer").html("<center><pre>" + data + "</pre></center>");
window.setTimeout(function() {
window.location.replace("report.php?id="+uid);
}, 2000);
$("#footer").fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100);
} 
});
});
//On report page


});
