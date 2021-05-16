<?php

//-----------------------------------------
// 区間最小用のLazy Segment Tree
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
  $lazy=array_fill(0, $N*2-1, 0);
  //↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
  //↓↓書き換える
  for($i=1;$i<=6;$i=$i+3){
    update($i, $i+2);
    update($i+1, $i+1);
    update($i+2, $i);
  }
  echo "---------------------".PHP_EOL;
  echo query(1, 1).PHP_EOL; // 3 (3)
  echo query(1, 2).PHP_EOL; // 2 (3, 2)
  echo query(1, 3).PHP_EOL; // 1 (3, 2, 1)
  echo query(3, 3).PHP_EOL; // 1 (1)
  echo query(1, 4).PHP_EOL; // 1 (3, 2, 1, 6)
  echo query(4, 5).PHP_EOL; // 5 (6, 5)
  echo query(1, 6).PHP_EOL; // 1 (3, 2, 1, 6, 5, 4)
  echo query(6, 6).PHP_EOL; // 4 (4)

  echo "---------------------".PHP_EOL;
  add(1, 3, 3);
  echo query(1, 1).PHP_EOL; // 6 (6)
  echo query(1, 2).PHP_EOL; // 5 (6, 5)
  echo query(1, 3).PHP_EOL; // 4 (6, 5, 4)
  echo query(3, 3).PHP_EOL; // 4 (4)
  echo query(1, 4).PHP_EOL; // 4 (6, 5, 4, 6)
  echo query(4, 5).PHP_EOL; // 5 (6, 5)
  echo query(1, 6).PHP_EOL; // 4 (6, 5, 4, 6, 5, 4)
  echo query(6, 6).PHP_EOL; // 4 (4)

  echo "---------------------".PHP_EOL;
  add(3, 5, 4);
  echo query(1, 1).PHP_EOL; // 6 (6)
  echo query(1, 2).PHP_EOL; // 5 (6, 5)
  echo query(1, 3).PHP_EOL; // 5 (6, 5, 8)
  echo query(3, 3).PHP_EOL; // 8 (8)
  echo query(1, 4).PHP_EOL; // 5 (6, 5, 8, 10)
  echo query(4, 5).PHP_EOL; // 9 (10, 9)
  echo query(1, 6).PHP_EOL; // 4 (6, 5, 8, 10, 9, 4)
  echo query(6, 6).PHP_EOL; // 4 (4)

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
      $tree[$i] = min($tree[$x + 1], $tree[$x+ 2]);
    }
  }
  // $start番目から$end番目までの最小値を取得する
  function query($start, $end){
    global $N;
    return sub_query($start-1, $end, 0, 0, $N);
  }

  function sub_query($a, $b, $k, $l, $r){
    global $tree;
    reflect($k,$l,$r);
    if($b <= $l || $r <= $a) return PHP_INT_MAX;
    if($a <= $l && $r <= $b) return $tree[$k];
 
    $mid = ($l+$r) >> 1;
    $x=$k<<1;
    $lv = sub_query($a, $b, $x+1, $l, $mid);
    $rv = sub_query($a, $b, $x+2, $mid, $r);
    return min($lv, $rv);
  }

  function add($start, $end, $x){
    global $N;
    return sub_add($start-1, $end, $x, 0, 0, $N);
  }

  function sub_add($a, $b, $v, $k, $l, $r){
    global $tree, $lazy;
    //reflect($k,$l,$r);
    if($b <= $l || $r <= $a) return;
    if($a <= $l && $r <= $b){
      $lazy[$k] += $v;
      reflect($k,$l,$r);
      return;
    }

    $mid = ($l+$r) >> 1;
    $x=$k<<1;
    sub_add($a, $b, $v, $x+1, $l, $mid);
    sub_add($a, $b, $v, $x+2, $mid, $r);
    $tree[$k] = min($tree[$x+1], $tree[$x+2]);
  }
 
  function reflect($k,$l,$r){
    global $tree, $lazy;
    if($lazy[$k] != 0){
      $tree[$k] += $lazy[$k];
      if($r-$l > 1){
        $x=$k<<1;
        $lazy[$x+1] += $lazy[$k];
        $lazy[$x+2] += $lazy[$k];
      }
      $lazy[$k]=0;
    }
  }