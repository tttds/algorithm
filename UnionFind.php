<?php

class UnionFind {
    private $d = [];
    public function __construct($n){
        $d =& $this->d;
        for($i=1;$i<=$n;++$i){
            $d[$i] = -1;
        }
    }
    public function unite($x, $y){
        $rx = $this->root($x);
        $ry = $this->root($y);
        if($rx == $ry) return false;
        $d =& $this->d;
        if($d[$rx] < $d[$ry]){
            $d[$rx] += $d[$ry];
            $d[$ry] = $rx;
        }else{
            $d[$ry] += $d[$rx];
            $d[$rx] = $ry;        
        }
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
}
