<?php
//-----------------------------------------
// Segment Tree
// 使い方
// 「書き換える」となっている部分を問題に応じて書き換える
//-----------------------------------------
//----------------------------------------------------------
// 300010は数列の長さ。間に合わないケースがあるのでその場合は書き換える

//------------------------
// Sum
//------------------------
$segSum = new SegTree(300010, function($x,$y){return $x + $y;}, 0);
for($i=1;$i<=6;$i=$i+3){
  $segSum->update($i, $i+2);
  $segSum->update($i+1, $i+1);
  $segSum->update($i+2, $i);
}

echo $segSum->query(1, 1).PHP_EOL; // 3
echo $segSum->query(1, 2).PHP_EOL; // 5
echo $segSum->query(1, 3).PHP_EOL; // 6
echo $segSum->query(3, 3).PHP_EOL; // 1
echo $segSum->query(1, 4).PHP_EOL; // 12
echo $segSum->query(4, 5).PHP_EOL; // 11
echo $segSum->query(1, 6).PHP_EOL; // 21
echo $segSum->query(6, 6).PHP_EOL; // 4

//------------------------
// Or
//------------------------
$segOr = new SegTree(300010, function($x,$y){return $x | $y;}, 0);
for($i=1;$i<=6;$i=$i+3){
  $segOr->update($i, $i+2);
  $segOr->update($i+1, $i+1);
  $segOr->update($i+2, $i);
}

echo $segOr->query(1, 1).PHP_EOL; // 3
echo $segOr->query(1, 2).PHP_EOL; // 3
echo $segOr->query(1, 3).PHP_EOL; // 3
echo $segOr->query(3, 3).PHP_EOL; // 1
echo $segOr->query(1, 4).PHP_EOL; // 7
echo $segOr->query(4, 5).PHP_EOL; // 7
echo $segOr->query(1, 6).PHP_EOL; // 7
echo $segOr->query(6, 6).PHP_EOL; // 4


//------------------------
// Min
//------------------------
$segMin = new SegTree(300010, function($x,$y){return min($x,$y);}, PHP_INT_MAX);
for($i=1;$i<=6;$i=$i+3){
  $segMin->update($i, $i+2);
  $segMin->update($i+1, $i+1);
  $segMin->update($i+2, $i);
}

echo $segMin->query(1, 1).PHP_EOL; // 3
echo $segMin->query(1, 2).PHP_EOL; // 2
echo $segMin->query(1, 3).PHP_EOL; // 1
echo $segMin->query(3, 3).PHP_EOL; // 1
echo $segMin->query(1, 4).PHP_EOL; // 1
echo $segMin->query(4, 5).PHP_EOL; // 5
echo $segMin->query(1, 6).PHP_EOL; // 1
echo $segMin->query(6, 6).PHP_EOL; // 4

//------------------------
// Max
//------------------------
$segMax = new SegTree(300010, function($x,$y){return max($x,$y);}, PHP_INT_MIN);
for($i=1;$i<=6;$i=$i+3){
    $segMax->update($i, $i+2);
    $segMax->update($i+1, $i+1);
    $segMax->update($i+2, $i);
}

echo $segMax->query(1, 1).PHP_EOL; // 3
echo $segMax->query(1, 2).PHP_EOL; // 3
echo $segMax->query(1, 3).PHP_EOL; // 3
echo $segMax->query(3, 3).PHP_EOL; // 1
echo $segMax->query(1, 4).PHP_EOL; // 6
echo $segMax->query(1, 5).PHP_EOL; // 6
echo $segMax->query(1, 6).PHP_EOL; // 6
echo $segMax->query(6, 6).PHP_EOL; // 4

//↑↑書き換える
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


class SegTree {

    private $N = 1;
    private $tree = null;
    private $op;
    private $e = 0;

    function op($x, $y){
        return call_user_func($this->op, $x, $y);
    }

    function __construct($N, $op, $e = 0) {
        while ($this->N < $N) {
            $this->N *= 2;
        }
        $this->op = $op;
        $this->e = $e;
        $this->tree=array_fill(0, $this->N*2-1, 0);
    }

    // $i番目の値を$valueで更新する
    // $iは1から始まる
    function update($i, $value){
        $i = $this->N + $i - 2;
        $this->tree[$i] = $value;
        while ($i > 0) {
            $i = ($i - 1) >> 1;
            $x = $i << 1;
            $this->tree[$i] = $this->op($this->tree[$x+1], $this->tree[$x+2]);
        }
    }
    // $start番目から$end番目までの和を取得する
    function query($start, $end){
        return $this->sub_query($start-1, $end, 0, 0, $this->N);
    }
  
    function sub_query($a, $b, $k, $l, $r){
        if($b <= $l || $r <= $a) return $this->e;
        if($a <= $l && $r <= $b) return $this->tree[$k];

        $mid = ($l+$r) >> 1;
        $x = $k<<1;
        $lv = $this->sub_query($a, $b, $x+1, $l, $mid);
        $rv = $this->sub_query($a, $b, $x+2, $mid, $r);
        return $this->op($lv, $rv);
    }
}
