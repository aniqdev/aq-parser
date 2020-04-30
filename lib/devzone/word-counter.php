<?php


?>
<style>
textarea {
	 resize: vertical;
	 min-height: 400px;
}
.list-group-item{
	background-color: transparent;
	display: list-item;
}
pr{
	white-space: pre;
	font-family: monospace;
}
.col-resizer-wrapper{
    height: 100px;
    margin: 0 -6px -100px; /* костыль (-6px) */
    position: relative;
    top: 30px;
}
.col-resizer{
    width: 12px;
    height: 100px;
    background: #4d5162;
    cursor: pointer;
    position: absolute;
    left: calc(75% - 9px); /* костыль */
    z-index: 3;
    cursor: col-resize;
    border-radius: 3px;
}
.col-resizer::before{
	content: '';
    width: 2px;
    height: 50px;
    position: absolute;
    left: 3px;
    top: 25px;
    background: #000;
}
.col-resizer::after{
	content: '';
	width: 2px;
	height: 50px;
	position: absolute;
	right: 3px;
    top: 25px;
    background: #000;
}
</style>
<div class="container">
	<h2>Word counter</h2>
	<div class="row">
		<div id="col_resizer_wrapper" class="col-resizer-wrapper">
			<div id="col_resizer" draggable="true" class="col-resizer"></div>
		</div>
		<div class="col-sm-9" id="col_1">
			<p><i>text</i></p>
			<textarea class="form-control" id="textarea" cols="30" rows="10">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero possimus atque delectus eum quidem voluptate aliquid recusandae itaque corporis, repellendus optio nam blanditiis quibusdam, ea quos sed, autem voluptatem eveniet!</textarea>
		</div>
		<div class="col-sm-3" id="col_2">
			<p><i>word length</i></p>
			<input type="number" class="form-control" value="4" id="num_inp"><br>
			<button class="btn btn-default" id="count_btn">Count!</button><br><br>
			<ol class="list-unstyled" id="result_list1"></ol>
			<ol class="list-group" id="result_list2"></ol>
		</div>
	</div>
</div>
<script>
$('#count_btn').click(function() {
	$('#result_list2').html('loading...')
	var resuls_arr = {}
	var text = $('#textarea').val()
	var number = +$('#num_inp').val()
	var from = 0
	var to = number
	do {
		var fragment = text.substring(from, to)
		var count = text.split(fragment).length - 1
		console.log(count)
		if (count > 1) {
			resuls_arr[fragment] = count
		}
		from++
		to++
		if(to > 30000000) break
	}while (fragment.length >= number)
	console.log(resuls_arr)
	var res_html = ''
	const ordered = {};
	Object.keys(resuls_arr).sort().forEach(function(fragment) {
		// res_html += '<li>"'+fragment+'" : '+resuls_arr[fragment]+'</li>'
		res_html += '<li class="list-group-item"><span class="badge">'+resuls_arr[fragment]+'</span><pr>"'+fragment+'"</pr></li>'
	});
	// for(fragment in resuls_arr){
	// 	res_html += '<li>'+fragment+' : '+resuls_arr[fragment]+'</li>'
	// }
	// $('#result_list1').html(res_html)
	$('#result_list2').html(res_html)
})

!function(d) {
	
	var col_1 = get_element('col_1')
	var col_2 = get_element('col_2')
	function col_resize_callback(perc_x) {
		col_1.style.width = perc_x+'%'
		col_2.style.width = (100 - perc_x)+'%'
	}
	var col_resizer = get_element('col_resizer')
	var col_resizer_wrapper = get_element('col_resizer_wrapper')
	aqs_drag_and_drop(col_resizer, col_resizer_wrapper, col_resize_callback)

    var cork = d.createElement('div')
    function get_element(id) {
      return d.getElementById(id) || cork;
    }

    function between(int, low, high) {
   		return int < low ? low : int > high ? high : int
    }

	function aqs_drag_and_drop(inner, outer, callback) {
		let shift = {mouseX:0,mouseY:0}
		let limits = {top:0, left:0}

		function onMouseMove(event) {

		  	let left = event.clientX - shift.wrapperX - shift.mouseX
		  	left = between(left, limits.left, limits.right)
			inner.style.left = left + 'px'

		  	let top = event.clientY - shift.wrapperY - shift.mouseY
		  	top = between(top, limits.top, limits.bottom)
		  	inner.style.top = top + 'px'

		  	var perc_x = left*100/limits.right
		  	var perc_y = top*100/limits.bottom
		  	callback(perc_x, perc_y)
		}

		function setLimits(event, no_mouse) {
		  inner.rect = inner.getBoundingClientRect()
		  outer.rect = outer.getBoundingClientRect()

		  if(no_mouse !== 'no_mouse') shift.mouseX = event.clientX - inner.rect.left
		  else shift.mouseX = inner.rect.width / 2
		  if(no_mouse !== 'no_mouse') shift.mouseY = event.clientY - inner.rect.top
		  else shift.mouseY = inner.rect.height / 2

		  shift.wrapperX = outer.rect.left
		  shift.wrapperY = outer.rect.top

		  limits.right = outer.rect.width - inner.rect.width
		  limits.bottom = outer.rect.height - inner.rect.height
		}

		inner.addEventListener('mousedown', function(event) {
		  event.stopPropagation();
		  setLimits(event)
		  // передвигаем мяч при событии mousemove
		  document.addEventListener('mousemove', onMouseMove);
		})

		// отпустить мяч, удалить ненужные обработчики
		document.addEventListener('mouseup', function() {
			document.removeEventListener('mousemove', onMouseMove);
		})

		outer.addEventListener('mousedown', function(event) {
		    event.stopPropagation();
		    setLimits(event, 'no_mouse')
			onMouseMove(event)
		})

		inner.ondragstart = function() { return false } // если мяч картинка
	}

}(document)
</script>