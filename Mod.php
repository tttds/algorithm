<?php


/**
 * mod Pの世界で逆元を求める
 * フェルマーの小定理を使う
 * 
 *   a^(P-1) ≡ 1   (mod P) より
 *   a^(P-2) ≡ 1/a (mod P)
 * 
 * @param $value 逆元を求めたい値
 * @param $mod   素数
 */
function Inverse($value, $mod) {
    return intval(gmp_powm($value,$mod-2,$mod));
}

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

$x = "19";
$y = 19;
echo strmod($x, $y);
echo PHP_EOL;
echo $x % $y;

//-----------------------------
//
//-----------------------------
function strmod($b, $m){
    $len = strlen($b);
    $mod = 0;
    for($i=0;$i<$len-1;$i++){
        $mod = ($mod + $b[$i])*10 % $m;
    }
    $mod = ($mod+$b[$len-1]) % $m;
    return $mod;
}

//------------------------------
// 二項係数の元を求める
//------------------------------
// 引数
// $n 二項係数を求める上限（最大500000程度）
// $mod 余り
//------------------------------
// 戻り値
// 配列 [$factorial, $ifactorial]
// $factorial・・階乗
// $ifactorial・・階乗の逆元 
//------------------------------
function getBinomialInit_mod($n, $mod){
    $factorial = [1,1];
    $ifactorial = [1,1];
    $inv = [1,1];
    for($i=2;$i<=$n;$i++){
      $factorial[$i] = $factorial[$i-1] * $i % $mod;
      $inv[$i] = $mod - $inv[$mod%$i] * intdiv($mod, $i) % $mod;
      $ifactorial[$i] = $ifactorial[$i-1] * $inv[$i] % $mod;
    }
    return [$factorial, $ifactorial];
}

//------------------------------
// nCkを求める
//------------------------------
function getBiomial_mod($fact, $ifact, $n, $k, $mod){
  if ($n < $k) return 0;
  if ($n < 0 || $k < 0) return 0;
  return $fact[$n] * ($ifact[$k] * $ifact[$n - $k] % $mod) % $mod;
}
