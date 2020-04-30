<style>
#square{
	margin: 25px auto;
	width: 400px;
	height: 400px;
	background: #555;
	position: relative;
}
#circle{
	width: 45px;
	height: 25px;
	/* border-radius: 15px; */
	background: #999;
	cursor: pointer;
	position: absolute;
	top: 0;
	left: 0;
	z-index: 10;
}
#break{
	width: 60px;
	height: 30px;
	position: absolute;
	right: 40px;
	bottom: 30px;
	background: darkblue;
}
.inp,.lab{
	position: fixed;
}
#left_scrollbar{
	outline: 1px solid aqua;
    width: 18px;
    height: 100%;
    position: absolute;
    top: 0;
    left: -32px;
}
#left_scroll{
	width: 18px;
    height: 25px;
    background: coral;
    cursor: pointer;
    position: absolute;
}
#bottom_scrollbar{
	outline: 1px solid aqua;
    width: 200%;
    height: 18px;
    position: absolute;
    left: 0;
    bottom: -32px;
}
#bottom_scroll{
	width: 45px;
    height: 18px;
    background: coral;
    cursor: pointer;
    position: absolute;
}
.col-resizer-wrapper{
	/* outline: 1px solid aqua; */
    /* width: 90%; */
    margin: auto;
    height: 45px;
    position: relative;
    top: 60px;
}
.col-resizer{
    width: 12px;
    height: 40px;
    background: #333642;
    cursor: pointer;
    position: absolute;
    left: calc(50% - 6px);
    z-index: 3;
    cursor: col-resize;
    /* transform: translateX(-50%) */
}
.col-resizer::before{
	content: '';
    width: 2px;
    height: 35px;
    position: absolute;
    left: 3px;
    top: 3px;
    background: #000;
}
.col-resizer::after{
	content: '';
	width: 2px;
	height: 35px;
	position: absolute;
	right: 3px;
    top: 3px;
    background: #000;
}
</style>

<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<div id="square">
				<div id="circle" draggable="true"></div>
				<!-- <div id="break"></div> -->
				<div id="left_scrollbar">
					<div id="left_scroll" draggable="true"></div>
				</div>
				<div id="bottom_scrollbar">
					<div id="bottom_scroll" draggable="true"></div>
				</div>
			</div>	
		</div>
		<div class="col-sm-6">
			<lable class="lab">1. style</lable><br>
			<input id="input1" class="inp" type="text"><br><br>
			<lable class="lab">event.offsetX+'px : '+event.offsetY+'px'</lable><br>
			<input id="input2" class="inp" type="text"><br><br>
			<lable class="lab">3. event.clientX+'px : '+event.clientY+'px'</lable><br>
			<input id="input3" class="inp" type="text"><br><br>
			<lable class="lab">3. event.pageX+'px : '+event.pageY+'px'</lable><br>
			<input id="input4" class="inp" type="text"><br><br>
		</div>
	</div>
	
	<br><br><br>
	<div class="row">
		<div id="col_resizer_wrapper" class="col-resizer-wrapper">
			<div id="col_resizer" draggable="true" class="col-resizer"></div>
		</div>
		<div class="col-xs-6" id="col_1">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis blanditiis ipsam, rerum labore asperiores maiores itaque voluptate alias Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde similique est a, vel perspiciatis deserunt obcaecati eos accusamus doloribus eveniet fugit consequuntur dignissimos quam ex, explicabo eaque reiciendis in ipsa.dicta veniam laudantium perspiciatis quos, accusamus, ex doloremque sapiente dolore obcaecati, quia.</p>
		</div>
		<div class="col-xs-6" id="col_2">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis blanditiis ipsam, rerum labore asperiores maiores itaque voluptate alias dicta veniam laudantium perspiciatis quos, accusamus, Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias totam veniam placeat, distinctio illum nobis nisi omnis reprehenderit eum tempore consequuntur voluptates natus fugit, quo, iusto consectetur. Labore, sit ipsam.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus vero tempore quibusdam neque possimus, placeat ullam, impedit expedita quis adipisci magni, veniam reprehenderit ipsam, a culpa. Enim dolores tempora reiciendis? ex doloremque sapiente dolore obcaecati, quia.</p>
		</div>
	</div>
</div>

<script>
!function(w, d) {
	
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

	var left_scroll = get_element('left_scroll')
	var left_scrollbar = get_element('left_scrollbar')

	var bottom_scroll = get_element('bottom_scroll')
	var bottom_scrollbar = get_element('bottom_scrollbar')

	var circle = get_element('circle')
	var square = get_element('square')

	var rangeY = square.clientHeight - circle.clientHeight
	var left_scrollbar_callback = function(perc_x, perc_y) {
		circle.style.top = perc_y*rangeY/100 + 'px'
	}
	aqs_drag_and_drop(left_scroll, left_scrollbar, left_scrollbar_callback)

	var srangeX = square.clientWidth - circle.clientWidth
	var bottom_scrollbar_callback = function(perc_x, perc_y) {
		circle.style.left = perc_x*srangeX/100 + 'px'
		// col_resize(perc_x)
	}
	aqs_drag_and_drop(bottom_scroll, bottom_scrollbar, bottom_scrollbar_callback)

	var rangeY = left_scrollbar.clientHeight - left_scroll.clientHeight
	var rangeX = bottom_scrollbar.clientWidth - bottom_scroll.clientWidth
	var square_callback = function(perc_x, perc_y) {
		bottom_scroll.style.left = perc_x*rangeX/100 + 'px'
		left_scroll.style.top = perc_y*rangeY/100 + 'px'
	}
	aqs_drag_and_drop(circle, square, square_callback)
	
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

		    input1.value = inner.style.cssText
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

	var input1 = get_element('input1')
	var input2 = get_element('input2')
	var input3 = get_element('input3')



    square.rect = square.getBoundingClientRect()
	shift_wrapperX = square.rect.left
	shift_wrapperY = square.rect.top
	square.addEventListener('mousemove', function(event) {
		// input2.value = event.offsetX+' px : '+event.offsetY+' px'
		var perc_x =  (event.clientX - square.rect.left) * 100 / square.rect.width
		var perc_y =  (event.clientY - square.rect.top) * 100 / square.rect.height
		// input3.value =  perc_x + ' px : ' + perc_y + ' px'
		var r = perc_x + 150
		var g = perc_y + 150
		var b = (perc_x + perc_y) / 2 + 150
		circle.style.background = 'rgb('+r+', '+g+', '+b+')'
		// input3.value = event.clientX+' px : '+event.clientY+' px'
		// input4.value = event.pageX+' px : '+event.pageY+' px'
	})

}(window, document)
</script>