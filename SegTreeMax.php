<?php

//-----------------------------------------
// 区間最大用のLazy Segment Tree
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
  for($i=1;$i<=6;$i=$i+3){
    update($i, $i+2);
    update($i+1, $i+1);
    update($i+2, $i);
  }

  echo query(1, 1).PHP_EOL; // 3
  echo query(1, 2).PHP_EOL; // 3
  echo query(1, 3).PHP_EOL; // 3
  echo query(3, 3).PHP_EOL; // 1
  echo query(1, 4).PHP_EOL; // 6
  echo query(1, 5).PHP_EOL; // 6
  echo query(1, 6).PHP_EOL; // 6
  echo query(6, 6).PHP_EOL; // 4

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
      $tree[$i] = max($tree[$x + 1], $tree[$x+ 2]);
    }
  }

  // $start番目から$end番目までの最大値を取得する
  function query($start, $end){
    global $tree, $N;
    $l = $start + $N - 1;
    $r = $end + $N;
    $ret = 0;
    while($l < $r){
      if($r&1){
        $r--;
        $ret = max($ret, $tree[$r-1]);
      }
      if($l&1){
        $ret = max($ret, $tree[$l-1]);
        $l++;
      }
      $l >>= 1;
      $r >>= 1;
    }
    return $ret;
  }
