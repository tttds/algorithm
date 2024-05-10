<?php

class Doubling {

    private $doubling = null;

    /**
     * コントラクタ
     * @param array $a
     * $a[$i]=$jの形式 $iは1～$nまで
     * → iからjへ次の移動ができること
     * → 出次数が1である
     * ↓↓↓↓こんな感じ↓↓↓↓
     * 1 → 2 → 3 → 9
     * 　　　　 ↑　 ↓
     *     4 → 6 ← 7
     *     ↑
     *     8
     * @param int $n 要素数
     * @param int $max_move 移動数の最大値
     */
    function __construct(&$a, $n, $max_move = 1000000000000000000) {
        $logK = 1;
        while ((1 << $logK) <= $max_move){
            $logK++;
        }
        // doubling[k][i] : i番目から 2^k 進んだ町
        $doubling = [];
        for ($i=1; $i<=$n;++$i) {
            $doubling[0][$i] = $a[$i];
        }
        //echo ">>".$logK;
        echo PHP_EOL;
        // 前処理 doubling の計算
        for ($k=0; $k<$logK; ++$k) {
            for ($i=1; $i<=$n;++$i) {
                $doubling[$k+1][$i] = $doubling[$k][$doubling[$k][$i]];
            }
        }
        $this->doubling =& $doubling;
    }

    /**
     * $i番目から$k回移動した先の番号を返す
     * @param int $i $iは1から始まる
     * @param int $k $k回移動した先の番号を返す
     * @return int 移動先の番号
     */
    public function get($now, $k) {
        for ($i=0; $k; $i++) {
            if ($k&1) $now = $this->doubling[$i][$now];
            $k = $k >> 1;
        }
        return $now;
    }
}
