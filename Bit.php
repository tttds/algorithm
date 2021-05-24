<?php

  $bit = new BIT(100);
  $bit->add(1, 30);
  echo $bit->sum(2);
  echo PHP_EOL;
  $bit->add(3, 20);
  echo $bit->sum(2);
  echo PHP_EOL;
  echo $bit->sum(3);
  echo PHP_EOL;

  /**
   * Binary Indexed Tree
   */
  class BIT{
    
    public $n;
    public $bit;
    
    function __construct($n){
      $this->n = $n;
      $this->bit = array_fill(0, $n+1, 0);
    }

    /**
     * $iに$xを追加する
     * $iは1～$nまでを取りうる
     */
    public function add($i, $x) {
      for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
          $this->bit[$idx] += $x;
      }
    }
    /**
     * 1～$iまでの合計を返す
     */
    public function sum($i) {
      $s=0;
      for ($idx = $i; $idx > 0; $idx -= ($idx & $idx*-1)) {
          $s += $this->bit[$idx];
      }
      return $s;
    }
  }
