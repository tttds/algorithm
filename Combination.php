<?php

class Combination {
    private $ptn = [];
    /**
     * @param Int $n nCmのnの部分
     * @param Int $m nCmのmの部分
     */
    function __construct(){
    }

    //-------------------------------------------------------------------------
    // 組み合わせのパターンを作成する（nCm）
    //-------------------------------------------------------------------------
    // 例1
    // $n = 4, $m = 2
    // $ret = [[0, 1], [0, 2], [0, 3], [1, 2], [1, 3], [2, 3]]
    // ＜使い方＞
    // $cp = new Combination();
    // $ptn = $cp->getCombinationPattern(4, 2);
    // ＜性能＞
    // 7×10^6件（28C9=6906900件）作成に1500ms, メモリ1GB程度
    // 4×10^6件（33C7=4272048件）作成に637ms, メモリ0.4GB程度
    // 8×10^6件（120C4=8214570件）作成に755ms, メモリ0.2GB程度
    //-------------------------------------------------------------------------
    public function getCombinationPattern($n, $m){
        if($m === 0) return [];
        $this->ptn = [];
        $nm = $n-$m;
        for($i=0; $i<=$nm; ++$i){
            $arr = [$i];
            $this->dfs($i+1, $n, $arr, $m-1);
        }
        return $this->ptn;
    }
    private function dfs($s, $e, &$arr, $x){
        if($x === 0) {
            $this->ptn[] =& $arr;
            return;
        }
        $ex = $e-$x;
        for($i=$s; $i<=$ex; ++$i){
            $arr2 = $arr;
            $arr2[] = $i;
            $this->dfs($i+1, $e, $arr2, $x-1);
        }
    }
    
    //-------------------------------------------------------------------------
    // $n人を1つの組を最大$p人で$m組に分ける場合のパターンを作成する
    // ※人は区別しない、組は区別する
    //-------------------------------------------------------------------------
    // 例１
    // $n = 4, $m = 3, $n = 3
    // $ret = [[0,1,3],[0,2,2],[0,3,1],[1,0,3],[1,1,2],[1,2,1],[1,3,0],[2,0,2],[2,1,1],[2,2,0],[3,0,1],[3,1,0]]
    // ＜使い方＞
    // $cp = new Combination();
    // $ptn = $cp->getCombinationPattern(4, 2);
    // ＜性能＞
    // 3×10^6件（$n=20,$m=9,$n=20 3108105件）作成に1600ms, メモリ1.3GB程度
    // 3×10^6件（$n=100,$m=5,$n=50 3134001件）作成に1000ms, メモリ0.9GB程度
    //-------------------------------------------------------------------------
    public function getCombinationPatternSum($n, $m, $p){
        if($m === 0) return [];
        if($n === 0) return [array_fill(0, $m, 0)];
        $this->ptn = [];
        $arr = [];
        $this->dfs2(0, $p, $arr, $m, $n);
        return $this->ptn;
    }
    private function dfs2($s, $e, &$arr, $x, $sum) {
        if($x === 0) {
            $this->ptn[] =& $arr;
            return;
        }
        $min = max(0, $sum-$e*($x-1));
        $max = min($e, $sum);
        for($i=$min; $i<=$max; $i++){
            $arr2 = $arr;
            $arr2[] = $i;
            $this->dfs2($min, $e, $arr2, $x-1, $sum-$i);
        }
    }
}

