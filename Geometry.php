<?php

// 線分abと線分cdが交差しているかどうかを判定
function isCrossing($ax, $ay, $bx, $by, $cx, $cy, $dx, $dy, $isValidEndpoint=false) {
  $ta = ($cx - $dx) * ($ay - $cy) + ($cy - $dy) * ($cx - $ax);
  $tb = ($cx - $dx) * ($by - $cy) + ($cy - $dy) * ($cx - $bx);
  $tc = ($ax - $bx) * ($cy - $ay) + ($ay - $by) * ($ax - $cx);
  $td = ($ax - $bx) * ($dy - $ay) + ($ay - $by) * ($ax - $dx);

  // 4点が同一直線上の場合
  if($ta == 0 && $tb == 0 && $tc == 0 && $td == 0){
    if($isValidEndpoint){
      if($ax == $bx && $bx == $cx && $cx == $dx){
        return (
            ($ay <= max($cy, $dy) && $ay >= min($cy, $dy)) ||
            ($by <= max($cy, $dy) && $by >= min($cy, $dy)) ||
            ($cy <= max($ay, $by) && $cy >= min($ay, $by)) 
        ); 
      }else{
        return (
            ($ax <= max($cx, $dx) && $ax >= min($cx, $dx)) ||
            ($bx <= max($cx, $dx) && $bx >= min($cx, $dx)) ||
            ($cx <= max($ax, $bx) && $cx >= min($ax, $bx)) 
        );
      }
    }else{
      if($ax == $bx && $bx == $cx && $cx == $dx){
        return (
            ($ay < max($cy, $dy) && $ay > min($cy, $dy)) ||
            ($by < max($cy, $dy) && $by > min($cy, $dy)) ||
            ($cy < max($ay, $by) && $cy > min($ay, $by)) 
        );
      }else{
        return (
            ($ax < max($cx, $dx) && $ax > min($cx, $dx)) ||
            ($bx < max($cx, $dx) && $bx > min($cx, $dx)) ||
            ($cx < max($ax, $bx) && $cx > min($ax, $bx)) 
        );
      }
    }
  }

  if($isValidEndpoint){
    // 端点を含む場合
    return $tc * $td <= 0 && $ta * $tb <= 0;
  }else{
    return $tc * $td < 0 && $ta * $tb < 0;
  }
}

  
echo degreeOrRadianFrom3Points(2, 0,0,0,2,0);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(1,2,0,0,2,0);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(-1,2,0,0,2,0);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(-2,0,0,0,2,0);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(-2,-1,0,0,2,0);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(2,-1,0,0,2,0);
echo PHP_EOL;

  
echo degreeOrRadianFrom3Points(2, 0,0,0,2,0,true);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(1,2,0,0,2,0,true);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(-1,2,0,0,2,0,true);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(-2,0,0,0,2,0,true);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(-2,-1,0,0,2,0,true);
echo PHP_EOL;
echo degreeOrRadianFrom3Points(2,-1,0,0,2,0,true);
echo PHP_EOL;

// 3点の作る角度を求める
// return: 
// (x2,y2)と(x1,y1)を結ぶ線分と、(x2,y2)と(x3,y3)を結ぶ線分の左回りの角度を度数で返す。
// 頂点の角(x2,y2)と(x1,y1)または(x3,y3)が同じ位置の場合は0を返す
// ＊＊＊注意＊＊＊
// 戻り値は浮動小数点誤差を含む
function degreeOrRadianFrom3Points($x1,$y1,$x2,$y2,$x3,$y3,$isRadian=false){
  $ax = $x1-$x2;
  $ay = $y1-$y2;
  $cx = $x3-$x2;
  $cy = $y3-$y2;
  if($ax==0 && $ay==0) return 0;
  if($cx==0 && $cy==0) return 0;
  
  $cos = ($ax*$cx+$ay*$cy) / (sqrt($ax*$ax+$ay*$ay)*sqrt($cx*$cx+$cy*$cy));
  if($isRadian){
      if($cx*$ay-$ax*$cy < 0) return -acos($cos);
      else return acos($cos);
  }else{
      if($cx*$ay-$ax*$cy < 0) return 360 - rad2deg(acos($cos));
      else return rad2deg(acos($cos));    
  }
}


// X, Y座標を原点を中心に回転する（反時計回り）
function rotate2d($radian, $x, $y){
  $nsin = sin($radian);
  $ncos = cos($radian);
  $retX = $ncos * $x - $nsin * $y;
  $retY = $nsin * $x + $ncos * $y;
  return [$retX, $retY];
}

/**
 * 点1と点2を通る直線と点3からの最短距離を求める
 * 
 */
function distanctCrossPoint($x1, $y1, $x2, $y2, $x3, $y3){
  [$x, $y] = crossPoint($x1, $y1, $x2, $y2, $x3, $y3);
  $dx = $x3 - $x;
  $dy = $y3 - $y;
  return sqrt($dx*$dx + $dy*$dy);
}


/**
 * 点1と点2を通る直線と点3からの垂線の足の座標を求める
 */
function crossPoint($x1, $y1, $x2, $y2, $x3, $y3){
  [$a,$b,$c] = coefficientStraightLine($x1,$y1,$x2,$y2);
  $p = ($a*$a + $b*$b);
  $q = ($a*$b*$x3 - $a*$a*$y3 + $b*$c);
  if($p == 0) $y = 0;
  else $y = -1 / $p * $q;
  if($a == 0) $x = $x3;
  else $x = -1 * ($b*$y + $c) / $a;
  return [$x, $y];
}

/**
 * 点1と点2を通る直線の数式
 * ax + by + c = 0
 * のa, b, cを返す
 */
function coefficientStraightLine($x1,$y1,$x2,$y2){
  $dx = $x2 - $x1;
  $dy = $y2 - $y1;
  return [$dy, -$dx, $dx*$y1 - $dy*$x1];
}
