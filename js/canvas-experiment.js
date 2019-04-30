(function() {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function(callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function() { callback(currTime + timeToCall); },
                timeToCall);
                lastTime = currTime + timeToCall;
            return id;
        };

        if (!window.cancelAnimationFrame)
            window.cancelAnimationFrame = function(id) {
                clearTimeout(id);
            };
}());

(function() {
    var canvas,
        contentWidth,
        contentHeight,
        ctx,
        points = [],
        target,
        // rgb1 = '0, 213, 139',
        // rgb2 = '16, 180, 220';
        rgb1 = '182, 85, 255',
        rgb2 = '133, 16, 220';

    function initVisualAnimation() {
        canvas = document.getElementById("animation-visual-canvas-3");

        resize();

        ctx = canvas.getContext('2d');

        target = {
            x: contentWidth / 2,
            y: contentHeight / 2
        };

        // create points
        for (var x = 0; x < contentWidth; x = x + contentWidth / 30) {
            for (var y = 0; y < contentHeight; y = y + contentHeight / 30) {
                var px = x + Math.random() * contentWidth / 30;
                var py = y + Math.random() * contentHeight / 30;
                points.push({
                    x: px,
                    originX: px,
                    y: py,
                    originY: py
                });
            }
        }

        // for each point find the 5 closest points
        for (var i = 0; i < points.length; i++) {
            var closest = [];
            var p1 = points[i];
            for (var j = 0; j < points.length; j++) {
                var p2 = points[j];
                if (p1 != p2) {
                    var placed = false;
                    for (var k = 0; k < 5; k++) {
                        if (!placed) {
                            if (closest[k] == undefined) {
                                closest[k] = p2;
                                placed = true;
                            }
                        }
                    }

                    for (var k = 0; k < 5; k++) {
                        if (!placed) {
                            if (getDistance(p1, p2) < getDistance(p1, closest[k])) {
                                closest[k] = p2;
                                placed = true;
                            }
                        }
                    }
                }
            }
            p1.closest = closest;
        }

        // assign a circle to each point
        for (var i in points) {
            points[i].circle = new Circle(points[i], 2 + Math.random() * 2, 'rgba('+rgb1+', 0.3)');
        }

        addListeners();

        animate();
        for (var i in points) {
            shiftPoint(points[i]);
        }
    }

    function addListeners() {
        if( !('ontouchstart' in window)) {
            canvas.addEventListener('mousemove', mouseMove);
        }

        window.addEventListener('resize', resize);
    }

    function mouseMove(e) {
        var posx = posy = 0;

        if (e.pageX || e.pageY) {
            posx = e.pageX;
            posy = e.pageY;
        } else if (e.clientX || e.clientY)    {
            posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
            posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
        }

        target.x = posx - getElementPosition(canvas).left;
        target.y = posy - getElementPosition(canvas).top;
    }

    function getElementPosition(elem) {
        var w = elem.offsetWidth,
            h = elem.offsetHeight,
            l = 0,
            t = 0;

        while (elem) {
            l += elem.offsetLeft;
            t += elem.offsetTop;
            elem = elem.offsetParent;
        }

        return {
            left: l,
            top: t,
            width: w,
            height: h
        };
    }

    function resize() {
        // parent node size
        contentWidth = canvas.parentNode.offsetWidth;
        contentHeight = canvas.parentNode.offsetHeight;

        // set canvas size equal size of parent node
        canvas.width = contentWidth;
        canvas.height = contentHeight;
    }

    function getDistance(p1, p2) {
        return Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2);
    }

    function getDistanceAbs(p1, p2) {
        return Math.abs(Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2));
    }

    function Circle(pos, rad, color) {
        var _this = this;

        (function() {
            _this.pos = pos || null;
            _this.radius = rad || null;
            _this.color = color || null;
        })();

        this.draw = function() {
            if (!_this.active) return;
            ctx.beginPath();
            ctx.arc(_this.pos.x, _this.pos.y, _this.radius, 0, 2 * Math.PI, false);
            ctx.fillStyle = 'rgba('+rgb2+', ' + _this.active + ')';
            ctx.fill();
        };
    }

    function animate() {
        ctx.clearRect(0, 0, contentWidth, contentHeight);

        for (var i in points) {
            // detect points in range
            if (getDistanceAbs(target, points[i]) < 4000) {
                points[i].active = 0.3;
                points[i].circle.active = 0.6;
            } else if (getDistanceAbs(target, points[i]) < 20000) {
                points[i].active = 0.1;
                points[i].circle.active = 0.3;
            } else if (getDistanceAbs(target, points[i]) < 40000) {
                points[i].active = 0.02;
                points[i].circle.active = 0.1;
            } else {
                points[i].active = 0;
                points[i].circle.active = 0;
            }

            drawLines(points[i]);
            points[i].circle.draw();
        }

        requestAnimationFrame(animate);
    }

    function drawLines(p) {
        if (!p.active) return;

        for (var i in p.closest) {
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.closest[i].x, p.closest[i].y);
            ctx.strokeStyle = 'rgba('+rgb1+', ' + p.active + ')';
            ctx.stroke();
        }
    }

    function shiftPoint(p) {
        TweenLite.to(
            p,
            1 + 1 * Math.random(),
            {
                x: p.originX - 50 + Math.random() * 100,
                y: p.originY - 50 + Math.random() * 100,
                ease:Circ.easeInOut,
                onComplete: function() {
                    shiftPoint(p);
                }
            }
        );
    }
// window.addEventListener('load', function () {
// 	var el = document.querySelector('.effect-1');
// 	el.setAttribute('data-effect', 'false');
// 	var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
// 	function notVisible() {
// 		return 'ontouchstart' in window || navigator.maxTouchPoints || isSafari;
// 	};

// 	window.addEventListener("scroll", function() {
// 		var scroll = window.pageYOffset || document.documentElement.scrollTop;
// 		var elTop = el.getBoundingClientRect().top + scroll - document.documentElement.clientHeight;
// 		var elBottom = el.getBoundingClientRect().bottom + scroll;

// 		if((el.getAttribute('data-effect') == 'false') && (scroll > elTop) && (scroll < elBottom)){
// 			el.setAttribute('data-effect', 'true');
// 			if( !notVisible() ){
// 				initVisualAnimation();
// 			}
// 		}
// 	});
// });

    initVisualAnimation();
    })();