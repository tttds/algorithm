<?php


class AngleSorter
{
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
            $ax = $a[0] - $originX;
            $ay = $a[1] - $originY;
            $bx = $b[0] - $originX;
            $by = $b[1] - $originY;

            $ha = self::half($ax, $ay, $startX, $startY, $clockwise);
            $hb = self::half($bx, $by, $startX, $startY, $clockwise);

            if ($ha !== $hb) {
                return $ha <=> $hb;
            }

            $cross = $ax * $by - $ay * $bx;

            if ($cross !== 0) {
                return $clockwise
                    ? ($cross <=> 0)
                    : -($cross <=> 0);
            }

            $da = $ax * $ax + $ay * $ay;
            $db = $bx * $bx + $by * $by;
            return $da <=> $db;
        });

        return $points;
    }

    public static function half(
        int $x,
        int $y,
        int $sx,
        int $sy,
        bool $clockwise
    ): int {
        // start × v
        $cross = $sx * $y - $sy * $x;

        if ($cross !== 0) {
            return $clockwise
                ? ($cross < 0 ? 0 : 1)   // CW：右側が前半
                : ($cross > 0 ? 0 : 1);  // CCW：左側が前半
        }

        // 同一直線上
        $dot = $sx * $x + $sy * $y;
        return $dot >= 0 ? 0 : 1;
    }
}
