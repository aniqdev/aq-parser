$(function ($) {

	$('body').on('click', '#aqs_slide_down_btn', function() {
		document.all.aqs_slide_down.classList.toggle('openedd')
	})

	var aqs_MO = new MutationObserver(function(mutations) {
	  if (document.all.aqs_slide_down){
	  	document.body.classList.add('aqs_slide_down_body');
	  	aqs_MO.disconnect();
	  }
	});
	aqs_MO.observe(document.body,{childList:true, subtree:true});

}(jQuery));