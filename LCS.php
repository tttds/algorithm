<?php

$s = ["a","v","a","p","c"];
$t = ["a","p","p","p","v","c"];

echo lcs($s, $t);

$s = ["a"];
$t = ["a"];
echo lcs($s,$t);
/**
 * $s, $tの最長共通部分文字列nの長さを返す
 * $sと$tの長さは違ってもOK
 */
function lcs($s, $t){
    $m=strlen($s);
    $n=strlen($t);

    for($i=0;$i<=$n;++$i){
        $len1[$i] = 0;
    }

    for ($i=1;$i<=$m;++$i){
        $len2[0] = 0;
        $i1=$i-1;
        for($j=1;$j<=$n;++$j){
            $j1=$j-1;
            if($s[$i1] === $t[$j1]){
                $len2[$j] = $len1[$j1] + 1;
            } else {
                $len2[$j] = max($len2[$j1], $len1[$j]);
            }        
        }
        $len1 = $len2;
    }    
    return $len2[$n];
}


