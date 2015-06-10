var loadGif='<img src="/library/images/270x96.gif">';
function logmein()
{
	var a=$("#loginfrom").attr("action");
	$.post(a,$("#loginfrom").serialize(true),function(b){
		if(b.result==0)
		{
			$('.loginBox input[type="text"], .loginBox input[type="password"]').css('background','#ffb3b3');
		}else
		{
				window.location.href='index.php';
		}
	},"json");
	return false
}

$(document).ready(function(){
	$("body").append('<div id="loading" class="loading" style="display:none;position:absolute;top: 45%;left: 45%;z-index: 1000;padding: 5px;text-align: center;color: #000;opacity: 0.8;filter:alpha(opacity=80);">'+loadGif+"<br>Loading...</div>");
	$("#loading").ajaxStart(function(){
		$(this).show()}).ajaxComplete(function(){
			$(this).hide()
		});
	$("#loginfrom").submit(function(){logmein();return false});
	if($.browser.msie){
	if(parseInt($.browser.version)<="8"){
		$("#ChangeBrowser").show()
		}
	}
	$(".download_chrome").click(function()
	{
		window.location.href("https://www.google.com/chrome/index.html?hl=th&amp;brand=CHMB&utm_campaign=th&amp;utm_source=th-ha-sea-th-sk-ae&amp;utm_medium=ha")
	});
});