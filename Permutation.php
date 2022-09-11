<?php

/**
 * 順列の組み合わせを返す
 * @param $start 開始の数.0～9までを設定する。
 * @param $end 終了の数.$start以上で9までを設定する。
 * @return 全パターンの順列の配列。配列のサイズは($end-$start+1)!となる。
 * $start = 0, $end = 2の場合
 * $return = ["012","021","102","120","201","210"];
 * 
 * ＜使用例＞
 * permutationAll(0,2);
 * 
 * ＜処理性能＞
 * $start = 0, $end = 9の約362万の作成に800ms程度かかる
 * 
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

