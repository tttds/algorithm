<?php

  $ufw = new UnionFindWeight(6);
  $ufw->unite(1, 2, 10);
  $ufw->unite(2, 3, 10);
  echo $ufw->diff(1, 3); // 20
  echo PHP_EOL;

  $ufw->unite(3, 4, 5);
  $ufw->unite(4, 5, 3);
  echo $ufw->diff(3, 5); // 8
  echo PHP_EOL;

  $ufw->unite(4, 6, 4);

  echo $ufw->diff(1, 4); // 25
  echo PHP_EOL;
  echo $ufw->diff(2, 4); // 15
  echo PHP_EOL;
  echo $ufw->diff(3, 5); // 8
  echo PHP_EOL;
  echo $ufw->diff(6, 3); // -9
  echo PHP_EOL;


  class UnionFindWeight {
    public $d = [];
    public $diffWeight = [];
    function __construct($n){
      for($i=1;$i<=$n;$i++){
        $this->d[$i] = -1;
        $this->diffWeight[$i] = 0;
      }
    }
    function unite($x, $y, $weight){
      $rx = $this->root($x);
      $ry = $this->root($y);
      $weight += $this->weight($x);
      $weight -= $this->weight($y);
      if($rx == $ry) return;
      //echo $rx." ".$ry." ";
      if($this->size($rx) > $this->size($ry)){
        $this->d[$rx]+= $this->d[$ry];
        $this->d[$ry] = $rx;
        $this->diffWeight[$ry] = $weight;
        //echo $this->d[$rx].PHP_EOL;
      }else{
        $this->d[$ry]+= $this->d[$rx];
        $this->d[$rx] = $ry;        
        $this->diffWeight[$rx] = -$weight;
        //echo $this->d[$ry].PHP_EOL;
      }
    }

    function weight($x){
      $this->root($x);
      return $this->diffWeight[$x];
    }

    // $y - $x
    function diff($x, $y){
      return $this->weight($y)-$this->weight($x);
    }

    function root($x){
      //echo $x.PHP_EOL;
      if($this->d[$x] < 0){
        return $x;
      }
      //経路圧縮
      $r = $this->root($this->d[$x]);
      $this->diffWeight[$x] += $this->diffWeight[$this->d[$x]];
      return $this->d[$x] = $r;
    }
    function size($x){
      return $this->d[$this->root($x)]*-1;
    }
    function isSame($x, $y){
      return $this->root($x) == $this->root($y);        
    }
  }
