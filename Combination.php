<?php

$array = [1, 2, 3, 4];
$m = 2;
$ans = getCombinationPattern($array, $m);
//var_dump($ans);
echo "--test1-----------".PHP_EOL;
for($i=0;$i<count($ans);$i++){
  echo implode(" ", $ans[$i]);
  echo PHP_EOL;
}

echo "--test2-----------".PHP_EOL;
$array = [1, 2, 3, 4];
$m = 3;
$ans = getCombinationPattern($array, $m);

for($i=0;$i<count($ans);$i++){
  echo implode(" ", $ans[$i]);
  echo PHP_EOL;
}


//-------------------------------------------------------------------------
// 組み合わせのパターンを作成する（nCm）
//-------------------------------------------------------------------------
// $array 全部の要素
// $m 全要素の中から選択をする数
//-------------------------------------------------------------------------
// 例1
// $array = [1, 2, 3, 4], $m = 2
// $ret = [[1, 2], [1, 3], [1, 4], [2, 3], [2, 4], [3, 4]]
//-------------------------------------------------------------------------
function getCombinationPattern($array, $m){

  $count = count($array);;
  $bit = 1<<$count;
  $ret=[];
  for($i=0;$i<$bit;$i++){
    if(gmp_popcount($i) == $m){
      $ret_elem = [];
      for($j=0;$j<$count;$j++){
        if(($i>>$j & 1) == 1){
          $ret_elem[] = $array[$j];
        }
      }
      $ret[] = $ret_elem;
    }
  }
  return $ret;

}
