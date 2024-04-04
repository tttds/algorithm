<?php


class Mod {
    /**
     * mod Pの世界で逆元を求める
     * フェルマーの小定理を使う
     * 
     *   a^(P-1) ≡ 1   (mod P) より
     *   a^(P-2) ≡ 1/a (mod P)
     * 
     * @param $value 逆元を求めたい値
     * @param $mod   素数
     */
    public function inverse($value, $mod) {
        return intval(gmp_powm($value,$mod-2,$mod));
    }

}
