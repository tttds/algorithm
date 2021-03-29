<?php

var_dump(factorize(10000));

//--------------------------------
// $n について素因数分解をする
// 戻り値：連想配列（キーは素数、値は個数）
// --例1--------------------------
// $n = 16
// $return = [2 => 4]
// --例2--------------------------
// $n = 24
// $return = [2 => 3, 3 => 1]
//--------------------------------
function factorize($n){
  $res = [];
  for($i=2; $i*$i<=$n; $i++){
    if($n % $i != 0) continue;
    $res[$i]=0;
    while($n % $i == 0){      
      $res[$i]++;
      $n /= $i;
    }
  }
  if($n != 1){
    $res[$n]=1;
  }
  return $res;
}

//echo implode(" ", createPrimeNumber(10000));

//--------------------------------
// $nまでの素数を調べる
// 戻り値：素数の配列
// --例1--------------------------
// $n = 16
// $return = [2,3,5,7,11,13]
// --例2--------------------------
// $n = 24
// $return = [2,3,5,7,11,13,17,18,23]
//--------------------------------
function createPrimeNumber($n){
  $sqrt = floor(sqrt($n));
  $lists = array_fill(2, $n-1, true);
  $prime = [];
  for ($i=2; $i<=$sqrt; $i++) {
    if (isset($lists[$i])) {
      for ($j=$i*2; $j<=$n; $j+=$i) {
        unset($lists[$j]);
      }
    }
  }
  $prime = array_keys($lists);
  return $prime;
}

$prime = createPrimeNumber(10000);
var_dump(factorizeUsePrime(10000, $prime));

//--------------------------------
// $n について素因数分解をする
// ※事前に素数一覧を作成し、$primeとして渡す
// 戻り値：連想配列（キーは素数、値は個数）
// --例1--------------------------
// $n = 16
// $return = [2 => 4]
// --例2--------------------------
// $n = 24
// $return = [2 => 3, 3 => 1]
//--------------------------------
function factorizeUsePrime($n, $prime){
  $prime_count = count($prime);
  $ret=[];
  $sqrt=floor(sqrt($n));
  for($i=0;$i<$prime_count;$i++){
    // 平方根を超えた場合は残った値が素数
    if($sqrt < $prime[$i]){
      $ret[$n]=1;
      return $ret;
    }
    // 割り切れる間続ける
    while($n % $prime[$i]==0){
      if(!isset($ret[$prime[$i]])){
        $ret[$prime[$i]]=0;
      }
      $ret[$prime[$i]]++;
      $n=intdiv($n, $prime[$i]);
      if($n==1){
        return $ret;
      }
    }
  }
}
