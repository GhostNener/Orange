$(function() {
    window.requestAnimFrame = (function() {
        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
        function(callback) {
            window.setTimeout(callback, 1000 / 60)
        }
    })();
    var totalDeg = 360 * 3 + 0;
    var steps = [];
    var lostDeg = [15];
    var prize;
    var count = parseInt($('input[pcount]').val());
    var now = 0;
    var a = 0.01;
    var outter, inner, timer, running = false;
    function countSteps() {
        var t = Math.sqrt(2 * totalDeg / a);
        var v = a * t;
        for (var i = 0; i < t; i++) {
            steps.push((2 * v * i - a * i * i) / 2)
        }
        steps.push(totalDeg)
    }
    function step() {
        outter.style.webkitTransform = 'rotate(' + steps[now++] + 'deg)';
        outter.style.MozTransform = 'rotate(' + steps[now++] + 'deg)';
        if (now < steps.length) {
            requestAnimFrame(step)
        } else {
            running = false;
            setTimeout(function() {
                if (prize != null) {
                    $("#prizetype").text(prize.name);
                    $('#pcontent').text(prize.content);
                    $('#code').text(prize.code);
                    $("#prizeModal").modal('show');
                }
            },
            200)
        }
    }
    function start(deg) {
        deg = deg || lostDeg[parseInt(lostDeg.length * Math.random())];
        running = true;
        clearInterval(timer);
        totalDeg = 360 * 5 + deg;
        steps = [];
        now = 0;
        countSteps();
        requestAnimFrame(step)
    }
    window.start = start;
    outter = document.getElementById('outer');
    inner = document.getElementById('inner');
    i = 10;
    $("#inner").click(function() {
        if (running) {return false;}
        if (count >0||prize != null) {
            alert("亲，你已经参加过本次活动了喔！下次再来吧~");
            return false;
        }
        $.ajax({
            type:'post',
            url: $('input[pcount]').attr('act'),
            dataType: "json",
            beforeSend: function() {
                running = true;
                timer = setInterval(function() {
                    i += 5;
                    outter.style.webkitTransform = 'rotate(' + i + 'deg)';
                    outter.style.MozTransform = 'rotate(' + i + 'deg)'
                },
                1)
            },
            success: function(data) {
                if(data.status==0){
                    prize = null;
                    count++;
                    alert(data.msg);
                    clearInterval(timer);
                    start(lostDeg[1]);
                    return false;
                }else{
                    prize=data.res;
                    start(prize.deg);
                }
                running = false;
                count++
            },
            error: function() {
                prize = null;
                start();
                running = false;
                count++
            },
            timeout: 4000
        })
    })
});