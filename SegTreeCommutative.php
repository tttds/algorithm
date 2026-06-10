<?php

/**
 * 左右が可換でない場合はこのSegTreeを使うこと
 * つまり、op($a, $b) ≠ op($b, $a)の場合
 */
class SegTreeCommutative {

    private $N = 1;
    private $tree = null;
    private $e = 0;
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
            $x>>=1;
            $tree[$x] = $this->op(
                $tree[$x<<1],
                $tree[$x<<1|1]
            );
        }
    }
    // $l番目から$r-1番目までの和を取得する
    // $lは0から始まる
    function query($l, $r){
        $l+=$this->N;
        $r+=$this->N;
        $left = $this->e;
        $right = $this->e;
        $tree =& $this->tree;
        while($l < $r){
            if($l & 1){
                $left = $this->op($left, $tree[$l]);
                ++$l;
            }
            if($r & 1){
                --$r;
                $right = $this->op($tree[$r], $right);
            }
            $l>>=1;
            $r>>=1;
        }
        return $this->op($left, $right);
    }
}
