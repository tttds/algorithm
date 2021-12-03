<?php

  $bit = new SET(200);
  $bit->insert(3);
  $bit->insert(9);
  $bit->insert(11);
  $bit->insert(12);
  $bit->insert(150);
  //--- existsテスト ---
  echo $bit->exists(3) ? "OK" : "NG";echo PHP_EOL;
  echo $bit->exists(9) ? "OK" : "NG";echo PHP_EOL;
  echo $bit->exists(11) ? "OK" : "NG";echo PHP_EOL;
  echo $bit->exists(15) ? "NG" : "OK";echo PHP_EOL;
  echo $bit->exists(200) ? "NG" : "OK";echo PHP_EOL;

  //--- eraseテスト ---
  $bit->erase(9);
  echo $bit->exists(9) ? "NG" : "OK";echo PHP_EOL;
  $bit->insert(9);
  echo $bit->exists(9) ? "OK" : "NG";echo PHP_EOL;

  //--- getテスト ---
  echo $bit->get(1) == 3 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(2) == 9 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(3) == 11 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(4) == 12 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(5) == 150 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(6) == -1 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(7) == -1 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(8) == -1 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(9) == -1 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->get(10) == -1 ? "OK" : "NG"; echo PHP_EOL;

  //--- ge_min_valテスト ---
  echo $bit->ge_min_val(1) == 3 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->ge_min_val(2) == 3 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->ge_min_val(3) == 3 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->ge_min_val(4) == 9 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->ge_min_val(151) == -1 ? "OK" : "NG"; echo PHP_EOL;

  //--- le_max_valテスト ---
  echo $bit->le_max_val(1) == -1 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->le_max_val(2) == -1 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->le_max_val(3) == 3 ? "OK" : "NG"; echo PHP_EOL;
  echo $bit->le_max_val(149) == 12 ? "OK" : "NG"; echo PHP_EOL;

  /**
   * Binary Indexed Treeを使ったC++のstd::setもどき
   */
  class SET{
    
    public $n;
    public $bit;
    public $exp2;
    
    /**
     * 1～$nまでを扱うため、$nを超える値が入る場合は座圧すること
     */
    function __construct($n){
      $this->n = $n;
      $this->exp2 = 1;
      while($this->exp2 * 2 <= $n){
        $this->exp2 *= 2;
      }
      $this->bit = array_fill(0, $n+1, 0);
    }

    /**
     * $iを追加する
     * $iは1～$nまでを取りうる
     */
    public function insert($i) {
      $x = 1;
      for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
          $this->bit[$idx] += $x;
      }
    }

    /**
     * $iを削除する
     * $iは1～$nまでを取りうる
     */
    public function erase($i) {
      $x = -1;
      for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
          $this->bit[$idx] += $x;
      }
    }

    /**
     * $iが存在するか
     * true: 存在する、false：存在しない
     */
    public function exists($i) {
      return ($this->sum($i) - $this->sum($i-1) > 0);
    }

    /**
     * $x番目の要素
     * 存在しない場合は -1を返す
     */
    public function get($x) {
      $sum = 0;
      $pos = 0;
      for($i = $this->exp2; $i > 0; $i = $i >> 1){
        $k = $pos + $i;
        //echo $i." ".$k.PHP_EOL;
        if($k <= $this->n && $sum + $this->bit[$k] < $x){
          $sum +=  $this->bit[$k];
          $pos += $i;
        }
      }
      if($pos == $this->n){
        return -1;
      }
      return $pos + 1;
    }
    
    /**
     * $x以上の最小の要素
     * 存在しない場合は -1を返す
     */
    public function ge_min_val($x) {
      return $this->get($this->sum($x-1)+1);
    }

    /**
     * $x以下の最大の要素
     * 存在しない場合は -1を返す
     */
    public function le_max_val($x) {
      $ret = $this->get($this->sum($x));
      if($ret == 1){
        if($this->exists(1)){
          return 1;
        }else{
          return -1;
        }
      } 
      return $ret;
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
