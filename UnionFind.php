<?php

/**
 * UnionFind
 */
class UnionFind {
    private $d = [];
    private $ccc;
    private $r = [];
    public function __construct($n){
        $d =& $this->d;
        $r =& $this->r;
        for($i=1;$i<=$n;++$i){
            $d[$i] = -1;
            $r[$i] = true;
        }        
        $this->ccc = $n;
    }
    public function unite($x, $y){
        $rx = $this->root($x);
        $ry = $this->root($y);
        if($rx == $ry) return false;
        $d =& $this->d;
        if($d[$rx] < $d[$ry]){
            $d[$rx] += $d[$ry];
            $d[$ry] = $rx;
            unset($this->r[$ry]);
        }else{
            $d[$ry] += $d[$rx];
            $d[$rx] = $ry;        
            unset($this->r[$rx]);
        }
        --$this->ccc;
        return true;
    }
    public function root($x){
        if($this->d[$x] < 0) return $x;
        return $this->d[$x] = $this->root($this->d[$x]);
    }
    public function size($x){
        return $this->d[$this->root($x)]*-1;
    }
    public function isSame($x, $y){
        return $this->root($x) == $this->root($y);        
    }
    /** 
     * 連結成分数を取得する 
     * @return int 連結成分数
     */
    public function count(){
        return $this->ccc;
    }

    /**
     * 全てのルートの要素を取得する
     * @return array ルート要素の配列
     */
    public function rootAll(){
        return array_keys($this->r);
    }
}
