(function() {
var cork = document.createElement('div');
function get_by_selector(selector) {
  return document.querySelector(selector) || cork;
}

var selector = '.product-slider.category-slider--content'
var target = document.querySelector('div[data-controllerUrl="/widgets/emotion/index/emotionId/474/controllerName/index"]')

var config = {
    attributes: true,
    childList: true,
    subtree: true
}; 
var haliluia = false;
var callback = function(mutationsList, observer) {

    for (var mutation in mutationsList) {
        if (!haliluia && document.querySelector('.product-slider.category-slider--content')) {
			// ********************************************
			
			var sliderWrapper = get_by_selector(selector);
			
			function slider_pos_calc() {
			  var viewportHeight = window.innerHeight;
			  var elemY = sliderWrapper.getBoundingClientRect().top;
			  if(window.csl_show) console.log('new',viewportHeight, elemY);
			  if (viewportHeight > elemY) {
			  	sliderWrapper.classList.add('lazy-loaded');
			  	window.removeEventListener('scroll', slider_pos_calc);
			  }
			}
			slider_pos_calc();
			window.addEventListener('scroll', slider_pos_calc);
            this.disconnect();
            haliluia = true;
			// ********************************************
        }
    }
};

if (document.querySelector('div[data-controllerUrl="/widgets/emotion/index/emotionId/474/controllerName/index"]')) {

	var observer = new MutationObserver(callback);
	observer.observe(target, config);
}
}());