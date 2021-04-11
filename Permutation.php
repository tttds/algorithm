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
    $n=[];
    for($i=$start;$i<=$end;$i++){
      $n[$i]=true;
    }
    $permu=[];
    appendCount($n, "", $permu);

    return $permu;
  }

  function appendCount($n, $str, &$permu){
    if(count($n) <= 4){

      if(count($n)===4){
        $a = [];
        foreach($n as $k => $v){
          $a[] = $k;
        }
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
      }

      if(count($n)===3){
        $a = [];
        foreach($n as $k => $v){
          $a[] = $k;
        }
        $permu[] = $str.$a[0].$a[1].$a[2];
        $permu[] = $str.$a[0].$a[2].$a[1];
        $permu[] = $str.$a[1].$a[0].$a[2];
        $permu[] = $str.$a[1].$a[2].$a[0];
        $permu[] = $str.$a[2].$a[0].$a[1];
        $permu[] = $str.$a[2].$a[1].$a[0];
        return;
      }
  
      if(count($n)===2){
        $a = [];
        foreach($n as $k => $v){
          $a[] = $k;
        }
        $permu[] = $str.$a[0].$a[1];
        $permu[] = $str.$a[1].$a[0];
        return;
      }

      if(count($n)===1){
        $permu[] = $str.key($n);
        return;
      }
    }

    foreach($n as $key => $value){
      unset($n[$key]);
      appendCount($n, $str.$key, $permu);
      $n[$key]=true;
    }
    return;
  }

