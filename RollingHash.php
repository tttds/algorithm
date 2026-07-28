<?php

/**
 * ダブルローリングハッシュ
 */
class RollingHash {

    private int $base;

    private int $mod1 = 2147483647; // 2^31-1
    private int $mod2 = 1000000007;

    private string $s;
    private int $n;

    private array $pow1 = [];
    private array $pow2 = [];

    private array $hash1 = [];
    private array $hash2 = [];

    public function __construct(string $s, int $base = 911382323)
    {
        $this->base = $base;
        $this->s = $s;
        $this->n = strlen($s);

        $this->pow1[0] = 1;
        $this->pow2[0] = 1;

        $this->hash1[0] = 0;
        $this->hash2[0] = 0;

        for ($i = 0; $i < $this->n; $i++) {

            $this->pow1[$i + 1] = ($this->pow1[$i] * $base) % $this->mod1;
            $this->pow2[$i + 1] = ($this->pow2[$i] * $base) % $this->mod2;

            $c = ord($s[$i]);

            $this->hash1[$i + 1] = ($this->hash1[$i] * $base + $c) % $this->mod1;
            $this->hash2[$i + 1] = ($this->hash2[$i] * $base + $c) % $this->mod2;
        }
    }

    /**
     * [l,r) のハッシュ値を返す
     *
     * @return array [hash1, hash2]
     */
    public function get(int $l, int $r): array
    {
        $x1 = $this->hash1[$r]
            - ($this->hash1[$l] * $this->pow1[$r - $l]) % $this->mod1;

        if ($x1 < 0) {
            $x1 += $this->mod1;
        }

        $x2 = $this->hash2[$r]
            - ($this->hash2[$l] * $this->pow2[$r - $l]) % $this->mod2;

        if ($x2 < 0) {
            $x2 += $this->mod2;
        }

        return [$x1, $x2];
    }

    /**
     * ハッシュ同士を比較
     */
    public static function equals(array $a, array $b): bool
    {
        return $a[0] === $b[0] && $a[1] === $b[1];
    }

    /**
     * ハッシュを連結する
     *
     * H(A+B) = H(A) * BASE^{|B|} + H(B)
     *
     * @param array $left       左側文字列のハッシュ [hash1, hash2]
     * @param int   $rightLength 右側文字列の長さ
     * @param array $right      右側文字列のハッシュ [hash1, hash2]
     *
     * @return array [hash1, hash2]
     */
    public function concat(array $left, int $rightLength, array $right): array
    {
        $h1 = (
            ($left[0] * $this->pow1[$rightLength]) % $this->mod1
            + $right[0]
        ) % $this->mod1;

        $h2 = (
            ($left[1] * $this->pow2[$rightLength]) % $this->mod2
            + $right[1]
        ) % $this->mod2;

        return [$h1, $h2];
    }

    /**
     * 文字列長
     */
    public function length(): int
    {
        return $this->n;
    }
}
