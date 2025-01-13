<?php

/**
 * 素数についてのクラス
 */
class PrimeNumber {

    /**
     * $n について素因数分解をする
     * --例1--------------------------
     * $n = 16
     * $return = [2 => 4]
     * --例2--------------------------
     * $n = 24
     * $return = [2 => 3, 3 => 1]
     * 
     * 性能：nが16桁の素数（9007199254740997）で800ms程度
     * 
     * @param int $n 素因数分解したい値
     * @return array キーが素数、値が個数の連想配列
     */
    public static function factorize($n){
        $res = [];
        for($i=2; $i*$i<=$n; ++$i){
            if($n % $i != 0) continue;
            $res[$i]=0;
            while($n % $i == 0){
                ++$res[$i];
                $n /= $i;
            }
        }
        if($n != 1){
            $res[$n]=1;
        }
        return $res;
    }
    
    
    /**
     * $nまでの素数を調べる
     *
     * --例1--------------------------
     * $n = 16
     * $return = [2,3,5,7,11,13]
     * --例2--------------------------
     * $n = 24
     * $return = [2,3,5,7,11,13,17,18,23]
     * --------------------------------
     * 
     * 性能：
     * $n=1000000 60ms
     * $n=10000000 600ms
     * 
     * @param int $n
     * @return array 素数の配列
     */
    public static function createPrimeNumber($n){
        $sqrt = floor(sqrt($n));
        if($n > 1) $lists[2] = true;
        if($n > 2) $lists[3] = true;
        $cnt5 = 0;
        $cnt7 = 0;
        $cnt11 = 0;
        for ($i=5; $i<=$n; $i+=6,++$cnt5,++$cnt7) {
            if($cnt5 == 5) {
                $cnt5 = 0;
            }else {
                $lists[$i] = true;
            }
            if($cnt7 == 7) {
                $cnt7 = 0;
            }else{
                $lists[$i+2] = true;
            }
        }
        $prime = [];
        for ($i=5; $i<=$sqrt; $i+=6) {
            if (isset($lists[$i])) {
                $i2=$i+$i;
                $i3=$i2+$i;
                for ($j=$i3; $j<=$n; $j+=$i2) {
                    unset($lists[$j]);
                }
            }
            $ii = $i+2;
            if (isset($lists[$ii])) {
                $ii2=$ii+$ii;
                $ii3=$ii2+$ii;
                for ($j=$ii3; $j<=$n; $j+=$ii2) {
                    unset($lists[$j]);
                }
            }
        }
        $prime = array_keys($lists);
        return $prime;
    }
    
    /**
     * $n について素因数分解をする
     * ※事前に素数一覧を作成し、$primeとして渡す
     * ※$primeは√$nまでの素数を用意すること
     * 何回も素因数分解をする場合、factorizeより少し速い（多分）
     * --例1--------------------------
     * $n = 16
     * $return = [2 => 4]
     * --例2--------------------------
     * $n = 24
     * $return = [2 => 3, 3 => 1]
     * 
     * @param int $n 素因数分解したい値
     * @param array $prime 素因数の配列
     * @return array キーが素数、値が個数の連想配列
     */
    public static function factorizeUsePrime($n, &$prime){
        if($n === 1) return [];
        $prime_count = count($prime);
        $ret=[];
        $sqrt=floor(sqrt($n));
        for($i=0;$i<$prime_count;++$i){
            // 平方根を超えた場合は残った値が素数
            if($sqrt < $prime[$i]){
                $ret[$n]=1;
                return $ret;
            }
            // 割り切れる間続ける
            while($n % $prime[$i]==0){
                if(!isset($ret[$prime[$i]])){
                    $ret[$prime[$i]]=0;
                }
                $ret[$prime[$i]]++;
                $n=intdiv($n, $prime[$i]);
                if($n==1){
                    return $ret;
                }
            }
        }
    }
}
