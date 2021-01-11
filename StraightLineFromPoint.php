<?php

    echo d3point(2,2,6,4,4,5);   // 1.788854382
    echo PHP_EOL;
    echo d3point(100,100,-100,100,0,0); // 50.000000

    // (x3,y3)から(x1,y1)と(x2,y2)を通る直線への距離
    function d3point($x1,$y1,$x2,$y2,$x3,$y3){
        // (x1,y1)と(x2,y2)を通る直線の方程式
        // ax + by + c = 0
        // のa,b,cを求める
        $a = $y1-$y2;
        $b = $x2-$x1;
        $c = ($y2-$y1)*$x1-($x2-$x1)*$y1;

        // (x3,y3)とax + by + c = 0の距離
        // d = | ax + by + c | / √ (a^2 + b^2)
        $d = abs($a*$x3+$b*$y3+$c) / sqrt($a*$a + $b*$b);
        return $d;
    }
