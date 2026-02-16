<?php

class SegTreeSum {

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
        $this->tree = array_fill(0, $this->N*2, $e);
        $this->e = $e;
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
            //---------------------
            $tree[$xnext] = $tree[$x]+$tree[$x^1];
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
            //echo ">>".$l." ".$r.PHP_EOL;
            if($l & 1){
                //---------------------
                $ans += $tree[$l];
                //---------------------
                ++$l;
            }
            if($r & 1){
                //---------------------
                $ans += $tree[$r-1];
                //---------------------
            }
            $l>>=1;
            $r>>=1;
            //echo ">>>".$ans.PHP_EOL;
        }
        //var_dump($this->tree);
        return $ans;
    }
}
