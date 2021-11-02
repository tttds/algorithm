<?php

$bt = new BinaryTrie(7, 3);
$bt->insert(5);
//$bt->insert(2);

echo implode(" ", $bt->nodes);
echo PHP_EOL;

//var_dump($bt->cnt);
//$bt->insert(3);
//var_dump($bt->cnt);
//$bt->insert(6);
//var_dump($bt->cnt);
echo $bt->lower_bound(1);
echo $bt->lower_bound(2);
echo $bt->lower_bound(3);
echo $bt->lower_bound(4);
echo $bt->lower_bound(5);
echo $bt->lower_bound(6);
echo $bt->lower_bound(7);

class BinaryTrie {
    public $n = 0;
    public $bitlen = 0;
    public $id = 0;
    public $nodes = null;
    public $cnt = null;

    function __construct($max_query, $bitlen){
        $this->n = $max_query * $bitlen;
        $this->nodes = array_fill(0, $this->n*2, -1);
        $this->cnt = array_fill(0, $this->n, 0);
        $this->id = 0;
        $this->bitlen = $bitlen;
    }

    function insert($x){
        $pt = 0;
        for($i=$this->bitlen-1; $i>=0; --$i){
            $y = ($x>>$i)&1;
            if($this->nodes[2*$pt+$y] == -1){
                $this->id += 1;
                $this->nodes[2*$pt+$y] = $this->id;
            }
            $this->cnt[$pt] += 1;
            $pt = $this->nodes[2*$pt+$y];
        }
        $this->cnt[$pt] = 1;
    }

    function lower_bound($x){
        $pt = 0;
        $ret = 1;
        for($i=$this->bitlen-1; $i>=0; --$i){
            if($pt == -1) break;
            if(($x>>$i)&1 && $this->nodes[2*$pt] != -1){
                $ret += $this->cnt[$this->nodes[2*$pt]];
            }
            $pt = $this->nodes[2*$pt+(($x>>$i)&1)];
        }
        return $ret;
    }
}

