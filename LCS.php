<?php

$s = "avapc";
$t = "apppvc";

echo lcs($s, $t);

$s = "a";
$t = "a";
echo lcs($s,$t);

/**
 * $s, $tの最長共通部分文字列nの長さを返す
 * $sと$tの長さは違ってもOK
 */
function lcs($s, $t){
    $m=strlen($s);
    $n=strlen($t);

    for($i=0;$i<=$m;$i++){
        $len[$i][0] = 0;
    }
    for($i=0;$i<=$n;$i++){
        $len[0][$i] = 0;
    }

    for ($i=1;$i<=$m;$i++){
        for($j=1;$j<=$n;$j++){
            if($s[$i-1] == $t[$j-1]){
                $len[$i][$j] = $len[$i-1][$j-1] + 1;
            } else {
                $len[$i][$j] = max($len[$i][$j-1], $len[$i-1][$j]);
            }        
        }
    }    
    return $len[$m][$n];
}


