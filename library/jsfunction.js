<!--
(function($) {
  $.fn.serializeFiles = function() {
    var form = $(this),
        formData = new FormData()
        formParams = form.serializeArray();

    $.each(form.find('input[type="file"]'), function(i, tag) {
      $.each($(tag)[0].files, function(i, file) {
        formData.append(tag.name, file);
      });
    });

    $.each(formParams, function(i, val) {
      formData.append(val.name, val.value);
    });

    return formData;
  };
})(jQuery);

function poponload(url) {
    windows = window.open(url, "booking", "location=0,status=0,scrollbars=0,width=920,height=580,resizable=no");
    if (!windows) {
        alert('Your browser was blocked popup please allow popup from tutorcenter.org and try again.');
    }
    windows.moveTo(200, 120);
}

function NewPopup(pageURL, title, w, h) {
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    if (!targetWin) {
        alert('Your browser was blocked popup please allow popup from tutorcenter.org and try again.');
    }
}

function load_more_comment(url) {
    $.ajax({
        'url': url,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            $('#comment-content').append(data.comments);
        }
    });
}

function checkPwd(str) {
    if (str.length < 6) {
        return ("too_short");
    } else if (str.length > 50) {
        return ("too_long");
    } else if (str.search(/\d/) == -1) {
        return ("no_num");
    } else if (str.search(/[a-zA-Z]/) == -1) {
        return ("no_letter");
    } else if (str.search(/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\_\+]/) != -1) {
        return ("bad_char");
    }
    return ("ok");
}

function chkpin(strp) {
    var pv = $("#" + strp).val();
    return true;
    if (checkPwd(pv) == "ok") {
        $("#" + strp).css('border', '1px solid #11BB47');
        return true;
    } else if (checkPwd(pv) == "too_short") {
        $("#" + strp).val('');
        $("#" + strp).css('border', '1px solid red');
        $("#" + strp).focus();
        return false;
    } else if (checkPwd(pv) == "too_long") {
        $("#" + strp).val('');
        $("#" + strp).css('border', '1px solid red');
        $("#" + strp).focus();
        return false;
    } else if (checkPwd(pv) == "no_num") {
        $("#" + strp).val('');
        $("#" + strp).css('border', '1px solid red');
        $("#" + strp).focus();
        return false;
    } else if (checkPwd(pv) == "no_letter") {
        $("#" + strp).val('');
        $("#" + strp).css('border', '1px solid red');
        $("#" + strp).focus();
        return false;
    } else if (checkPwd(pv) == "bad_char") {
        $("#" + strp).val('');
        $("#" + strp).css('border', '1px solid red');
        $("#" + strp).focus();
        return false;
    }
}

function Post_Items(itemid, commentcmd, load) {

    var url = '/getpost.php?get_items=' + itemid;
    var commentMSG = $("#comment-input").val();

    if (commentcmd == 'save') {
        if (load == 0) url = '/getpost.php?get_items=' + itemid + '&comment_save=' + commentMSG
        else
        url = '/getpost.php?get_items=' + itemid + '&comment_save=' + commentMSG + '&more=' + load
    }

    $.ajax({
        'url': url,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            $('.loading').fadeOut();

        } //End Success
    }); //End Ajax
}

function cmts(itemid) {
    var comment_text = $(".c" + itemid + " #comment-input").val();
    if (comment_text == '') return false;
    url = '/getpost.php?get_items=' + itemid + '&comment_save=' + comment_text;

    $.ajax({
        'url': url,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            $(".c" + itemid + " #comment-input").val('');
            $('.c' + itemid + " > ul").prepend(data.comments);

        } //End Success
    }); //End Ajax
}

function more_cm(id) {
    var this_row = $('.c' + id + " > ul li").index() + 20;
    var st_row = $('.c' + id + " > ul li").index() + 1;
    var set_url = '/getpost.php?get_items=' + id + '&load=' + st_row + ',' + this_row;
    //alert(set_url);
    var cmmt = $('.c' + id + " > div.cmm").html();

    $('.c' + id + " > div.cmm").html('<img src="/library/images/270x16.gif"  class="loader_more">');
    $.ajax({
        'url': set_url,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            $('.c' + id + " > ul").append(data.comments);
            if (data.c == 0) $('.c' + id + " > div.cmm").remove();
            else $('.c' + id + " > div.cmm").html(cmmt);

        } //End Success
    }); //End Ajax
}

function cmdel(id) {
    var set_url = '/getpost.php?rmcm=' + id;
    $.ajax({
        'url': set_url,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            if (data.s == 1) $("#cm" + id).remove();
        } //End Success
    }); //End Ajax
}

function stdSetupSave() {
    var set_url = '/save.php?q=stdSetup';
    $.ajax({
        'url': set_url,
        'type': 'POST',
        'dataType': 'HTML',
        'data': $('#stdSetupFrm').serialize(),
        'success': function(data) {
            if(data==1)
                $('#stdSetupFrm').html("<h3>บันทึกการตั้งค่าเรียบร้อยแล้ว</h3>");
        } //End Success
    }); //End Ajax
    return false;
}

function liked(like_bid) {
    $.ajax({
        'url': '/save.php?q=like&set=' + like_bid,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            if (data.value != 'false') $('.liked_' + like_bid).text(data.value);
        }
    });
    return false;
}

function acupasschange(newpass)
{
    var acupasstools = $('.aculearn_password_toolbox');
    $('#acupassch').click(function(){
        var passtoolbox = acupasstools.html();
        var passcontainer = $('#passview');
        if(!newpass)
            newpass = passcontainer.data('pass');

        passcontainer.html('<input type="text" id="acupassword" value="'+newpass+'">');
        acupasstools.html('<a href="#" id="acupassconfirm" title="Save this password."><i class="fa fa-check" style="color: green"></i></a>');
        acupasssave(passtoolbox,passcontainer)
    })
    return false;
}

function acupasssave(passtoolbox,passcontainer){
    $('#acupassconfirm').click(function(){
        var acunewpass = $('#acupassword').val();
        $('.aculearn_password_toolbox').html(passtoolbox);
        passcontainer.html(acunewpass);
        passcontainer.data(acunewpass)
        acupasschange(acunewpass);
    });
    return false;
}

function SaveBody() {
    var display = document.getElementById('stat')
    var content = $('textarea.edit-body').val();
    var pid = $('input#pageid').val();

    $.post('/BodySave.php?saveid=' + pid, $('#editbody').serialize(true), function(data) {
    });
}

function getData(get, q, ds){
    $.post('/get.php?t='+q+'&get=' + get, function(res) {
        $('.lightbox').find(ds).html('');
        $('.lightbox').find(ds).html(res.body);
    });
}

function addPostFile(){
	 $('.lightbox').find('#postfile').click();
}

function lightbox(data,t){
    var lbexists = $('.lightbox').html();
    $('.lightbox-title').html('');
    $('.lbbody').html('');
	$('.filter').show();
    $.post('/get.php?t='+t+'&get=' + data, function(res) {

            $('.lightbox-title').html(res.title);
            $('.lbbody').html(res.body);

            showlimit();
           $('.lightbox').fadeIn(100);
           $('.lb-times').fadeIn(100);
           $('.lb-times').click(function(){
                $('.lightbox').hide();
				$('.filter').hide();
                $(this).hide()
            })
    });

	window.scrollTo(0,0);

}

function select_user(name, uid){
    $('body').find('.member-list').append('<span class="member_selected" id="member-id-'+uid+'">\
    <input type="hidden" name="member_group[]" value="'+uid+'">\
	<i class="fa fa-male fa-2x inlinepos text-green"></i> '+name+' \
    <a href="javascript:void(0);" onclick="$(\'#member-id-'+uid+'\').remove();">\
    <i class="fa fa-times"></i></a></span>');
    $('.uls').hide();
    $('#idCheck').val('').focus();
}

function stds(fid){
    var dataset = $(document).find(fid).serialize();
    $.post('/save.php?q=stds', dataset, function(res) {
        if(res==1){
            $('.success-check').addClass('fa-check-circle-o');
            $('.success-check').removeClass('fa-times');
            $('.success-check').show();
        }
        else{
            $('.success-check').removeClass('fa-check-circle-o');
            $('.success-check').addClass('fa-times');
            $('.success-check').show();
        }
    });
}

function ccs(fid,courseid){
    var dataset = $(document).find(fid).serialize();
    $.post('/save.php?q=ccs', dataset, function(res) {
        lightbox(courseid,'CourseStd');
    });

}


function unregisterccr(classid,courseid){
  $.post('/save.php?q=uncc', {"class":classid, "course":courseid}, function(res) {
        lightbox(courseid,'CourseStd');
    });
}

function addPoint(repid){
  $.post('/save.php?q=pointToreply', {"id":repid}, function(res) {
        $('body').find('.point-'+repid).html('มี 1 คะแนน');
    });
}

function delreply(repid){
  $.post('/save.php?q=remove_reply', {"id":repid}, function(res) {
        $('body').find('#rep-'+repid).remove();
    });
}

function form_to_json (selector) {
  var ary = $(selector).serializeArray();
  var obj = {};
  for (var a = 0; a < ary.length; a++) obj[ary[a].name] = ary[a].value;
  return obj;
}

function showlimit(){
            $('.morelimit').click(function(){
            var thst = $(this).data('stat');
            if(thst==0)
            {
                $('.limitHeight').css('height','auto');
                $('.limitHeight').css('overflow-y','auto');
                $(this).html('<i class="fa fa-minus-square-o"></i> ย่อให้เล็กลง');
                $(this).data('stat',1);
            }
            else{
                $('.limitHeight').css('height','50px');
                $('.limitHeight').css('overflow-y','hidden');
                $(this).html('<i class="fa fa-plus-square-o"></i> เพิ่มเติม');
                $(this).data('stat',0);
            }
        });
}

function SaveUpdate(post_id) {
    var display = $('.register-stat')
    var error_msg = 'ลงทะเบียนผิดพลาด';

    $.ajax({
        'url': '/save.php?action=register&id=' + post_id,
        'type': 'GET',
        'dataType': 'json',
        'success': function(data) {
            if (data.status == 'registered') {
                $('.booked-' + post_id).html('<a href="#" onclick="return false" rel="tooltip" original-title="คุณได้สมัครเรียนเรื่องนี้แล้ว"><span class="symbol booked">O</span></a>');
            }
        }
    });
}

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

function delete_post(postid)
{
	if(confirm("ต้องการลบเนื้อหานี้ใช่หรือไม่?...")==false) return false;
    $.ajax({
        'url': '/save.php?q=delete&id='+postid,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function (data)
            {
            if(data.result=='success')
                {
                    $('.post-'+postid).fadeOut(100, function(){ $(this).remove();});
                    $('.c'+postid).fadeOut(100, function(){ $(this).remove(); });
                }
            }
    });
}
function addreplyfile (id){
	$('#repf-'+id).click();
}
function replybtn(id){
	$('.rep-'+id).show();
}
$(document).ready(function() {


        $('body').append('<div class="lightbox"><div class="lb-times">Close <i class="fa fa-times"></i></div><div class="lbbody"></div></div><div class="filter"></div>');

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


    $("a[href$='\#']").click(function() {
        return false;
    });

    acupasschange();
    $('#acupassview').click(function(){
       $('#passview').html($('#passview').data('pass'));
       return false;
    })

    //Add  wmode="Opaque"
    $('#shb').keyup(function(e) {
        if (e.keyCode == 13) {
            var kw = $(this).val();
            if (kw == '') return false;
            window.location.href = '/acu-result?type=all&kk=' + kw;
        }
    });

    $(".search_type").click(function() {
        $(".searchOptions").slideToggle('fast');
        var searcharrow = $('.search_type').html();
        if (searcharrow == "s") $('.search_type').html("r");
        else
        $('.search_type').html("s");
    });
    if (!event.preventDefault) {
        event.preventDefault = function() {
            event.returnValue = false; //ie
        };
    }
    //Edit body Submit
    $("#editbody").submit(function() {
        var action = $("#editbody").attr("action");
        var pid = $('input#pageid').val();
        var pn = $('input#pn').val();
        $.post(action, $('#editbody').serialize(true), function(data) {
            if (data.stat == 1) {
                $('#alert_status').slideUp(300).fadeIn(400).html('Successfully!');
                $('#alert_status').delay(3000).fadeOut(400);
            } else {
                $('#alert_status').slideUp(300).fadeIn(400).html('Save fail!');
                $('#alert_status').delay(3000).fadeOut(400);
            }
        }, "json");
        return false;
    });

    $('a.script').click(function() //this will apply to all anchor tags
    {
        $('textarea.script-body').slideToggle();
    });

    $('#embed_code').click(function() {
        $(this).focus();
        $(this).select();
    });

    $('.embedbt').click(function() {
        $('#embed_code').show();
    });
});

function addfriend(friendID) {
    $.ajax({
        'url': '/social_func.php?g=add&fid=' + friendID,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            //alert(data.result);
            if (data.result == 'success') {
                $('a[rel$=friend-status]').html('<span class="icons2">P</span> ส่งคำร้องขอเป็นเพื่อนแล้ว');
                $('a[rel$=friend-status]').delay(2000).fadeOut();
            }
        }
    });
}

function pageTab(tabid){
    $('.pageobj').hide();
    switch(tabid){
        case 'setting':
            $('#pageConfig').show();
            break;
        case 'header':
            $('#pageHeader').show();
            break;
        case 'body':
            $('#pageBody').show();
            break;
    }
}
function accept_friend(accept_id) {
    $.ajax({
        'url': '/social_func.php?g=accept&uid=' + accept_id,
        'type': 'GET',
        'dataType': 'JSON',
        'success': function(data) {
            //alert(data.result);
            if (data.result == 'success') {
                $('.a' + accept_id).fadeOut();
            }
        }
    });
}

function alertme(showdata, type) {
    $('#alert_status').addClass(type);
    $('#alert_status .text').html(showdata);
    $('#alert_status').slideDown();
}

function loadMoreAction() {
    var old = $('.btmore').html();
    $('.btmore').html('<img src="/library/images/loading.gif" class="loader_more">');
    var size = $('li.content_line').size();
    var last = 10;
    var rang = size + ',' + last;
    $.ajax({
        'url': '/search.php?act=load_content&rang=' + rang,
        'type': 'GET',
        'dataType': 'HTML',
        'success': function(data) {
            $('.btmore').html(old);
            $('#slist,#slist-profile').append(data);
            post_fnc_del();
            $("a[href$='\#']").click(function() {
                return false;
            });
        }
    });
}


function autoResize(id) {
    var newheight;

    if (document.getElementById) {
        newheight = document.getElementById(id).contentWindow.document.body.scrollHeight;
    }
    var new_id = "#" + id;
    $(new_id).height(newheight);
}


function addslashes(str) {
    str = str.replace(/\\/g, '\\\\');
    str = str.replace(/\'/g, '\\\'');
    str = str.replace(/\"/g, '\\"');
    str = str.replace(/\0/g, '\\0');
    return str;
}

//-->