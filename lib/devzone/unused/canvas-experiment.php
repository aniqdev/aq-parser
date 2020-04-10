
<style>
html,body{
    height: 100%;
}
.animation-wrapper-3{
    width: 70%;
    height: 70%;
    /* overflow: hidden; */
    margin: 200px auto;
    background-image: url('images/canvas-experiment-2.jpg');
    background-image: url('images/space-3.jpg');
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
}
.animation-wrapper-3 canvas {
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 1;
    z-index: 1;
}
</style>

<div class="animation-wrapper-3">
    <canvas id="animation-visual-canvas-3"></canvas>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.2/TweenLite.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.2/easing/EasePack.min.js"></script>
<script type="text/javascript" src="js/canvas-experiment.js?t=<?= filemtime ('js/canvas-experiment.js'); ?>"></script>
