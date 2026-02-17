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

//--- kthテスト ---
echo $set->kth(-1) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(0) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(1) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(2) == 9 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(3) == 11 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(4) == 12 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(5) == 150 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(6) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(7) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(8) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(9) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->kth(10) == -1 ? "OK" : "NG"; echo PHP_EOL;

//--- geMinテスト ---
echo $set->geMin(1) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->geMin(2) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->geMin(3) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->geMin(4) == 9 ? "OK" : "NG"; echo PHP_EOL;
echo $set->geMin(151) == -1 ? "OK" : "NG"; echo PHP_EOL;

//--- leMaxテスト ---
echo $set->leMax(1) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->leMax(2) == -1 ? "OK" : "NG"; echo PHP_EOL;
echo $set->leMax(3) == 3 ? "OK" : "NG"; echo PHP_EOL;
echo $set->leMax(149) == 12 ? "OK" : "NG"; echo PHP_EOL;

/**
 * Binary Indexed Tree を使った ordered set 風クラス
 * 値は 1 ～ n を扱う（それ以上は座圧してから使う）
 */
class SetBIT {

    private int $n;
    private array $bit;
    private int $size = 0;
    private int $maxPow2;

    public function __construct(int $n) {
        $this->n = $n;
        $this->bit = array_fill(0, $n + 1, 0);

        // n 以下の最大の2冪
        $p = 1;
        while (($p << 1) <= $n) {
            $p <<= 1;
        }
        $this->maxPow2 = $p;
    }

    /** 内部BIT加算 */
    private function add(int $i, int $delta): void {
        for ($idx = $i; $idx <= $this->n; $idx += ($idx & -$idx)) {
            $this->bit[$idx] += $delta;
        }
    }

    /** 1～i の累積和 */
    public function sum(int $i): int {
        if ($i <= 0) return 0;
        if ($i > $this->n) $i = $this->n;

        $s = 0;
        for ($idx = $i; $idx > 0; $idx -= ($idx & -$idx)) {
            $s += $this->bit[$idx];
        }
        return $s;
    }

    /** i を追加 */
    public function insert(int $i): void {
        if ($i < 1 || $i > $this->n) return;
        if ($this->exists($i)) return; // 二重登録防止

        $this->add($i, 1);
        $this->size++;
    }

    /** i を削除 */
    public function erase(int $i): void {
        if (!$this->exists($i)) return;

        $this->add($i, -1);
        $this->size--;
    }

    /** 要素数 */
    public function count(): int {
        return $this->size;
    }

    /** i が存在するか */
    public function exists(int $i): bool {
        if ($i < 1 || $i > $this->n) return false;
        return $this->sum($i) - $this->sum($i - 1) > 0;
    }

    /**
     * k 番目（1-indexed）の要素を返す
     * 無ければ -1
     */
    public function kth(int $k): int {
        if ($k <= 0 || $k > $this->size) return -1;

        $idx = 0;
        $acc = 0;

        for ($step = $this->maxPow2; $step > 0; $step >>= 1) {
            $next = $idx + $step;
            if ($next <= $this->n && $acc + $this->bit[$next] < $k) {
                $idx = $next;
                $acc += $this->bit[$next];
            }
        }
        return $idx + 1;
    }

    /** x 以上の最小の要素 */
    public function geMin(int $x): int {
        if ($x > $this->n) return -1;
        $rank = $this->sum($x - 1) + 1;
        return $this->kth($rank);
    }

    /** x 以下の最大の要素 */
    public function leMax(int $x): int {
        $rank = $this->sum($x);
        if ($rank == 0) return -1;
        return $this->kth($rank);
    }

    /**
     * lower_bound:
     * x以上の最小の要素を返す
     */
    public function lower_bound(int $x): int {
        $rank = $this->lower_bound_rank($x);
        if ($rank <= 0 || $rank > $this->size) return -1;
        return $this->kth($rank);
    }

    /**
     * upper_bound:
     * xより大きい最小の要素を返す
     */
    public function upper_bound(int $x): int {
        $rank = $this->upper_bound_rank($x);
        if ($rank <= 0 || $rank > $this->size) return -1;
        return $this->kth($rank);
    }

    /**
     * lower_bound_rank:
     * x以上の最小要素の順位（1-indexed）
     * 存在しない場合は size+1 を返す
     */
    public function lower_bound_rank(int $x): int {
        return $this->sum($x - 1) + 1;
    }

    /**
     * upper_bound_rank:
     * xより大きい最小要素の順位（1-indexed）
     * 存在しない場合は size+1 を返す
     */
    public function upper_bound_rank(int $x): int {
        return $this->sum($x) + 1;
    }
}
