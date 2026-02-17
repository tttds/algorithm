<?php

[$fact, $ifact] = Binomial::getBinomialInit_mod(5000000, 1000000007);
echo Binomial::getBiomial_mod($fact, $ifact, 10, 2, 1000000007);

/**
 * 二項分布の関連のクラス
 */
class Binomial {

    /**
     * 二項係数の元を求める
     * 
     * @param Int $n 二項係数を求める上限（最大5x10^6程度）
     * @param Int $mod 余り
     *------------------------------
     * @return Array [$factorial, $ifactorial]
     * $factorial・・階乗
     * $ifactorial・・階乗の逆元 
     *
     */
    public static function getBinomialInit_mod($n, $mod){
        $factorial = [1,1];
        $ifactorial = [1,1];
        $inv = [1,1];
        for($i=2;$i<=$n;$i++){
            $factorial[$i] = $factorial[$i-1] * $i % $mod;
            $inv[$i] = $mod - $inv[$mod%$i] * intdiv($mod, $i) % $mod;
            $ifactorial[$i] = $ifactorial[$i-1] * $inv[$i] % $mod;
        }
        return [$factorial, $ifactorial];
    }

    /**
     * nCkを求める
     * @param Array $fact 階乗
     * @param Array $ifact 階乗の逆元
     * @param Int $n nCkのn
     * @param Int $k nCkのk
     * @param Int $mod 余り
     */
    public static function getBiomial_mod($fact, $ifact, $n, $k, $mod){
        if ($n < $k) return 0;
        if ($n < 0 || $k < 0) return 0;
        return $fact[$n] * ($ifact[$k] * $ifact[$n - $k] % $mod) % $mod;
    }
}
