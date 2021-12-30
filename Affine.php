<?php

/**
 * @param $scaleX X軸の拡大方向
 * @param $scaleY y軸の拡大方向
 * @param $matrix 3x3の同次座標
 */
function affineScale($scaleX, $scaleY, $matrix = [[1,0,0],[0,1,0],[0,0,1]]) {

    // matrixが3x3の行列であることをチェック
    if(count($matrix) != 3) throw new Exception();
    for($i=0;$i<3;$i++){
        if(count($matrix[$i]) != 3) throw new Exception();
    }

    $matrix2 = [[$scaleX,0,0],[0,$scaleY,0],[0,0,1]];

    return matrix_multi($matrix, $matrix2);
}

/**
 * @param $moveX X軸方向の移動
 * @param $moveY Y軸方向の移動
 * @param $matrix 3x3の同次座標
 */
function affineMove($moveX, $moveY, $matrix = [[1,0,0],[0,1,0],[0,0,1]]) {

    // matrixが3x3の行列であることをチェック
    if(count($matrix) != 3) throw new Exception();
    for($i=0;$i<3;$i++){
        if(count($matrix[$i]) != 3) throw new Exception();
    }

    $matrix2 = [[1,0,$moveX],[0,1,$moveY],[0,0,1]];

    return matrix_multi($matrix, $matrix2);
}

/**
 * @param $degree 原点を中心として左回りの度数
 * @param $matrix 3x3の同次座標
 */
function affineRotate($degree, $matrix = [[1,0,0],[0,1,0],[0,0,1]]) {

    // matrixが3x3の行列であることをチェック
    if(count($matrix) != 3) throw new Exception();
    for($i=0;$i<3;$i++){
        if(count($matrix[$i]) != 3) throw new Exception();
    }

    $rad = deg2rad($degree);
    $cos = round(cos($rad)*10000000000, 0) / 10000000000;
    $sin = round(sin($rad)*10000000000, 0) / 10000000000;
    $matrix2 = [[$cos,-$sin,0],[$sin,$cos,0],[0,0,1]];

    return matrix_multi($matrix2, $matrix);
}

/**
 * 
 */
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