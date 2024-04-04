<?php

class BoundSearch {
    // $arrayの中で$xより小さい値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosLT(&$a, $x){
        $ok=-1;
        $ng=count($a);
        if($ng === 0) return -1;
        while($ng-$ok > 1){
            $m=($ng+$ok)>>1;
            if($a[$m] < $x) $ok = $m;
            else $ng = $m;
        }
        return $ok;
    }

    // $arrayの中で$xより小さい値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは降順にソートされている前提
    function searchPosLT_DESC(&$a, $x){
        $ok=count($a);
        $ng=-1;
        if($ok === 0) return -1;
        while($ok-$ng > 1){
            $m=($ng+$ok)>>1;
            if($x > $a[$m]) $ok = $m;
            else $ng = $m;
        }
        if($ok == count($a)){
            return -1;
        }
        return $ok;
    }

    // $arrayの中で$x以下の値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosLE(&$a, $x){
        $ok=-1;
        $ng=count($a);
        if($ng === 0) return -1;
        while($ng-$ok > 1){
            $m=($ng+$ok)>>1;
            //echo "m:".$m." ok:".$ok." ng:".$ng." a:".$a[$m]." x:".$x.PHP_EOL;
            if($a[$m] <= $x) $ok = $m;
            else $ng = $m;
        }
        return $ok;
    }
    // $arrayの中で$xより大きい値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosGT(&$a, $x){
        $ok=count($a);
        $ng=-1;
        if($ng === 0) return -1;
        while($ok-$ng > 1){
            $m=($ng+$ok)>>1;
            //echo "m:".$m." ok:".$ok." ng:".$ng." a:".$a[$m]." x:".$x.PHP_EOL;
            if($a[$m] > $x) $ok = $m;
            else $ng = $m;
        }
        if($ok === count($a)) return -1;
        return $ok;
    }

    // $arrayの中で$x以上の値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosGE(&$a, $x){
        $ok=count($a);
        $ng=-1;
        if($ng === 0) return -1;
        while($ok-$ng > 1){
          $m=($ng+$ok)>>1;
          //echo "m:".$m." ok:".$ok." ng:".$ng." a:".$a[$m]." x:".$x.PHP_EOL;
          if($a[$m] >= $x) $ok = $m;
          else $ng = $m;
        }
        if($ok === count($a)) return -1;
        return $ok;
    }
}

$bs = new BoundSearch();
$a = [1, 3, 5, 12, 6, 7, 81, 12];
sort($a); // 1, 3, 5, 6, 7, 12, 12, 81

echo "###### searchPosLT Test ######\n";
echo $bs->searchPosLT($a, 7).PHP_EOL; // (3) 7より小さい値を持つ最初の場所
echo $bs->searchPosLT($a, 10).PHP_EOL; // (4) 10より小さい値を持つ最初の場所
echo $bs->searchPosLT($a, 2).PHP_EOL; // (0) 2より小さい値を持つ最初の場所
echo $bs->searchPosLT($a, 1).PHP_EOL; // (-1) 1より小さい値を持つ最初の場所
echo $bs->searchPosLT($a, 81).PHP_EOL; // (6) 81より小さい値を持つ最初の場所
echo $bs->searchPosLT($a, 100).PHP_EOL; // (7) 100より小さい値を持つ最初の場所

echo "###### searchPosLT_DESC Test ######\n";
rsort($a);
echo $bs->searchPosLT_DESC($a, 7).PHP_EOL; // (4) 7より小さい値を持つ最初の場所
echo $bs->searchPosLT_DESC($a, 10).PHP_EOL; // (3) 10より小さい値を持つ最初の場所
echo $bs->searchPosLT_DESC($a, 2).PHP_EOL; // (7)) 2より小さい値を持つ最初の場所
echo $bs->searchPosLT_DESC($a, 1).PHP_EOL; // (-1) 1より小さい値を持つ最初の場所
echo $bs->searchPosLT_DESC($a, 0).PHP_EOL; // (-1) 0より小さい値を持つ最初の場所
echo $bs->searchPosLT_DESC($a, 81).PHP_EOL; // (1) 81より小さい値を持つ最初の場所
echo $bs->searchPosLT_DESC($a, 100).PHP_EOL; // (0) 100より小さい値を持つ最初の場所

echo "###### searchPosLE Test ######\n";
sort($a);
echo $bs->searchPosLE($a, 7).PHP_EOL; // (4) 7以下の値を持つ最初の場所
echo $bs->searchPosLE($a, 10).PHP_EOL; // (4) 10以下の値を持つ最初の場所
echo $bs->searchPosLE($a, 2).PHP_EOL; // (0) 2以下の値を持つ最初の場所
echo $bs->searchPosLE($a, 1).PHP_EOL; // (0) 1以下の値を持つ最初の場所
echo $bs->searchPosLE($a, 0).PHP_EOL; // (-1) 0以下の値を持つ最初の場所
echo $bs->searchPosLE($a, 81).PHP_EOL; // (7) 81以下の値を持つ最初の場所
echo $bs->searchPosLE($a, 100).PHP_EOL; // (7) 100以下の値を持つ最初の場所

echo "###### searchPosGT Test ######\n";
echo $bs->searchPosGT($a, 7).PHP_EOL; // (5) 7より大きい値を持つ最初の場所
echo $bs->searchPosGT($a, 10).PHP_EOL; // (5) 10より大きい値を持つ最初の場所
echo $bs->searchPosGT($a, 2).PHP_EOL; // (1)) 2より大きい値を持つ最初の場所
echo $bs->searchPosGT($a, 1).PHP_EOL; // (1) 1より大きい値を持つ最初の場所
echo $bs->searchPosGT($a, 0).PHP_EOL; // (0) 0より大きい値を持つ最初の場所
echo $bs->searchPosGT($a, 81).PHP_EOL; // (-1) 81より大きい値を持つ最初の場所
echo $bs->searchPosGT($a, 100).PHP_EOL; // (-1) 100より大きい値を持つ最初の場所

echo "###### searchPosGE Test ######\n";
echo $bs->searchPosGE($a, 7).PHP_EOL; // (4) 7以上の値を持つ最初の場所
echo $bs->searchPosGE($a, 10).PHP_EOL; // (5) 10以上の値を持つ最初の場所
echo $bs->searchPosGE($a, 2).PHP_EOL; // (1)) 2以上の値を持つ最初の場所
echo $bs->searchPosGE($a, 1).PHP_EOL; // (0) 1以上の値を持つ最初の場所
echo $bs->searchPosGE($a, 0).PHP_EOL; // (0) 0以上の値を持つ最初の場所
echo $bs->searchPosGE($a, 81).PHP_EOL; // (7) 81以上の値を持つ最初の場所
echo $bs->searchPosGE($a, 100).PHP_EOL; // (-1) 100以上の値を持つ最初の場所

