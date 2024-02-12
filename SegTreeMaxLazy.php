<?php


class SegTreeMaxLazy {

  private $N = 1;
  private $tree = null;
  private $e = 0;
  private $hierarchy = 0;

  private $lazy = null;
  private $no = 0;

  function __construct($N, $e = 0) {
      $hierarchy = 0;
      while ($this->N < $N) {
          $this->N *= 2;
          ++$hierarchy;
      }
      $this->tree = array_fill(0, $this->N*2, $e);
      $this->lazy = array_fill(0, $this->N*2, 0);
      $this->e = $e;
      $this->hierarchy = $hierarchy;
  }

  // $x番目の値を$valueで更新する
  // $xは0から始まる
  function update($x, $value){
      $x += $this->N;
      $tree =& $this->tree;
      $tree[$x] = $value;
      while($x){
          $xnext = $x>>1;
          //---------------------
          if($tree[$x] < $tree[$x^1]){
              $tree[$xnext] = $tree[$x^1];
          }else{
              $tree[$xnext] = $tree[$x];
          }
          //---------------------
          $x>>=1;
      }
  }
  // $l番目から$r-1番目までの和を取得する
  // $lは0から始まる
  function query($l, $r){
      $l += $this->N;
      $r += $this->N;
      $ids = $this->gindex($l,$r);
      $this->propagates($ids);
      $ans = $this->e;
      $tree =& $this->tree;
      while($l < $r){
          //echo ">>".$l." ".$r.PHP_EOL;
          if($l & 1){
              //---------------------
              if($ans < $tree[$l]){
                  $ans = $tree[$l];
              }
              //---------------------
              ++$l;
          }
          if($r & 1){
              //---------------------
              if($ans < $tree[$r-1]){
                  $ans = $tree[$r-1];
              }
              //---------------------
          }
          $l>>=1;
          $r>>=1;
      }
      return $ans;
  }

  /**
   * $l, $rはあらかじめ$this->Nを加算していること
   */
  function gindex($l, $r){

      $x = ($l&-$l);
      $shiftl=0;
      while($x&1 === 0) $x>>=1; $shiftl++;
      $lm = $l>>$shiftl;
      
      $x = ($r&-$r);
      $shiftr=0;
      while($x&1 === 0) $x>>=1; $shiftr++;
      $rm = $r>>$shiftr;

      $ret = [];
      while($l < $r){
          if($l<=$lm) $ret[] = $l;
          if($r<=$rm) $ret[] = $r;
          $r>>=1;
          $l>>=1;
      }
      while($l){
          $ret[] = $l;
          $l>>=1;
      }
      return $ret;
  }

  function propagates($ids){
      $ids = array_reverse($ids);
      $tree =& $this->tree;
      $lazy =& $this->lazy;
      foreach($ids as $i){
          $v = $lazy[$i];
          if($v === 0) continue;
          $ii=$i<<1;
          $lazy[$i] = 0;
          $lazy[$ii] += $v;
          $tree[$ii] += $v;
          $lazy[++$ii] += $v;
          $tree[$ii] += $v;
      }
  }
  
  function add($l,$r,$x){
      $l += $this->N;
      $r += $this->N;
      $ids = $this->gindex($l,$r);
      $tree =& $this->tree;
      $lazy =& $this->lazy;
      //echo ">>>>>".$l." ".$r." ".$x.PHP_EOL;
      //var_dump($ids);
      while($l < $r){
          if($l&1){
              $lazy[$l] += $x;
              $tree[$l] += $x;
              ++$l;
          }
          if($r&1){
              $lazy[--$r] += $x;
              $tree[$r] += $x;
          }
          $r >>= 1;
          $l >>= 1;
      }
      foreach($ids as $i){
          $ii = $i<<1;
          //---------------------
          if($tree[$ii] < $tree[$ii+1]){
              $tree[$i] = $tree[$ii+1];
          }else{
              $tree[$i] = $tree[$ii];
          }         
          //---------------------
          $tree[$i] += $lazy[$i];
      }
  }
}
