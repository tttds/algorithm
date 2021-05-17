<?php

// 線分abと線分cdが交差しているかどうかを判定
function isCrossing($ax, $ay, $bx, $by, $cx, $cy, $dx, $dy) {
  $ta = ($cx - $dx) * ($ay - $cy) + ($cy - $dy) * ($cx - $ax);
  $tb = ($cx - $dx) * ($by - $cy) + ($cy - $dy) * ($cx - $bx);
  $tc = ($ax - $bx) * ($cy - $ay) + ($ay - $by) * ($ax - $cx);
  $td = ($ax - $bx) * ($dy - $ay) + ($ay - $by) * ($ax - $dx);

  return $tc * $td < 0 && $ta * $tb < 0;
  // return $tc * $td <= 0 && $ta * $tb <= 0; // 端点を含む場合
}

  
echo degreeFrom3Points(2, 0,0,0,2,0);
echo PHP_EOL;
echo degreeFrom3Points(1,2,0,0,2,0);
echo PHP_EOL;
echo degreeFrom3Points(-1,2,0,0,2,0);
echo PHP_EOL;
echo degreeFrom3Points(-2,0,0,0,2,0);
echo PHP_EOL;
echo degreeFrom3Points(-2,-1,0,0,2,0);
echo PHP_EOL;
echo degreeFrom3Points(2,-1,0,0,2,0);
echo PHP_EOL;

// 3点の作る角度を求める
// return: 
// (x2,y2)と(x1,y1)を結ぶ線分と、(x2,y2)と(x3,y3)を結ぶ線分の左回りの角度を度数で返す。
// 頂点の角(x2,y2)と(x1,y1)または(x3,y3)が同じ位置の場合は0を返す
function degreeFrom3Points($x1,$y1,$x2,$y2,$x3,$y3){
  $ax = $x1-$x2;
  $ay = $y1-$y2;
  $cx = $x3-$x2;
  $cy = $y3-$y2;
  if($ax==0 && $ay==0) return 0;
  if($cx==0 && $cy==0) return 0;
  
  $cos = ($ax*$cx+$ay*$cy) / (sqrt($ax*$ax+$ay*$ay)*sqrt($cx*$cx+$cy*$cy));

  if($cx*$ay-$ax*$cy < 0) return 360 - rad2deg(acos($cos));
  else return rad2deg(acos($cos));
}
