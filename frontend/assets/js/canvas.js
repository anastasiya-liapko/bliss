'use strict';

$(document).ready(function() {

    var drawingCanvas = document.getElementById('canvas');
    var location = window.location.href;
    var cur_url = location.split('/').pop().split('.').shift();

    var draw = function () {
        ctx.drawImage(document.getElementById('background__polygon-1'), 0, 0, 1440, 1728);

        if (cur_url == 'index' || cur_url == '') {
            ctx.drawImage(document.getElementById('background__phone'), 0, 0, 1344, 1315);
            ctx.fillStyle = "#dae0e6";
            ctx.beginPath();
            ctx.moveTo(1341, 0);
            ctx.lineTo(1121, 545);
            ctx.lineTo(400, 1252);
            ctx.lineTo(457, 1312);
            ctx.lineTo(1197, 596);
            ctx.lineTo(1437, 0);
            ctx.closePath();
            ctx.fill();
        }

        ctx.drawImage(document.getElementById('background__polygon-1'), -370, 2200, 1440, 1728);
        
        ctx.strokeStyle = "#000000";
        ctx.fillStyle = "#dae0e6";
        ctx.fillStyle = "#ebeff2";

        var lingrad = ctx.createLinearGradient(0, 230, 250, 20);
          lingrad.addColorStop(0.7, '#9f041b');
          lingrad.addColorStop(0.6, '#9f041b');
          lingrad.addColorStop(0.5, '#9f041b');
          lingrad.addColorStop(1, '#f5515f');

        ctx.fillStyle = lingrad;
        ctx.translate(841, 676);
        ctx.rotate((Math.PI / 180) * 47);
            if (cur_url == 'index' && $(window).width() >= 1200) {
                ctx.fillRect(205, 525, 100, 100);
            }
        
        ctx.fillStyle = "#ebeff2";
        ctx.fillRect(700, 712, 500, 500);

        ctx.fillStyle = "#dae0e6";
        ctx.fillRect(765, 623, 380, 90);

        ctx.fillStyle = "#dae0e6";
        ctx.rotate((Math.PI / 180) * 65);
        ctx.fillRect(1050, -783, 500, 90);

        ctx.fillStyle = "#ebeff2";
        ctx.rotate((Math.PI / 180) * 66);
        ctx.fillRect(100, -1525, 1000, 500);

            if (cur_url == 'index' && $(window).width() >= 1200) {
                ctx.fillStyle = lingrad;
                ctx.rotate((Math.PI / 180) * 48);
                ctx.fillRect(-1598, -2060, 80, 500);
            }
    }

    if(drawingCanvas && drawingCanvas.getContext) {
        var ctx = drawingCanvas.getContext('2d');

        draw();
    }

    $(window).resize(function () {
        var ctx = drawingCanvas.getContext('2d');

        ctx.resetTransform();
        ctx.clearRect(0, 0, drawingCanvas.width, drawingCanvas.height);

        draw();
    })

});

// $('body').click(function(e) {
//   var offset = $(this).offset();
//   var relativeX = (e.pageX - offset.left);
//   var relativeY = (e.pageY - offset.top);
 
//   alert("X: " + relativeX + "  Y: " + relativeY);
// });
