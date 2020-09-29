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
