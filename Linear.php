<?php
/**
 * 2元1次連立方程式
 * a1*X + b1*Y = c1
 * a2*X + b2*Y = c2
 *
 * @return array|null ['X' => float, 'Y' => float] or null（解なし・無限解）
 */
function solveLinear2($a1, $b1, $c1, $a2, $b2, $c2) {
    $D = $a1 * $b2 - $a2 * $b1;

    // 行列式が0 → 解なし or 無限解
    if (abs($D) < 1e-12) {
        return null;
    }

    $X = ($c1 * $b2 - $c2 * $b1) / $D;
    $Y = ($a1 * $c2 - $a2 * $c1) / $D;

    return [
        'X' => $X,
        'Y' => $Y,
    ];
}
