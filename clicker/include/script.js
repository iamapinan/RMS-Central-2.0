$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})


var number;
var timeout;
var last = 1;

function repeat() {
	var bgmSound = $("#BGMPlayer")[0];
	var delaytime = $('#playtime').val()+'000';
	bgmSound.play();
	random_once();
	setTimeout(function(){
		clearTimeout(timeout);
		bgmSound.pause();
		bgmSound.currentTime = 0;
	}, delaytime);
	
};

function mediaSorting(data, currentses){
	$.post('update.php?q=mediasort', {'sorted': data, 'sess':currentses}, function(data){
		console.log(data);
	}, "JSON")
}
function random_once(){
   var maxcount = $('#stdcount').val();
   //number = chance.integer({min: 1, max: maxcount});
   number = Math.floor((Math.random() * maxcount) + 1);
   
   console.log(number);
   
   var userName;
   var currentId
   timeout = setTimeout(function(){
   	  repeat();

   	   var Selected = $('#'+number).data('photo');
	   var coin = $('#'+number).data('coin');
	   var gold = $('#'+number).data('gold');
	   var crystal = $('#'+number).data('crystal');

	  $('.random-mark').removeClass('random-mark'); //Clear mask.
	  $('#'+number).addClass('random-mark');
	  userName = $('#'+number+' .stdCap').html()
	  currentId = $('#'+number).data('user');
	  $('.workspace-image').html('<img src="'+Selected+'" class="img-circle">');
	  $('.workspace-info').html('<div class="caption"><h1>'+userName+'</h1>\
	  	<h3><img src="img.php?width=30&height=30&image=/clicker/include/images/Crystal.png" class="crystal-img button hvr-grow"> <span id="crystal-point">'+crystal+'</span>\
	  	 <a class="btn btn-success" onclick="addPoint(\'crystal\',\''+currentId+'\');">+1</a></h3>\
	  	<h3><img src="img.php?width=30&height=30&image=/clicker/include/images/Gold.png" class="gold-img button hvr-grow"> <span id="gold-point">'+gold+'</span>\
	  	 <a class="btn btn-success" onclick="addPoint(\'gold\',\''+currentId+'\');">+1</a></h3>\
	  	<h3><img src="img.php?width=30&height=30&image=/clicker/include/images/Coin.png" class="coin-img button hvr-grow"> <span id="coin-point">'+coin+'</span>\
	  	 <a class="btn btn-success" onclick="addPoint(\'coin\',\''+currentId+'\');">+1</a></h3>\
	  	</div>');
   }, 80);
}

$(function(){
	$('.thumbnail').click(function(){
	   var userName = $(this).find('.stdCap').html();
   	   var currentId = $(this).data('user');
	   var Selected = $(this).data('photo');
	   var coin = $(this).data('coin');
	   var gold = $(this).data('gold');
	   var crystal = $(this).data('crystal');

	   $('.random-mark').removeClass('random-mark');
	   $(this).addClass('random-mark');
	   $('.workspace-image').html('<img src="'+Selected+'" class="img-circle">');
	   $('.workspace-info').html('<div class="caption"><h1>'+userName+'</h1>\
	  	<h3><img src="img.php?width=30&height=30&image=/clicker/include/images/Crystal.png" class="crystal-img button hvr-grow"> <span id="crystal-point">'+crystal+'</span>\
	  	 <a class="btn btn-success" onclick="addPoint(\'crystal\',\''+currentId+'\');">+1</a></h3>\
	  	<h3><img src="img.php?width=30&height=30&image=/clicker/include/images/Gold.png" class="gold-img button hvr-grow"> <span id="gold-point">'+gold+'</span>\
	  	 <a class="btn btn-success" onclick="addPoint(\'gold\',\''+currentId+'\');">+1</a></h3>\
	  	<h3><img src="img.php?width=30&height=30&image=/clicker/include/images/Coin.png" class="coin-img button hvr-grow"> <span id="coin-point">'+coin+'</span>\
	  	 <a class="btn btn-success" onclick="addPoint(\'coin\',\''+currentId+'\');">+1</a></h3>\
	  	</div>');

	});
});

function addPoint(pt,u){
	var current = $('#'+pt+'-point');
	var point = current.text();
	current.html(++point);

	var newpoint = current.text();
	$.post('update.php?q=score', {'type':pt,'point':newpoint, 'user':u}, function(data){

		if(data.status=='success')
		{
			console.log(number);
			$('#'+number).attr('data-'+pt,newpoint);
		}
	}, "JSON")
}
$(function(){
	$('#addmedialink').submit(function(){
		$.post($(this).attr('action'), $(this).serialize(), function(data){
			console.log(data);
			$('.mediaList').append("<li class=file-list data-id="+data.id+"><a href=\""+data.file+"\" target=\"_blank\">\
				<a href=# onclick=\"share-url\" data-id="+data.id+"><i class=\"option-item fa fa-link\"></i></a>\
				<a href=# onclick=\"toggle\" data-id="+data.id+"><i class=\"option-item fa fa-eye-slash\" style=\"margin-left: 35px;\"></i></a>\
				<a href=# onclick=\"trash\" data-id="+data.id+"><i class=\"option-item fa fa-trash\" style=\"margin-left: 72px;\"></i></a>\
			    		<img src='"+data.image+"'></a>\
			    		<p>"+data.title+"</p></li>");
		},"json");
		return false;
	});

	$('#settingfrm').submit(function(){
		$.post($(this).attr('action'), $(this).serialize(), function(data){
			if(data.status=='success'){
				$('#configsave').html('<i class="fa fa-check"></i> บันทึก')
			}
		},"json");
		return false;
	});
	
})