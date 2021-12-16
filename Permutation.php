<?php
permutationAll(0, 9);
//var_dump(permutationAll(0, 2));
//var_dump(permutationAll(1, 2));
//var_dump(permutationAll(3, 3));
//var_dump(permutationAll(3, 6));

  /**
   * 順列の組み合わせを返す
   * @param $n 数
   */
  function permutationAll($start, $end){
    if($end < $start){
      return [];
    }
    if($start == $end){
      return ["".$start];
    }
    $n=[];
    for($i=$start;$i<=$end;$i++){
      $n[$i]=true;
    }
    $permu=[];
    appendCount($n, "", $permu);

    return $permu;
  }

  function appendCount(&$n, $str, &$permu){
    $a = [];
    foreach($n as $k => $v){
      if(!$v) continue;
      $a[] = $k;
    }
    if(count($n)===4){
      $permu[] = $str.$a[0].$a[1].$a[2].$a[3];
      $permu[] = $str.$a[0].$a[1].$a[3].$a[2];
      $permu[] = $str.$a[0].$a[2].$a[1].$a[3];
      $permu[] = $str.$a[0].$a[2].$a[3].$a[1];
      $permu[] = $str.$a[0].$a[3].$a[1].$a[2];
      $permu[] = $str.$a[0].$a[3].$a[2].$a[1];
      $permu[] = $str.$a[1].$a[0].$a[2].$a[3];
      $permu[] = $str.$a[1].$a[0].$a[3].$a[2];
      $permu[] = $str.$a[1].$a[2].$a[0].$a[3];
      $permu[] = $str.$a[1].$a[2].$a[3].$a[0];
      $permu[] = $str.$a[1].$a[3].$a[0].$a[2];
      $permu[] = $str.$a[1].$a[3].$a[2].$a[0];
      $permu[] = $str.$a[2].$a[0].$a[1].$a[3];
      $permu[] = $str.$a[2].$a[0].$a[3].$a[1];
      $permu[] = $str.$a[2].$a[1].$a[0].$a[3];
      $permu[] = $str.$a[2].$a[1].$a[3].$a[0];
      $permu[] = $str.$a[2].$a[3].$a[0].$a[1];
      $permu[] = $str.$a[2].$a[3].$a[1].$a[0];
      $permu[] = $str.$a[3].$a[0].$a[1].$a[2];
      $permu[] = $str.$a[3].$a[0].$a[2].$a[1];
      $permu[] = $str.$a[3].$a[1].$a[0].$a[2];
      $permu[] = $str.$a[3].$a[1].$a[2].$a[0];
      $permu[] = $str.$a[3].$a[2].$a[0].$a[1];
      $permu[] = $str.$a[3].$a[2].$a[1].$a[0];
      return;
    }else if(count($a)===3){
      $permu[] = $str.$a[0].$a[1].$a[2];
      $permu[] = $str.$a[0].$a[2].$a[1];
      $permu[] = $str.$a[1].$a[0].$a[2];
      $permu[] = $str.$a[1].$a[2].$a[0];
      $permu[] = $str.$a[2].$a[0].$a[1];
      $permu[] = $str.$a[2].$a[1].$a[0];
      return;
    }else if(count($a)===2){
      $permu[] = $str.$a[0].$a[1];
      $permu[] = $str.$a[1].$a[0];
      return;
    }else if(count($a)===1){
      $permu[] = $str.key($a);
      return;
    }

    foreach($n as $key => $value){
      if(!$value) continue;
      $n[$key]=false;
      appendCount($n, $str.$key, $permu);
      $n[$key]=true;
    }
    return;
  }

