<?php

  class UnionFind {
    public $d = [];
    function __construct($n){
      for($i=1;$i<=$n;$i++){
        $this->d[$i] = -1;
      }
    }
    function unite($x, $y){
      $rx = $this->root($x);
      $ry = $this->root($y);
      if($rx == $ry) return;
      //echo $rx." ".$ry." ";
      if($this->size($rx) > $this->size($ry)){
        $this->d[$rx]+= $this->d[$ry];
        $this->d[$ry] = $rx;
        //echo $this->d[$rx].PHP_EOL;
      }else{
        $this->d[$ry]+= $this->d[$rx];
        $this->d[$rx] = $ry;        
        //echo $this->d[$ry].PHP_EOL;
      }
    }
    function root($x){
      //echo $x.PHP_EOL;
      if($this->d[$x] < 0){
        return $x;
      }
      return $this->d[$x] = $this->root($this->d[$x]);
    }
    function size($x){
      return $this->d[$this->root($x)]*-1;
    }
    function isSame($x, $y){
      return $this->root($x) == $this->root($y);        
    }
  }
