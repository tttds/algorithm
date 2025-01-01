<?php

class SegTreeLazyMaxUpdateRecursive {
    private $tree = [];
    private $lazy = [];
    private $size;
    private $size1;

    public function __construct(array $data) {
        $this->size = count($data);
        $this->size1 = $this->size - 1;
        $treeSize = 1;
        while(true){
            $treeSize *= 2;
            if($treeSize >= 2 * $this->size) break;
        }
        $this->tree = array_fill(0, $treeSize, PHP_INT_MIN);
        $this->lazy = array_fill(0, $treeSize, null);
        $this->build(1, 0, $this->size1, $data); // ルートはインデックス 1
    }

    private function build($node, $start, $end, $data) {
        if ($start === $end) {
            $this->tree[$node] = $data[$start];
        } else {
            $mid = ($start + $end) >> 1;
            $leftChild = $node << 1;
            $rightChild = $leftChild + 1;

            $this->build($leftChild, $start, $mid, $data);
            $this->build($rightChild, ++$mid, $end, $data);

            if($this->tree[$leftChild] < $this->tree[$rightChild]){
                $this->tree[$node] = $this->tree[$rightChild];
            }else{
                $this->tree[$node] = $this->tree[$leftChild];
            }
        }
    }

    private function propagate($node, $start, $end) {
        $lazy =& $this->lazy;
        if ($lazy[$node] !== null) {
            $this->tree[$node] = $lazy[$node];

            if ($start != $end) {
                $child = $node << 1;
                $lazy[$child] = $lazy[$node];
                $lazy[++$child] = $lazy[$node];
            }

            $lazy[$node] = null;
        }
    }

    public function rangeUpdate($left, $right, $value) {
        $this->rangeUpdateRecursive(1, 0, $this->size1, $left, $right, $value);
    }

    private function rangeUpdateRecursive($node, $start, $end, $left, $right, $value) {

        if ($right < $start || $end < $left) {
            return;
        }

        if ($left <= $start && $end <= $right) {
            $this->lazy[$node] = $value;
            $this->propagate($node, $start, $end);
            return;
        }

        $this->propagate($node, $start, $end);

        $mid = ($start + $end) >> 1;
        $leftChild = $node << 1;
        $rightChild = $leftChild + 1;

        $this->rangeUpdateRecursive($leftChild, $start, $mid, $left, $right, $value);
        $this->rangeUpdateRecursive($rightChild, ++$mid, $end, $left, $right, $value);

        if($this->tree[$leftChild] < $this->tree[$rightChild]){
            $this->tree[$node] = $this->tree[$rightChild];
        }else{
            $this->tree[$node] = $this->tree[$leftChild];
        }
    }

    public function rangeQuery($left, $right) {
        return $this->rangeQueryRecursive(1, 0, $this->size1, $left, $right);
    }

    private function rangeQueryRecursive($node, $start, $end, $left, $right) {
        $this->propagate($node, $start, $end);

        if ($right < $start || $end < $left) {
            return PHP_INT_MIN;
        }

        if ($left <= $start && $end <= $right) {
            return $this->tree[$node];
        }

        $mid = ($start + $end) >> 1;
        $child = $node << 1;

        $leftMax = $this->rangeQueryRecursive($child, $start, $mid, $left, $right);
        $rightMax = $this->rangeQueryRecursive(++$child, ++$mid, $end, $left, $right);

        if($leftMax < $rightMax){
            return $rightMax;
        }else{
            return $leftMax;
        }
    }
}
