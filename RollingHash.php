<?php

$s = "ababcdabcd";
$rh = new RollingHash($s);
echo ($rh->get(0, 2)); // abのハッシュ   = 100009
echo PHP_EOL;
echo ($rh->get(2, 4)); // abのハッシュ   = 100009
echo PHP_EOL;
echo ($rh->get(1, 3)); // baのハッシュ   = 200015
echo PHP_EOL;
echo ($rh->get(0, 4)); // ababのハッシュ = 10598840
echo PHP_EOL;
echo ($rh->get(2, 6)); // abcdのハッシュ = 10798856
echo PHP_EOL;
echo ($rh->get(6, 10)); // abcdのハッシュ = 10798856
echo PHP_EOL;

/**
 * ローリングハッシュ
 */
class RollingHash {

    private $b;
    private $mod;
    private $s = [];
    private $t = [];
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
        $alpha = "abcdefghijklmnopqrstuvwxyz";
        for($i=0;$i<26;$i++) $alphanum[$alpha[$i]]=$i+1;
        $t =& $this->t;
        for($i=0;$i<$n;$i++){
            $t[$i] = $alphanum[$s[$i]];
        }
        // power
        $power =& $this->power;
        $power[0] = 1;
        for($i=0;$i<$n;++$i){
            $power[$i+1] = ($power[$i] * $b) % $mod;
        }
        // hash
        $hash =& $this->hash;
        $hash[0] = 0;;
        for($i=0;$i<$n;++$i){
            $hash[$i+1] = ($hash[$i] * $b + $t[$i]) % $mod;
        }
    }

    /**
     * s[$l,$r)のハッシュ値を求める
     * ※ 0-indexed
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
