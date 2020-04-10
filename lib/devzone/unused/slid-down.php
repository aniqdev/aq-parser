<div id="aqs_slide_down">
	<button id="aqs_slide_down_btn">*</button>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis minus explicabo aliquam culpa facere adipisci obcaecati ea, eaque, natus a qui iste excepturi consequuntur labore rerum, nemo quis libero et fugit voluptatum inventore hic ad aliquid non. Quod quibusdam repellendus minima et corporis, nostrum labore officia maiores necessitatibus ut, suscipit autem id aliquid distinctio voluptatibus quo consequuntur ad optio similique omnis vitae dolorum. Voluptas, tempora sapiente, nulla atque velit iure molestiae, aspernatur minus earum in et nesciunt possimus beatae tempore ducimus pariatur quae corrupti facere, dolorem est reprehenderit. Laborum reiciendis magnam vel esse praesentium minima id repellat modi voluptas quas maiores sunt explicabo saepe excepturi, sit qui amet vitae debitis! Totam necessitatibus aspernatur animi magnam sit tempora harum reiciendis incidunt odio, fuga, officia non quibusdam tenetur provident accusamus nobis magni velit beatae culpa consectetur suscipit est omnis. Eaque officia, porro sequi beatae vero aut soluta labore eveniet quaerat. Dolore quidem dolor commodi corrupti optio animi, pariatur. Molestias aspernatur, ratione accusamus? Maxime deserunt, quo eius quos nihil asperiores veritatis accusamus atque et error praesentium dolorum autem, ab! Sint odio soluta quas quasi maxime, beatae rerum modi. Esse, fugiat iste mollitia, soluta, facere dolore nostrum itaque at eius animi id commodi iure?</p>
</div>

<!-- <button id="test_btn">test</button>

<div id="test_wrapper">no</div> -->

<style>
#aqs_slide_down{
	overflow-y: auto;
	height: 100px;
	transition: height .5s; }
#aqs_slide_down.openedd{ height: 300px; }
</style>

<script>
// var aqs_s_wrapper = document.all.aqs_slide_down;
// var aqs_s_btn = document.all.aqs_slide_down_btn;
// if(aqs_s_wrapper && aqs_s_btn){
// 	aqs_s_btn.addEventListener('click', function() {
// 		aqs_s_wrapper.classList.toggle('openedd')
// 	})
// }
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

// $('#test_btn').on('click', function () {
// 	$('#test_wrapper').html('<div id="yes"><span id="span">yes</span></div>')
// })


</script>