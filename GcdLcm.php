<?php

echo gcdMulti([48, 108, 30]);

// 最小公約数（２つ）（ユークリッドの互除法）
function gcd($a, $b){
  if($b===0){
    return $a;
  }
  return gcd($b, $a%$b);
}

// 最小公約数（３つ）（ユークリッドの互除法）
function gcdMulti($arr){
  $cnt = count($arr);
  $gcd = $arr[0];
  for($i=0;$i<$cnt;$i++){
    $gcd = gcd($gcd, $arr[$i]);
  }
  return $gcd;
}

// 最大公倍数（２つ）
function lcm($a, $b){
  return $a*$b/gcd($a, $b);
}

// 最小公倍数（３つ以上）
function lcmMulti($arr){
  $cnt = count($arr);
  $lcm = $arr[0];
  for($i=1;$i<$cnt;$i++){
    $lcm = lcm($lcm, $arr[$i]);
  }
  return $lcm;
}
