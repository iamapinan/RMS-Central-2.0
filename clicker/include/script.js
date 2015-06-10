$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})


var number;
var timeout;
function repeat() {
	random_once();
	setTimeout(function(){
		clearTimeout(timeout);
	}, 1000);
	
};

function random_once(){
   number = Math.floor((Math.random() * 10) + 1);
   timeout = setTimeout(function(){
   	  repeat()
	  $('.random-mark').removeClass('random-mark'); //Clear mask.
	  $('#'+number).addClass('random-mark');   	
   }, 100);
}