<?php
require "../Permutation.php";

echo ">>>>>>>>>>>>>>>>>>  ". basename(__FILE__). " >>>>>>>>>>>>>>>>>>>".PHP_EOL;
echo ">>>>>>>>>>>>>>>>>>  TEST1 >>>>>>>>>>>>>>>>>>>".PHP_EOL;
$now = microtime(true);
$ret = permutationAll(0, 9);
if(count($ret) != 10*9*8*7*6*5*4*3*2*1) echo "NG";
if($ret[0] != "0123456789") echo "NG";
echo intval((microtime(true) - $now) * 1000) . "ms";
