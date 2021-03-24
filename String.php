<?php

echo strswap("abcdefg", 0, 1).PHP_EOL;
echo strswap("abcdefg", 0, 2).PHP_EOL;
echo strswap("abcdefg", 0, 6).PHP_EOL;
echo strswap("abcdefg", 1, 2).PHP_EOL;
echo strswap("abcdefg", 2, 1).PHP_EOL;
echo strswap("abcdefg", 1, 6).PHP_EOL;
echo strswap("abcdefg", 3, 5).PHP_EOL;
echo strswap("abcdefg", 5, 6).PHP_EOL;
echo strswap("abcdefg", 6, 6).PHP_EOL;

function strswap($s, $i, $j){
    if($i == $j) return $s;
    $n = strlen($s);
    if($i > $j) [$i, $j] = [$j, $i];
    $ret = "";
    if($i != 0) $ret .= substr($s, 0, $i);
    $ret .= $s[$j];
    $ret .= substr($s, $i+1, $j-$i-1);
    $ret .= $s[$i];
    if($j < $n-1) $ret .= substr($s, $j+1, $n-$j-1);
    return $ret;
  }
  
  