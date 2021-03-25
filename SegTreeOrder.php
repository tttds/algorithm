<?php

//-----------------------------------------
// N番目に小さい値用のSegment Tree
// 使い方
// 「書き換える」となっている部分を問題に応じて書き換える
//-----------------------------------------
  $N=1;
  //----------------------------------------------------------
  // 300010は数列の長さ。間に合わないケースがあるのでその場合は書き換える
  while ($N < 300010) {
    $N *= 2;
  }
  $tree=array_fill(0, $N*2-1, 0);
  //↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
  //↓↓書き換える
  $sequence=[2,4,1,6,9,12,34];
  for($i=0;$i<count($sequence);$i++){
    update($sequence[$i], 1);
  }

  echo find(1).PHP_EOL; // 2
  echo find(2).PHP_EOL; // 4
  echo find(3).PHP_EOL; // 1
  echo find(4).PHP_EOL; // 6
  echo find(5).PHP_EOL; // 9
  echo find(6).PHP_EOL; // 12
  echo find(7).PHP_EOL; // 34

  //↑↑書き換える
  //↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

  // $i番目の値を$valueで更新する
  // $iは1から始まる
  function update($i, $value){
    global $tree, $N;
    $i = $N + $i - 2;
    $tree[$i] = $value;
    while ($i > 0) {
      $i = ($i - 1) >> 1;
      $x = $i << 1;
      $tree[$i] = $tree[$x+1]+$tree[$x+2];
    }
  }
  // $order番目に小さい値を取得する
  function find($order){
    global $N;
    [$isSuccess, $value] = sub_query($order, 0, 0, $N);
    if($isSuccess) return $value;
    else return false;
  }

  function sub_query($order, $k, $l, $r){
    global $tree, $N;
    //echo $order." ".$k." ".$l." ".$r.PHP_EOL;
    if($tree[$k] < $order){
      return [false, $order-$tree[$k]];
    }
    if($r-$l == 1){
      return [true, $k-$N+2];
    }
 
    $mid = ($l+$r) >> 1;
    $x=$k<<1;
    [$ret, $value] = sub_query($order, $x+1, $l, $mid);
    if($ret) return [$ret, $value];
    return sub_query($value, $x+2, $mid, $r);
  }
 
