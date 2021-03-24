<?php

$a=[[1, 2, 3], [4, 5, 6]];
$b=[[4, 5],[6, 7], [8, 9]];
$c=[[4],[6],[8]];

var_dump(matrix_multi($a,$b));
var_dump(matrix_multi($a,$c));


// 行列計算
// 行数と列数が一致すること
// 一致しない場合はFALSEを返す
function matrix_multi($a,$b){
    if(count($a[0])!=count($b)){
        return FALSE;
    }
    $ret = [];
    $r=count($a[0]);
    $c1=count($a);
    $c2=count($b[0]);
    $ret = [];
    for($i=0;$i<$c1;$i++){
        for($j=0;$j<$c2;$j++){
            $ret[$i][$j] = 0;
            for($k=0;$k<$r;$k++){
                $ret[$i][$j] += $a[$i][$k] * $b[$k][$j];
            }
        }
    }
    return $ret;
}
