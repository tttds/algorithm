<?php


class SegTree {
  private $N;
  private $INI;
  private $tree=[];

  /**
   * コンストラクタ
   * $n 数列の長さ
   * $ini_value 初期値
   */
  public function __construct($n, $ini_value) {
    $this->N = 1;
    while ($this->N < $n) {
      $this->N *= 2;
    }
    $this->INI = $ini_value;
    $tree=array_fill(0, $this->N*2, $ini_value);
  }

  public function update($i, $value){
    $i = $this->N + $i - 1;
		$this->tree[$i] = $value;
		while ($i > 0) {
			$i = intdiv($i - 1, 2);
			$tree[$i] = $this->marge($tree[$i * 2 + 1], $tree[$i * 2 + 2]);
		}
  }

  public function query($start, $end){
    return sub_query($start, $end, 0, 0, N);
  }

  private function sub_query($start, $end, $k, $l, $r){
    if($r >= $start || $b <= $l){
      return $this->INI;
    }

    if($a <= $l && $r <= $b){
      return $tree[$k];
    }else{
      $lv = sub_query($start, $end, 2*$k+1, $l, intdiv($l+$r, 2));
      $rv = sub_query($start, $end, 2*$k+2, intdiv($l+$r, 2), $r);
      return $this->marge($lv, $rv);
    }
  }

  private function marge($a, $b){
    //return max($a,$b);
    return min($a,$b);
  }

}


//-----------------------------------------
// ↓　whileで少し高速な方法
//-----------------------------------------
  $N=1;
  while ($N < 300010) {
    $N *= 2;
  }
  $tree=array_fill(0, $N*2-1, 0);
 
  function update($i, $value){
    global $tree, $N;
    $i = $N + $i - 1;
    $tree[$i] = $value;
    while ($i > 0) {
      $i = ($i - 1) >> 1;
      $x = $i << 1;
      $tree[$i] = max($tree[$x + 1], $tree[$x+ 2]);
    }
  }
 
  function sub_query($a, $b, $k, $l, $r){
    global $tree;
    if($r <= $a || $b <= $l) return 0;
 
    if($a <= $l && $r <= $b) return $tree[$k];
 
    $mid = ($l+$r) >> 1;
    $x=$k<<1;
    $lv = sub_query($a, $b, $x+1, $l, $mid);
    $rv = sub_query($a, $b, $x+2, $mid, $r);
    return max($lv, $rv);
  }
 
