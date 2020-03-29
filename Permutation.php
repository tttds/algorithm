<?php

  var_dump(permutationAll(1, 5));

  /**
   * 順列の組み合わせを返す
   * @param $n 数
   */
  function permutationAll($start, $end)
  {
    if($end < $start){
      return [];
    }
    $n=[];
    for($i=$start;$i<=$end;$i++){
      $n[$i]=true;
    }

    $arr = appendCount($n, "");

    return $arr;
  }

  function appendCount($n, $str){
    if(count($n)===1){
      return [$str.key($n)];
    }
    $arr = [];
    foreach($n as $key => $value){
      unset($n[$key]);
      $childs = appendCount($n, $str.$key);
      foreach($childs as $child){
        $arr[] = $child;
      }
      $n[$key]=true;
    }
    return $arr;
  }
