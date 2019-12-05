<style>
.ball-wrapper{
  position: relative;
}
#ball{
  width: 25px;
  height: 25px;
  border-radius: 15px;
  background: #999;
  cursor: pointer;
  /* transform: translate(-50%, -50%); */
  position: absolute;
  top: 0;
  left: 0;
}
</style>

<div class="container ball-wrapper">
<img src="https://js.cx/clipart/ball.svg" width="40" height="40" id="ball">
</div>

<script>
var ball = document.getElementById('ball');
let shiftX,shiftY

// переносит мяч на координаты (pageX, pageY),
// дополнительно учитывая изначальный сдвиг относительно указателя мыши
// function moveAt(pageX, pageY) {
//   ball.style.left = pageX - shiftX + 'px';
//   ball.style.top = pageY - shiftY + 'px';
// }

function onMouseMove(event) {
  // moveAt(event.pageX, event.pageY);
  ball.style.left = event.pageX - shiftX + 'px';
  ball.style.top = event.pageY - shiftY + 'px';
}

ball.onmousedown = function(event) {

  shiftX = event.clientX - ball.getBoundingClientRect().left;
  shiftY = event.clientY - ball.getBoundingClientRect().top;

  ball.style.position = 'absolute';
  ball.style.zIndex = 1000;
  document.body.append(ball);

  // onMouseMove(event)

  // передвигаем мяч при событии mousemove
  document.addEventListener('mousemove', onMouseMove);

  // отпустить мяч, удалить ненужные обработчики
  ball.onmouseup = function() {
    document.removeEventListener('mousemove', onMouseMove);
    ball.onmouseup = null;
  };

};

ball.ondragstart = function() {
  return false;
};


</script>