<?php
  class BIT{
    
    public $n;
    public $bit;
    
    function __construct($n){
      $this->n = $n;
      $this->bit = array_fill(0, $n+1, 0);
    }

    public function add($i, $x) {
      for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
          $this->bit[$idx] += $x;
      }
    }
    public function sum($i) {
      $s=0;
      for ($idx = $i; $idx > 0; $idx -= ($idx & $idx*-1)) {
          $s += $this->bit[$idx];
      }
      return $s;
  }
