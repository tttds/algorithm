<?php

/**
 * mod Pの世界での繰り返し二乗法
 * @param $b 基数
 * @param $e 指数
 * @param $m 素数
 */
function repeatSqrt($b, $e, $m){
  return gmp_powm($b,$e,$m);
}

/**
 * mod Pの世界での組み合わせ(nCa)
 * @param $n 全体の数
 * @param $a 選択する数
 * @param $m 素数
 */
function combination($n, $a, $m)
{
    $x=1; // bunbo
    $y=1; // bunshi
    for($i=0;$i<$a;$i++)
    {
        $x=$x*($n-$i)%$m;
        $y=$y*($i+1)%$m;
    }
    // フェルマーの小定理 
    // Y^(P-1)≡1 (mod P) より
    // Y^(P-2)≡1/Y (mod P)
    // つまり
    // $x / $y = $x * $y^($m-2) (mod P)
    $y=repeatSqrt($y, $m-2, $m);
    return ($x*$y)%$m;
}

/**
 * mod Pの世界での組み合わせ(nCa)
 * @param $n 全体の数
 * @param $a 選択する数
 * @param $m 素数
 * @param $factorial 処理済みの階乗
 */
function combination2($n, $a, $m, $factorial)
{
    $x=$factorial[$n]; // bunbo
    $y=$factorial[$a]*$factorial[$n-$a]%$m; // bunshi

    // フェルマーの小定理 
    // Y^(P-1)≡1 (mod P) より
    // Y^(P-2)≡1/Y (mod P)
    // つまり
    // $x / $y = $x * $y^($m-2) (mod P)
    $y=repeatSqrt($y, $m-2, $m);
    return ($x*$y)%$m;
}
function getFactorial($n, $mod){
  $factorial=[1];
  for($i=1;$i<=$n;$i++){
    $factorial[$i]=$factorial[$i-1]*$i%$mod;
  }
  return $factorial;
}
