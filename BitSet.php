<?php

//---- test
$bs1 = new BitSet(101);
$bs2 = new BitSet(101);
$bs1->set(1);
$bs1->set(2);

$bs1->set(63);
$bs1->set(64);
$bs1->set(65);

$bs1->set(99);
$bs1->set(100);

$bs2->set(2);
$bs2->set(3);

$bs2->set(64);
$bs2->set(65);
$bs2->set(66);

$bs2->set(100);
$bs2->set(101);

$bs1->xor($bs2);

echo $bs1->get(1); //1
echo $bs1->get(2); //0
echo $bs1->get(3); //1

echo $bs1->get(63); //1
echo $bs1->get(64); //0
echo $bs1->get(65); //0
echo $bs1->get(66); //1

echo $bs1->get(99); //1
echo $bs1->get(100); //0
echo $bs1->get(101); //1


/**
 * BitSet
 */
class BitSet {
    private $num = [];
    private $index = [];
    private $bit = [];
    private $max_index = 0;
    function __construct($n){
        for($i=0;$i<=$n;$i++){
            $this->index[$i] = intdiv($i, 64);
            $this->num[$this->index[$i]] = 0;
            $this->bit[$i] = $i - $this->index[$i] * 64;
        }
        $this->max_index = intdiv($n, 64);
    }
    public function set($pos){
        $this->num[$this->index[$pos]] |= (1 << $this->bit[$pos]);
    }
    public function reset($pos){
        $this->num[$this->index[$pos]] |= (1 << $this->bit[$pos]);
    }
    public function xor($bs){
        for($i=0;$i<=$this->max_index;$i++){
            $this->num[$i] ^= $bs->num[$i];
        }
    }
    public function or($bs){
        for($i=0;$i<=$this->max_index;$i++){
            $this->num[$i] |= $bs->num[$i];
        }
       
    }
    public function and($bs){
        for($i=0;$i<=$this->max_index;$i++){
            $this->num[$i] &= $bs->num[$i];
        }
    }
    public function get($pos){
        return ($this->num[$this->index[$pos]] >> $this->bit[$pos]) & 1;
    }
}
