<?php
  
function factorize($n){
  $res = [];
  for($i=2; $i*$i<$n; $i++){
    if($n % $i != 0) continue;
    $res[$i]=0;
    while($n % $i == 0){      
      $res[$i]++;
      $n /= $i;
    }
  }
  if($n != 1){
    $res[$n]=0;
  }
  return $res;
}

  //$pn = new PrimeNumber();
  //var_dump($pn->primeFactorization(1000000));

  class PrimeNumber {

    public $prime = [];

    function createPrimeNumber($n){
      $sqrt = floor(sqrt($n));
      $lists = array_fill(2, $n-1, true);
      for ($i=2; $i<=$sqrt; $i++) {
        if (isset($lists[$i])) {
          for ($j=$i*2; $j<=$n; $j+=$i) {
            unset($lists[$j]);
          }
        }
      }
      $this->prime = array_keys($lists);
      return $this->prime;
    }

    function primeFactorization($value){
      if(count($this->prime) === 0){
        $this->createPrimeNumber($value);
      }
      $prime_count = count($this->prime);
      $ret=[];
      $sqrt=floor(sqrt($value));
      for($i=0;$i<$prime_count;$i++){
        // 平方根を超えた場合は残った値が素数
        if($sqrt < $this->prime[$i]){
          $ret[$value]=1;
          return $ret;
        }
        // 割り切れる間続ける
        while($value%$this->prime[$i]==0){
          if(!isset($ret[$this->prime[$i]])){
            $ret[$this->prime[$i]]=0;
          }
          $ret[$this->prime[$i]]++;
          $value=intdiv($value, $this->prime[$i]);
          if($value==1){
            return $ret;
          }
        }
      }
    }
  }
