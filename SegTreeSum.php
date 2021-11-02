<?php
//-----------------------------------------
// 区間和用のSegment Tree
// 使い方
// 「書き換える」となっている部分を問題に応じて書き換える
//-----------------------------------------
//----------------------------------------------------------
// 300010は数列の長さ。間に合わないケースがあるのでその場合は書き換える
//↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//↓↓書き換える
$segSum = new SegTreeSum(300010);
for($i=1;$i<=6;$i=$i+3){
  $segSum->update($i, $i+2);
  $segSum->update($i+1, $i+1);
  $segSum->update($i+2, $i);
}

echo $segSum->query(1, 1).PHP_EOL; // 3
echo $segSum->query(1, 2).PHP_EOL; // 5
echo $segSum->query(1, 3).PHP_EOL; // 6
echo $segSum->query(3, 3).PHP_EOL; // 1
echo $segSum->query(1, 4).PHP_EOL; // 12
echo $segSum->query(4, 5).PHP_EOL; // 11
echo $segSum->query(1, 6).PHP_EOL; // 21
echo $segSum->query(6, 6).PHP_EOL; // 4

//↑↑書き換える
//↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


class SegTreeSum {

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
            $this->tree[$i] = $this->tree[$x + 1] + $this->tree[$x+ 2];
        }
    }
    // $start番目から$end番目までの和を取得する
    function query($start, $end){
        return $this->sub_query($start-1, $end, 0, 0, $this->N);
    }
  
    function sub_query($a, $b, $k, $l, $r){
        if($b <= $l || $r <= $a) return 0;
        if($a <= $l && $r <= $b) return $this->tree[$k];

        $mid = ($l+$r) >> 1;
        $x = $k<<1;
        $lv = $this->sub_query($a, $b, $x+1, $l, $mid);
        $rv = $this->sub_query($a, $b, $x+2, $mid, $r);
        return $lv+$rv;
    }
}
