<?php

function suffix_array($s, $upper = null): array {
    return _sa_is(array_map('ord', str_split($s)), 255);
}

function _sa_is(array $s, int $upper): array {

    $n = count($s);
    if ($n === 0) return [];
    if ($n === 1) return [0];
    if ($n === 2) return ($s[0] < $s[1]) ? [0, 1] : [1, 0];
    if ($n < 10) return _sa_naive($s);
    if ($n < 40) return _sa_doubling($s);

    $sa = array_fill(0, $n, 0);
    $ls = array_fill(0, $n, false);
    for ($i = $n - 2; $i >= 0; $i--) {
        $ls[$i] = ($s[$i] === $s[$i + 1]) ? $ls[$i + 1] : ($s[$i] < $s[$i + 1]);
    }
    
    $sum_l = array_fill(0, $upper + 1, 0);
    $sum_s = array_fill(0, $upper + 1, 0);
    for ($i = 0; $i < $n; $i++) {
        if (!$ls[$i]) $sum_s[$s[$i]]++;
        else $sum_l[$s[$i] + 1]++;
    }
    for ($i = 0; $i <= $upper; $i++) {
        $sum_s[$i] += $sum_l[$i];
        if ($i < $upper) $sum_l[$i + 1] += $sum_s[$i];
    }
    
    $lms_map = array_fill(0, $n + 1, -1);
    $m = 0;
    for ($i = 1; $i < $n; $i++) {
        if (!$ls[$i - 1] && $ls[$i]) {
            $lms_map[$i] = $m;
            $m++;
        }
    }
    $lms = [];
    for ($i = 1; $i < $n; $i++) {
        if (!$ls[$i - 1] && $ls[$i]) {
            $lms[] = $i;
        }
    }
    
    induce($sa, $s, $ls, $sum_l, $sum_s, $lms);

    if ($m) {
        $sorted_lms = [];
        foreach ($sa as $v) {
            if ($lms_map[$v] !== -1) {
                $sorted_lms[] = $v;
            }
        }
    
        $rec_s = array_fill(0, $m, 0);
        $rec_upper = 0;
        $rec_s[$lms_map[$sorted_lms[0]]] = 0;
    
        for ($i = 1; $i < $m; $i++) {
            $left = $sorted_lms[$i - 1];
            $right = $sorted_lms[$i];
            
            if ($lms_map[$left] + 1 < $m) {
                $end_l = $lms[$lms_map[$left] + 1];
            } else {
                $end_l = $n;
            }
    
            if ($lms_map[$right] + 1 < $m) {
                $end_r = $lms[$lms_map[$right] + 1];
            } else {
                $end_r = $n;
            }
    
            $same = true;
            if ($end_l - $left !== $end_r - $right) {
                $same = false;
            } else {
                while ($left < $end_l) {
                    if ($s[$left] !== $s[$right]) {
                        break;
                    }
                    $left++;
                    $right++;
                }
                if ($left == $n || $s[$left] !== $s[$right]) {
                    $same = false;
                }
            }
    
            if (!$same) {
                $rec_upper++;
            }
            $rec_s[$lms_map[$sorted_lms[$i]]] = $rec_upper;
        }
    
        $rec_sa = _sa_is($rec_s, $rec_upper);
    
        for ($i = 0; $i < $m; $i++) {
            $sorted_lms[$i] = $lms[$rec_sa[$i]];
        }
        induce($sa, $s, $ls, $sum_l, $sum_s, $sorted_lms);
    }
    
    return $sa;
}

function _sa_naive(array $s): array {
    $sa = range(0, count($s) - 1);
    usort($sa, function ($i, $j) use ($s) {
        return strcmp(implode('', array_slice($s, $i)), implode('', array_slice($s, $j)));
    });
    return $sa;
}

function _sa_doubling(array $s): array {
    $n = count($s);
    $sa = range(0, $n - 1);
    $rnk = $s;
    $tmp = array_fill(0, $n, 0);
    $k = 1;
    while ($k < $n) {
        usort($sa, function ($x, $y) use ($rnk, $n, $k) {
            if ($rnk[$x] !== $rnk[$y]) return $rnk[$x] - $rnk[$y];
            $rx = ($x + $k < $n) ? $rnk[$x + $k] : -1;
            $ry = ($y + $k < $n) ? $rnk[$y + $k] : -1;
            return $rx - $ry;
        });
        $tmp[$sa[0]] = 0;
        for ($i = 1; $i < $n; $i++) {
            $tmp[$sa[$i]] = $tmp[$sa[$i - 1]] + (
                (($rnk[$sa[$i - 1]] !== $rnk[$sa[$i]]) || 
                 (($sa[$i - 1] + $k < $n ? $rnk[$sa[$i - 1] + $k] : -1) !== ($sa[$i] + $k < $n ? $rnk[$sa[$i] + $k] : -1))) ? 1 : 0
            );
        }
        $rnk = $tmp;
        $k *= 2;
    }
    return $sa;
}

function induce(&$sa, array $s, array $ls, array $sum_l, array $sum_s, array $lms) {

    $n = count($s);
    $sa = array_fill(0, $n, -1);

    $buf = $sum_s;
    foreach ($lms as $d) {
        if ($d === $n) continue;
        $sa[$buf[$s[$d]]++] = $d;
    }
    $buf = $sum_l;
    $sa[$buf[$s[$n - 1]]++] = $n - 1;
    for ($i = 0; $i < $n; $i++) {
        $v = $sa[$i];
        if ($v >= 1 && !$ls[$v - 1]) {
            $sa[$buf[$s[$v - 1]]++] = $v - 1;
        }
    }
    $buf = $sum_l;
    for ($i = $n - 1; $i >= 0; $i--) {
        $v = $sa[$i];
        if ($v >= 1 && $ls[$v - 1]) {
            $sa[--$buf[$s[$v - 1] + 1]] = $v - 1;
        }
    }
}
