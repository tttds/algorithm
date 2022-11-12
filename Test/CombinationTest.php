<?php

require "../Combination.php";

echo ">>>>>>>>>>>>>>>>>>  ". basename(__FILE__). " >>>>>>>>>>>>>>>>>>>".PHP_EOL;
echo ">>>>>>>>>>>>>>>>>>  TEST1 >>>>>>>>>>>>>>>>>>>".PHP_EOL;
$start = 1;
$end = 4;
$m = 2;
$ans = getCombinationPattern($start, $end, $m);
for($i=0;$i<count($ans);$i++){
  echo implode(" ", $ans[$i]);
  echo PHP_EOL;
}

echo ">>>>>>>>>>>>>>>>>>  TEST2 >>>>>>>>>>>>>>>>>>>".PHP_EOL;
$start = 1;
$end = 4;
$m = 3;
$ans = getCombinationPattern($start, $end, $m);

for($i=0;$i<count($ans);$i++){
    echo implode(" ", $ans[$i]);
    echo PHP_EOL;
}
  
echo ">>>>>>>>>>>>>>>>>>  TEST3 >>>>>>>>>>>>>>>>>>>".PHP_EOL;
$start = 0;
$end = 4;
$m = 3;
$ans = getCombinationPattern($start, $end, $m);

for($i=0;$i<count($ans);$i++){
    echo implode(" ", $ans[$i]);
    echo PHP_EOL;
}

echo ">>>>>>>>>>>>>>>>>>  TEST4 >>>>>>>>>>>>>>>>>>>".PHP_EOL;
$now = microtime(true);
$start = 1;
$end = 22;
$m = 11;
$ans = getCombinationPattern($start, $end, $m);
echo intval((microtime(true) - $now) * 1000) . "ms".PHP_EOL;
echo count($ans);

