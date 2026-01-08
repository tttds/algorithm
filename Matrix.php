<?php
class Matrix {
    public int $n;
    public int $mod;
    public array $a;

    // n×n 行列（0 初期化）
    public function __construct(int $n, int $mod) {
        $this->n = $n;
        $this->mod = $mod;
        $this->a = array_fill(0, $n, array_fill(0, $n, 0));
    }

    /* ========= static utility ========= */

    // 単位行列
    public static function identity(int $n, int $mod): Matrix {
        $m = new Matrix($n, $mod);
        for ($i = 0; $i < $n; $i++) {
            $m->a[$i][$i] = 1;
        }
        return $m;
    }

    // 行列の和 A + B
    public static function add(Matrix $A, Matrix $B): Matrix {
        $n = $A->n;
        $mod = $A->mod;
        $res = new Matrix($n, $mod);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $res->a[$i][$j] = ($A->a[$i][$j] + $B->a[$i][$j]) % $mod;
            }
        }
        return $res;
    }

    // 行列積 A × B
    public static function mul(Matrix $A, Matrix $B): Matrix {
        $n = $A->n;
        $mod = $A->mod;
        $res = new Matrix($n, $mod);

        for ($i = 0; $i < $n; $i++) {
            for ($k = 0; $k < $n; $k++) {
                if ($A->a[$i][$k] == 0) continue;
                $aik = $A->a[$i][$k];
                for ($j = 0; $j < $n; $j++) {
                    $res->a[$i][$j] =
                        ($res->a[$i][$j] + $aik * $B->a[$k][$j]) % $mod;
                }
            }
        }
        return $res;
    }

    /* ========= instance methods ========= */

    // 行列累乗（繰り返し二乗法）
    public function power(int $exp): Matrix {
        $res = Matrix::identity($this->n, $this->mod);
        $base = $this;

        while ($exp > 0) {
            if ($exp & 1) {
                $res = Matrix::mul($res, $base);
            }
            $base = Matrix::mul($base, $base);
            $exp >>= 1;
        }
        return $res;
    }

    // 行列 × ベクトル
    public function apply(array $v): array {
        $n = $this->n;
        $mod = $this->mod;
        $res = array_fill(0, $n, 0);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $res[$i] = ($res[$i] + $this->a[$i][$j] * $v[$j]) % $mod;
            }
        }
        return $res;
    }
}
