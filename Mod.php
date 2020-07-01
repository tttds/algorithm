<?php

/**
 * mod Pの世界での繰り返し二乗法
 * @param $b 基数
 * @param $p 指数
 * @param $m 素数
 */
function repeatSqrt($b, $p, $m){
  if($p==1)
  {
    return $b;
  }
  else if($p%2 == 0)
  {
    $t = repeatSqrt($b, floor($p/2), $m);
    return ($t * $t) % $m;
  }
  else
  {
    return ($b * repeatSqrt($b, $p-1, $m)) % $m;
  }
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
