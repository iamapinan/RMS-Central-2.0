$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})


var number;
var timeout;
function repeat() {
	random_once();
	setTimeout(function(){
		clearTimeout(timeout);
	}, 1500);
	
};

function random_once(){
   number = Math.floor((Math.random() * 2) + 1);
   var userSelected;
   var userName;
   playBGM()
   timeout = setTimeout(function(){
   	  repeat();

	  $('.random-mark').removeClass('random-mark'); //Clear mask.
	  $('#'+number).addClass('random-mark');
	  userSelected = $('#'+number).find('img').attr('src');
	  userName = $('#'+number+' .stdCap').html();
	  var Selected = userSelected.replace('width=66&height=66','width=250&height=250');
	  $('.workspace').addClass('text-center').html('<img src="'+Selected+'" class="img-rounded"><div class="caption">'+userName+'</div>');
   }, 80);
}

function playBGM(){
	var bgmSound = $("#BGMPlayer")[0];
    bgmSound.play();
}