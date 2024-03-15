<?php

class UnionFind {
    public $d = [];
    function __construct($n){
        $d =& $this->d;
        for($i=1;$i<=$n;++$i){
            $d[$i] = -1;
        }
    }
    function unite($x, $y){
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
    function root($x){
        if($this->d[$x] < 0) return $x;
        return $this->d[$x] = $this->root($this->d[$x]);
    }
    function size($x){
        return $this->d[$this->root($x)]*-1;
    }
    function isSame($x, $y){
        return $this->root($x) == $this->root($y);        
    }
}
