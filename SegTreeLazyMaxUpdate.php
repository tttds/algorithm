<?php

/**
 * 遅延セグメント木（最大値・区間更新）
 */
class SegTreeLazyMaxUpdate {
    private $tree = [];
    private $lazy = [];
    private $size;

    /**
     * コンストラクタ
     * @param 初期データの配列
     * 例：$data = [0, 0, 0, 0, 3, 4];
     */
    public function __construct(array $data) {
        $n = count($data);
        $powerOfTwo = 1;
        while ($powerOfTwo < $n) {
            $powerOfTwo *= 2;
        }
        $this->size = $powerOfTwo;

        // 配列を次の2のべき乗に拡張
        $extendedData = array_merge($data, array_fill(0, $this->size - $n, PHP_INT_MIN));

        // 木を初期化
        $this->tree = array_fill(0, 2 * $this->size, PHP_INT_MIN);
        $this->lazy = array_fill(0, $this->size, null);

        // リーフノードにデータを配置
        for ($i = 0; $i < $this->size; $i++) {
            $this->tree[$this->size + $i] = $extendedData[$i];
        }

        // 内部ノードを構築
        for ($i = $this->size - 1; $i > 0; $i--) {
            $this->tree[$i] = max($this->tree[2 * $i], $this->tree[2 * $i + 1]);
        }
    }

    private function apply($pos, $value) {
        $this->tree[$pos] = $value; // 値を適用
        if ($pos < $this->size) {
            $this->lazy[$pos] = $value; // 遅延値を保存
        }
    }

    private function push($pos) {
        for ($h = floor(log($this->size, 2)); $h > 0; $h--) {
            $i = $pos >> $h;
            if ($this->lazy[$i] !== null) {
                $this->apply(($i<<1), $this->lazy[$i]);
                $this->apply(($i<<1) + 1, $this->lazy[$i]);
                $this->lazy[$i] = null;
            }
        }
    }

    private function pull($pos) {
        while ($pos > 1) {
            $pos >>= 1;
            if ($this->lazy[$pos] === null) {
                $pos2 = $pos << 1;
                if($this->tree[$pos2] < $this->tree[$pos2 + 1]) {
                    $this->tree[$pos] = $this->tree[$pos2 + 1];
                }else{
                    $this->tree[$pos] = $this->tree[$pos2];
                }
            }
        }
    }

    /**
     * 区間更新
     * 半開区間で指定する
     * 例：4～8のインデックスを5に更新する場合は rangeUpdate(4, 9, 5) とする
     * @param left 0以上の値
     * @param right 1以上の値
     * @param value 更新する値
     */
    public function rangeUpdate($left, $right, $value) {
        $left += $this->size;
        $right += $this->size;

        $l0 = $left;
        $r0 = $right - 1;

        $this->push($l0);
        $this->push($r0);

        while ($left < $right) {
            if ($left & 1) {
                $this->apply($left++, $value);
            }
            if ($right & 1) {
                $this->apply(--$right, $value);
            }
            $left >>= 1;
            $right >>= 1;
        }

        $this->pull($l0);
        $this->pull($r0);
    }

    /**
     * 区間取得
     * 半開区間で指定する
     */
    public function rangeQuery($left, $right) {
        $left += $this->size;
        $right += $this->size;

        $this->push($left);
        $this->push($right - 1);

        $res = PHP_INT_MIN;

        while ($left < $right) {
            if ($left & 1) {
                if($res < $this->tree[$left]) {
                    $res = $this->tree[$left];
                }
                $left++;
            }
            if ($right & 1) {
                if($res < $this->tree[--$right]) {
                    $res = $this->tree[$right];
                }
            }
            $left >>= 1;
            $right >>= 1;
        }

        return $res;
    }
}
