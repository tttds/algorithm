<?php

$a = [12, 6, 25, 38, 29, 46];
$a = press($a);
echo $a[0] == 2 ? "OK" : "NG";
echo PHP_EOL;
echo $a[1] == 1 ? "OK" : "NG";
echo PHP_EOL;
echo $a[2] == 3 ? "OK" : "NG";
echo PHP_EOL;
echo $a[3] == 5 ? "OK" : "NG";
echo PHP_EOL;
echo $a[4] == 4 ? "OK" : "NG";
echo PHP_EOL;
echo $a[5] == 6 ? "OK" : "NG";
echo PHP_EOL;

$a = [6, 6, 23, 23, 4, 4];
$a = press($a);
echo $a[0] == 2 ? "OK" : "NG";
echo PHP_EOL;
echo $a[1] == 2 ? "OK" : "NG";
echo PHP_EOL;
echo $a[2] == 3 ? "OK" : "NG";
echo PHP_EOL;
echo $a[3] == 3 ? "OK" : "NG";
echo PHP_EOL;
echo $a[4] == 1 ? "OK" : "NG";
echo PHP_EOL;
echo $a[5] == 1 ? "OK" : "NG";
echo PHP_EOL;


// 圧縮
//
function press(&$a){
    // キーを保持したまま昇順にソート
    asort($a);
    $an = [];
    $first = true;
    $x = 1;
    foreach($a as $k => $v){
        if($first) {
            $an[$k] = $x;
            $before = $v;
            $first = false;
            continue;
        }
        if($v != $before){
            $x++;
        }
        $an[$k] = $x;
        $before = $v;
    }
    // キーで昇順にソート
    ksort($an);
    return $an;
}
