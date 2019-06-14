(function() {

	HTMLElement.prototype.aqs_slider = function(opts){

		var $this = this,
			elems = this.children;
		// this содержит список слайдов
		// по нажатию на кнопки prev и next елементы мписка сдвигаются
		try {

			opts.prev.addEventListener("click", function() {
				$this.prepend(elems[elems.length-1]);
			});

			opts.next.addEventListener("click", function() {
				$this.appendChild(elems[0]);
			});

		} catch(e) {

		  console.error(e.stack);

		}
		return this;
	}

	document.body.appendChild(document.querySelector('.gkarusel'))

	var divs = document.querySelectorAll('.emotion--wrapper:nth-child(2) div[class*=emotion--sizer]');

	[].forEach.call(divs, function(div) {
	  div.remove()
	});

	var wrapper = document.querySelector('.emotion--wrapper.ptm.pbm')

	var prev_btn = document.createElement('div');
	prev_btn.className = 'aqs-controls prev-btn'
	prev_btn.innerHTML = '<';

	var next_btn = document.createElement('div');
	next_btn.className = 'aqs-controls next-btn'
	next_btn.innerHTML = '>';

	wrapper.appendChild(prev_btn)
	wrapper.appendChild(next_btn)

	var section = document.querySelector('.emotion--wrapper.ptm.pbm .emotion--container.emotion--column-5')

	section.aqs_slider({
		prev: prev_btn,
		next: next_btn,
	});

})();