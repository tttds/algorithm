<?php

$set = new SetBIT(200);
$set->insert(3);
$set->insert(9);
$set->insert(11);
$set->insert(12);
$set->insert(150);
//--- existsテスト ---
echo $set->exists(3) ? "OK" : "NG";echo PHP_EOL;
echo $set->exists(9) ? "OK" : "NG";echo PHP_EOL;
echo $set->exists(11) ? "OK" : "NG";echo PHP_EOL;
echo $set->exists(15) ? "NG" : "OK";echo PHP_EOL;
echo $set->exists(200) ? "NG" : "OK";echo PHP_EOL;

//--- eraseテスト ---
$set->erase(9);
echo $set->exists(9) ? "NG" : "OK";echo PHP_EOL;
$set->insert(9);
echo $set->exists(9) ? "OK" : "NG";echo PHP_EOL;

//--- getテスト ---
echo $set->get(-1) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(0) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(1) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(2) == 9 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(3) == 11 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(4) == 12 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(5) == 150 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(6) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(7) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(8) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(9) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->get(10) == -1 ? "OK" : "NG"; echo PHP_EOL;

//--- ge_min_valテスト ---
echo $set->ge_min_val(1) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->ge_min_val(2) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->ge_min_val(3) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->ge_min_val(4) == 9 ? "OK" : "NG"; echo PHP_EOL;
echo $set->ge_min_val(151) == -1 ? "OK" : "NG"; echo PHP_EOL;

//--- le_max_valテスト ---
echo $set->le_max_val(1) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->le_max_val(2) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->le_max_val(3) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->le_max_val(149) == 12 ? "OK" : "NG"; echo PHP_EOL;

/**
 * Binary Indexed Treeを使ったC++のstd::setもどき
 */
class SetBIT{
    
    public $n;
    public $bit;
    public $exp2;
    public $count;
    
    /**
     * 1～$nまでを扱うため、$nを超える値が入る場合は座圧すること
     */
    function __construct($n){
        $this->n = $n;
        $this->exp2 = 1;
        $this->count = 0;
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
        ++$this->count;
        for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
            $this->bit[$idx] += $x;
        }
    }

    /**
     * $iを削除する
     * $iは1～$nまでを取りうる
     */
    public function erase($i) {
        --$this->count;
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
        if($x <= 0 || $x > $this->count){
            return -1;
        }
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

    /**
     * 要素が$x以上になる最小の添え字
     */
    public function lower_bound($x) {
        return $this->sum($x-1)+1;
    }
    /**
     * 要素が$x以下になる最大の添え字
     */
    public function upper_bound($x) {
        return $this->sum($x);
    }
}
