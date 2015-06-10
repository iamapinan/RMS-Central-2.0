  //menus
	$(".user-name").click(function (event) {
	  event.stopPropagation();
      $("#umenu").toggle();
       var zIndexNumber = 1000;
       $("#umenu").css("zIndex", zIndexNumber);
    });
	$('#shb, .user-text').click(function (event){
		event.stopPropagation();
	});
	$("body").click(function() {
		 $("#umenu").hide();
		 $('.msg-alert-popup').hide();
		 //$('#shb').css('width','60px');
		 //$("#slides").fadeIn('fast');
	});


function fnc_org_add()
{
	var orgn = $('#schoolsh').val();
	var call_url = '/profilesave.php?action=newp';
		$.post(call_url, $("#newprofile").serialize(true),function(r){
			if(r.res==1)
				window.location='/create?do=neworg&org='+orgn;
		}, 'json');
}

function create_new_org()
{
	var orgn = $('#schoolsh').val();
	var call_url = '/save.php?action=neworg';
		$.post(call_url, $("#neworg").serialize(true),function(r){
			if(r.res==1)
				window.location=r.redirect;
			else
			{
				$('#alert_status').show();
				$('#alert_status .text').html('ท่านต้องกรอกข้อมูลให้ครบถ้วนก่อนดำเนินการต่อ');
			}
		}, 'json');
}

$('#verify_save').click(function(){
	var ogid = $('#schoolid').val();
	var roid = $('#roleid').val();
	var schn = $('#schoolsh').val();
	if((ogid==''||schn=='')||roid=='')
	{
		if(ogid==''&&schn!='')
			fnc_org_add();

		$('#alert_status').show();
		$('#alert_status .text').html('กรุณาเติมข้อมูลในช่องว่างก่อนดำเนินการต่อ');
		return false;
	}
	else
	{
		var call_url = '/profilesave.php?action=newp';
		$.post(call_url, $("#newprofile").serialize(true),function(r){
			window.location='/verify';
		}, 'json');
		return false;
	}
});

$('#regis_save').click(function(){
	var ogid = $('#schoolid').val();
	var roid = $('#role').find(':selected').val();
	var schn = $('#schoolsh').val();
	var pass = $('#password').val();

	if((ogid==''||schn=='')||roid==''||pass=='')
	{
		if(ogid==''&&schn!='')
			fnc_org_add();

		$('#alert_status').show();
		$('#alert_status .text').html('กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนดำเนินการต่อ');
		return false;
	}
	else
	{
		return true;
	}
});

$('#schoolsh').keypress(function(e) {
		$('#shoolshpre').show();
		//alert($(this).val());
		var this_keyword = $(this).val();
		$('#shoolshpre').show();
		$.post("/search.php?act=schoolsh", { q: this_keyword}).done(function (data)
				{
					$('.scview').html(data);
				});
});

$(document).ready(function(){
	//Search input click
		//$('.master-li').hover(function(){ $('.child-li').show(); },function(){$('.child-li').hide(); });
		$('#MainSearch').click(function(ev){
			ev.stopPropagation();
			if($(this).val()!='')
				$('.search_content_preview').show(function(){ $(this).effect();});
		});
		$('#schoolsh').click(function()
		{
			$('#schoolsh').show();
		});
		$('#mySearch').click(function(ev){
			ev.stopPropagation();
		if($(this).val()!='')
			$('.mysearch_content_preview').show(function(){ $(this).effect();});
		});

		$("#roleid").change( function() {
			switch_role($(this).val());
		});

		$('.status_close').click(function(){
			$('#alert_status').slideUp(300);
		});
});//Close ready,

function delete_post(postid)
{
	$.ajax({
		'url': '/save.php?q=delete&id='+postid,
		'type': 'GET',
		'dataType': 'JSON',
		'success': function (data)
			{
			if(data.result=='success')
				{
					$('.post-'+postid).fadeOut("slow", function(){ $(this).remove();});
					$('.c'+postid).fadeOut("slow", function(){ $(this).remove(); });
				}
			}
	});
}