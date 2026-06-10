<?php

//-------- Test ---------
// Sum
$seg = new SegTree(5, function($x,$y){return $x+$y;}, 0);
$seg->update(0, 1);
$seg->update(1, 2);
check($seg->query(0, 2), 3);
check($seg->query(0, 6), 3);
$seg->update(4, 5);
check($seg->query(1, 6), 7);
// Max
$seg = new SegTree(5, function($x,$y){return max($x, $y);}, PHP_INT_MIN);
$seg->update(0, 1);
$seg->update(1, 2);
check($seg->query(0, 2), 2);
check($seg->query(0, 6), 2);
$seg->update(4, 5);
check($seg->query(1, 6), 5);
// Min
$seg = new SegTree(5, function($x,$y){return min($x, $y);}, PHP_INT_MAX);
$seg->update(0, 1);
$seg->update(1, 2);
check($seg->query(0, 2), 1);
check($seg->query(0, 6), 1);
$seg->update(4, -10);
check($seg->query(1, 6), -10);
// Or
$seg = new SegTree(5, function($x,$y){return $x | $y;}, 0);
$seg->update(0, 1);
$seg->update(1, 2);
check($seg->query(0, 2), 3);
check($seg->query(1, 6), 2);
$seg->update(4, 10);
check($seg->query(0, 6), 11);

function check($a, $b, $isSame = true){
    global $count;
    if(!isset($count)) $count = 0;
    else $count++;
    echo $count." ";
    if($a === $b && $isSame) echo "OK";
    else if($a !== $b && !$isSame) echo "OK";
    else echo "NG ". $a." ".$b;
    echo PHP_EOL;
}
//-------- Test ---------

class SegTree {

    private $N = 1;
    private $tree = null;
    private $e = 0;
    private $hierarchy = 0;
    private $op = null;

    function op($x, $y){
        return call_user_func($this->op, $x, $y);
    }

    function __construct($N, $op, $e = 0) {
        $hierarchy = 0;
        while ($this->N < $N) {
            $this->N *= 2;
            ++$hierarchy;
        }
        $this->tree = array_fill(0, $this->N*2, $e);
        $this->e = $e;
        $this->op = $op;
        $this->hierarchy = $hierarchy;
    }

    // $x番目の値を$valueで更新する
    // $xは0から始まる
    function update($x, $value){
        $x += $this->N;
        $tree =& $this->tree;
        $tree[$x] = $value;
        while($x){
            $xnext = $x>>1;
            $tree[$xnext] = $this->op($tree[$x], $tree[$x^1]);
            $x>>=1;
        }
    }
    // $l番目から$r-1番目までの和を取得する
    // $lは0から始まる
    function query($l, $r){
        $l+=$this->N;
        $r+=$this->N;
        $ans = $this->e;
        $tree =& $this->tree;
        while($l < $r){
            if($l & 1){
                $ans = $this->op($ans, $tree[$l]);
                ++$l;
            }
            if($r & 1){
                $ans = $this->op($ans, $tree[$r-1]);
            }
            $l>>=1;
            $r>>=1;
        }
        return $ans;
    }
}
