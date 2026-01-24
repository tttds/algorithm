<?php

class AngleSorter
{
    /**
     * 偏角ソート（atan2なし・整数演算のみ）
     *
     * @param array $points [[x, y], ...]
     * @param int $originX
     * @param int $originY
     * @param int $startX スタート方向ベクトルX
     * @param int $startY スタート方向ベクトルY
     * @param bool $clockwise true=時計回り, false=反時計回り
     * @return array
     */
    public static function sort(
        array $points,
        int $originX = 0,
        int $originY = 0,
        int $startX = 0,
        int $startY = 1,
        bool $clockwise = true
    ): array {
        usort($points, function ($a, $b) use (
            $originX, $originY, $startX, $startY, $clockwise
        ) {
            // 原点からのベクトル
            $ax = $a[0] - $originX;
            $ay = $a[1] - $originY;
            $bx = $b[0] - $originX;
            $by = $b[1] - $originY;

            // 半平面判定
            $ha = self::half($ax, $ay, $startX, $startY);
            $hb = self::half($bx, $by, $startX, $startY);

            if ($ha !== $hb) {
                return $ha <=> $hb;
            }

            // 外積
            $cross = $ax * $by - $ay * $bx;

            if ($cross !== 0) {
                // 時計回り / 反時計回り切り替え
                return $clockwise ? -($cross <=> 0) : ($cross <=> 0);
            }

            // 同一直線上 → 距離順
            $da = $ax * $ax + $ay * $ay;
            $db = $bx * $bx + $by * $by;
            return $da <=> $db;
        });

        return $points;
    }

    /**
     * スタート方向基準の半平面判定
     * 0: スタート方向側（先）
     * 1: 反対側
     */
    private static function half(
        int $x,
        int $y,
        int $sx,
        int $sy
    ): int {
        // 外積 start × v
        $cross = $sx * $y - $sy * $x;

        if ($cross > 0) return 0;
        if ($cross < 0) return 1;

        // 同一直線上 → 内積で前後判定
        $dot = $sx * $x + $sy * $y;
        return $dot >= 0 ? 0 : 1;
    }
}
