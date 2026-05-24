<?php

/**
 * 遅延セグメント木（区間加算・区間和）
 */
class SegTreeLazySumAdd {

    private $tree = [];
    private $lazy = [];
    private $size;

    public function __construct(array $data) {

        $n = count($data);

        $powerOfTwo = 1;
        while ($powerOfTwo < $n) {
            $powerOfTwo <<= 1;
        }

        $this->size = $powerOfTwo;

        // 0埋め
        $extendedData = array_merge(
            $data,
            array_fill(0, $this->size - $n, 0)
        );

        $this->tree = array_fill(0, $this->size * 2, 0);

        // lazy[pos] = この区間に加算する値
        $this->lazy = array_fill(0, $this->size * 2, 0);

        // leaf
        for ($i = 0; $i < $this->size; $i++) {
            $this->tree[$this->size + $i] = $extendedData[$i];
        }

        // build
        for ($i = $this->size - 1; $i > 0; $i--) {
            $this->tree[$i] =
                $this->tree[$i << 1]
                + $this->tree[($i << 1) + 1];
        }
    }

    /**
     * ノードに加算を適用
     */
    private function apply($pos, $value, $length) {

        // 区間長 × 加算値
        $this->tree[$pos] += $value * $length;

        if ($pos < $this->size) {
            $this->lazy[$pos] += $value;
        }
    }

    /**
     * 遅延伝播
     */
    private function push($pos) {

        for ($h = (int)log($this->size, 2); $h > 0; $h--) {

            $i = $pos >> $h;

            if ($this->lazy[$i] != 0) {

                $len = 1 << ($h - 1);

                $this->apply(
                    $i << 1,
                    $this->lazy[$i],
                    $len
                );

                $this->apply(
                    ($i << 1) + 1,
                    $this->lazy[$i],
                    $len
                );

                $this->lazy[$i] = 0;
            }
        }
    }

    /**
     * 親方向へ更新
     */
    private function pull($pos) {

        while ($pos > 1) {

            $pos >>= 1;

            $this->tree[$pos] =
                $this->tree[$pos << 1]
                + $this->tree[($pos << 1) + 1]
                + $this->lazy[$pos] * $this->segmentLength($pos);
        }
    }

    /**
     * ノードの区間長
     */
    private function segmentLength($pos) {

        $depth = (int)floor(log($pos, 2));

        return 1 << (
            (int)log($this->size, 2) - $depth
        );
    }

    /**
     * 区間加算
     * [left, right)
     */
    public function rangeAdd($left, $right, $value) {

        $left += $this->size;
        $right += $this->size;

        $l0 = $left;
        $r0 = $right - 1;

        $this->push($l0);
        $this->push($r0);

        $length = 1;

        while ($left < $right) {

            if ($left & 1) {
                $this->apply($left++, $value, $length);
            }

            if ($right & 1) {
                $this->apply(--$right, $value, $length);
            }

            $left >>= 1;
            $right >>= 1;

            $length <<= 1;
        }

        $this->pull($l0);
        $this->pull($r0);
    }

    /**
     * 区間和
     * [left, right)
     */
    public function rangeQuery($left, $right) {

        $left += $this->size;
        $right += $this->size;

        $this->push($left);
        $this->push($right - 1);

        $res = 0;

        while ($left < $right) {

            if ($left & 1) {
                $res += $this->tree[$left++];
            }

            if ($right & 1) {
                $res += $this->tree[--$right];
            }

            $left >>= 1;
            $right >>= 1;
        }

        return $res;
    }
}
