<?php

//-----------------------------------------
// 区間最小用のSegment Tree
// 使い方
// 「書き換える」となっている部分を問題に応じて書き換える
//-----------------------------------------
//----------------------------------------------------------
// 300010は数列の長さ。間に合わないケースがあるのでその場合は書き換える
//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//↓↓書き換える
$segMin = new SegTreeMin(300010);
for($i=1;$i<=6;$i=$i+3){
  $segMin->update($i, $i+2);
  $segMin->update($i+1, $i+1);
  $segMin->update($i+2, $i);
}

echo $segMin->query(1, 1).PHP_EOL; // 3
echo $segMin->query(1, 2).PHP_EOL; // 2
echo $segMin->query(1, 3).PHP_EOL; // 1
echo $segMin->query(3, 3).PHP_EOL; // 1
echo $segMin->query(1, 4).PHP_EOL; // 1
echo $segMin->query(4, 5).PHP_EOL; // 5
echo $segMin->query(1, 6).PHP_EOL; // 1
echo $segMin->query(6, 6).PHP_EOL; // 4

//↑↑書き換える
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


class SegTreeMin {

    private $N = 1;
    private $tree = null;

    function __construct($N) {
        while ($this->N < $N) {
            $this->N *= 2;
        }          
        $this->tree=array_fill(0, $this->N*2-1, 0);
    }

    // $i番目の値を$valueで更新する
    // $iは1から始まる
    function update($i, $value){
        $i = $this->N + $i - 2;
        $this->tree[$i] = $value;
        while ($i > 0) {
            $i = ($i - 1) >> 1;
            $x = $i << 1;
            $this->tree[$i] = min($this->tree[$x + 1], $this->tree[$x+ 2]);
        }
    }
    // $start番目から$end番目までの最小値を取得する
    function query($start, $end){
        return $this->sub_query($start-1, $end, 0, 0, $this->N);
    }
  
    function sub_query($a, $b, $k, $l, $r){
        if($b <= $l || $r <= $a) return PHP_INT_MAX;
        if($a <= $l && $r <= $b) return $this->tree[$k];

        $mid = ($l+$r) >> 1;
        $x = $k<<1;
        $lv = $this->sub_query($a, $b, $x+1, $l, $mid);
        $rv = $this->sub_query($a, $b, $x+2, $mid, $r);
        return min($lv, $rv);
    }
}
