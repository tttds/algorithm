<?php

/**
 * 二次元累積和
 * @param $a 二次元配列
 * @param $h 二次元配列の高さ
 * @param $w 二次元配列の幅
 * @return 二次元累積和
 * 
 * 例）
 * $a = [[1,2,3],
 *       [2,3,4]]
 * 
 * $ret = [[0,0,0,0],
 *         [0,1,3,6],
 *         [0,3,8,15]];
 * 
 */
function ruiseki_wa2d(&$a,$h,$w){
    $ret[0] = array_fill(0, $w+1, 0);
    for($i=0;$i<$h;$i++){
        $sum = [0];
        $ret[$i+1][0] = 0;
        for($j=0;$j<$w;$j++){
            $sum[$j+1] = $sum[$j] + $a[$i][$j];
            $ret[$i+1][$j+1] = $sum[$j+1] + $ret[$i][$j+1];
        }
    }
    return $ret;
}