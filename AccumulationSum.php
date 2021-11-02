<?php
$a = [];
$a[] = [1, 1, 1, 1];
$a[] = [1, 4, 3, 1];
$a[] = [1, 1, 1, 2];

$ans = dAccumulationSum($a, 3, 4);

for($i=0;$i<=3;$i++){
    echo implode(" ", $ans[$i]);
    echo PHP_EOL;
}

function dAccumulationSum($a,$h,$w){
    $ret = array_fill(0, $h+1, array_fill(0, $w+1, 0));
    for($i=1;$i<=$h;$i++){
        for($j=1;$j<=$w;$j++){
            $ret[$i][$j]=$a[$i-1][$j-1] + $ret[$i-1][$j] + $ret[$i][$j-1] - $ret[$i-1][$j-1];
        }
    }
    return $ret;
}