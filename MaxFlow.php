<?php

$mf = new MaxFlow();
$mf->addEdge(1, 2, 10);
$mf->addEdge(1, 3, 2);
$mf->addEdge(2, 3, 6);
$mf->addEdge(2, 4, 6);
$mf->addEdge(4, 3, 3);
$mf->addEdge(3, 5, 5);
$mf->addEdge(4, 5, 8);
echo $mf->max_flow(1, 5);

/*
$mf = new MaxFlow();
$mf->addEdge(1, 2, 4);
$mf->addEdge(1, 3, 1);
$mf->addEdge(2, 4, 2);
$mf->addEdge(3, 4, 4);
echo $mf->max_flow(1, 4);
*/

/*
$mf = new MaxFlow();
$mf->addEdge(1, 2, 4);
$mf->addEdge(1, 3, 1);
$mf->addEdge(2, 3, 1);
$mf->addEdge(2, 4, 2);
$mf->addEdge(3, 4, 4);
echo $mf->max_flow(1, 4);
*/

class MaxFlow {

    public $G = [];
    public $used = [];

    function addEdge($from, $to, $cap){
        if(!isset($this->G[$from])) $this->G[$from] = [];
        if(!isset($this->G[$to])) $this->G[$to] = [];
        $this->G[$from][] = new Edge($to, $cap, count($this->G[$to]));
        $this->G[$to][] = new Edge($from, 0, count($this->G[$from])-1);
    }
    
    /**
     * 
     */
    function max_flow($s, $t) {
        $flow = 0;
        while(true) {
            $this->used = [];
            $f = $this->dfs($s, $t, PHP_INT_MAX);
            if($f == 0) return $flow;
            $flow += $f;
        }
    }

    function dfs($v, $t, $f){
        if($v == $t) return $f;
        $this->used[$v] = true;
        for($i=0;$i<count($this->G[$v]);$i++){
            $e =& $this->G[$v][$i];
            if(!isset($this->used[$e->to]) && $e->cap > 0){
                $d = $this->dfs($e->to, $t, min($f, $e->cap));
                if($d > 0){
                    $e->cap -= $d;
                    $this->G[$e->to][$e->rev]->cap += $d;
                    return $d;
                }
            }
        }
        return 0;
    }    
}

class Edge{
    public $to;
    public $cap;
    public $rev;
    function __construct($to, $cap, $rev){
        $this->to = $to;
        $this->cap = $cap;
        $this->rev = $rev;
    }
}


