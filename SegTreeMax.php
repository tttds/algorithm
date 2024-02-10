<?php


class SegTree {

    private $N = 1;
    private $tree = null;
    private $e = 0;
    private $hierarchy = 0;

    function __construct($N, $e = 0) {
        $hierarchy = 0;
        while ($this->N < $N) {
            $this->N *= 2;
            ++$hierarchy;
        }
        $n = $this->N;
        for($i=0;$i<=$hierarchy;$i++){
            $range = 1<<$i;
            $this->tree[$i] = array_fill(0, $n, $e);
            $n>>=1;
        }
        $this->e = $e;
        $this->hierarchy = $hierarchy;
    }

    // $x番目の値を$valueで更新する
    // $xは0から始まる
    function update($x, $value){
        $tree =& $this->tree[0];
        $tree[$x] = $value;
        for($i=1;$i<=$this->hierarchy;++$i){
            $treeNext =& $this->tree[$i];
            $xnext = $x >> 1;
            $x &= ~1;
            //---------------------
            if($tree[$x] < $tree[$x+1]){
                $treeNext[$xnext] = $tree[$x+1];
            }else{
                $treeNext[$xnext] = $tree[$x];
            }
            //---------------------
            $x = $xnext;
            $tree =& $treeNext;
        }
    }
    // $start番目から$end番目までの和を取得する
    // $startは0から始まる
    function query($start, $end){
        $ans = $this->e;
        $tree =& $this->tree;
        $i = 0;
        $range = 1;
        while($start < $end){
            $tree_child =& $tree[$i];
            $s = $start>>$i;
            if($s & 1){
                //---------------------
                if($ans < $tree_child[$s]){
                    $ans = $tree_child[$s];
                }
                //---------------------
                $start += $range;
            }
            $e = $end >> $i;
            if($e & 1){
                //---------------------
                if($ans < $tree_child[$e-1]){
                    $ans = $tree_child[$e-1];
                }
                //---------------------
                $end -= $range;
            }
            ++$i;
            $range <<= 1;
        }
        return $ans;
    }
}
