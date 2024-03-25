<?php

$s = "ababcdabcd";
$rh = new RollingHash($s);
// abとab
check($rh->get(0, 2), $rh->get(2, 4));
// baとbab
check($rh->get(1, 3), $rh->get(1, 4), false);
// ababとabcd
check($rh->get(0, 4), $rh->get(2, 6), false);
// abcdとabcd
check($rh->get(2, 6), $rh->get(6, 10));

function check($a, $b, $isSame = true){
    if($a === $b && $isSame) echo "OK";
    else if($a !== $b && !$isSame) echo "OK";
    else echo "NG ". $a." ".$b;
    echo PHP_EOL;
}

/**
 * ローリングハッシュ
 */
class RollingHash {

    private $b;
    private $mod;
    private $s = [];
    private $n;
    private $power = [];
    private $hash = [];

    /**
     * コンストラクタ
     * @param $s 対象の文字列（英小文字）
     * @param $b 基数
     * @param $mod 法
     */
    public function __construct(&$s, $b=18743, $mod=212341513){
        $this->b = $b;
        $this->mod = $mod;
        $this->s =& $s;
        $this->n = $n = strlen($s);
        // power
        $power =& $this->power;
        $power[0] = 1;
        for($i=0;$i<$n;++$i){
            $power[$i+1] = ($power[$i] * $b) % $mod;
        }
        // hash
        $hash =& $this->hash;
        $hash[0] = 0;
        for($i=0;$i<$n;++$i){
            $hash[$i+1] = ($hash[$i] * $b + ord($s[$i])) % $mod;
        }
    }

    /**
     * 文字列Sの$l～$r-1のハッシュ値を求める
     * ※ 0-indexed
     * 例：
     * S="abcedf"の場合、
     * get(0,2) = "ab"のハッシュ値
     * get(3,6) = "ced"のハッシュ値
     * 
     * @param $l 
     * @param $r 
     * @return Int ハッシュ値
     */
    public function get($l,$r){
        $hash = $this->hash[$r] - ($this->hash[$l] * $this->power[$r - $l] % $this->mod);
        if ($hash < 0) $hash += $this->mod;
        return $hash;
    }
}
