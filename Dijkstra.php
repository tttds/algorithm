<?php

    $route[1][2] = 1;
    $route[2][4] = 7;
    $route[4][5] = 3;
    $route[5][7] = 3;
    $route[4][2] = 4;
    $route[4][6] = 1;
    $route[6][7] = 1;
    $route[1][3] = 2;
    $route[3][4] = 4;
    $route[3][6] = 3;

    $d = new Dijkstra($route, 7);
    $d->calc(1);
    var_dump($d->cheapest);

    class Dijkstra {
        // $routeは以下の形式で渡すこと
        // $route[$i][$j] = $cost;
        // $iから$jまでのコストが$cost
        public $route;
        public $cheapest;
        // 1～$nまで
        public $n;
        function __construct(&$route, $n){
            $this->route = $route;
            $this->n = $n;
        }
        
        // $iから各点の距離を計算
        function calc($root){
            $this->cheapest = [];
            for($i=1;$i<=$this->n;$i++){
                $this->cheapest[$i]=PHP_INT_MAX;
            }
            $heap = new SplMinHeap();
            $heap->insert([0,$root]);
            $this->cheapest[$root]=0;

            while(!$heap->isEmpty()){
                [$cost,$current]=$heap->extract();
                if(!isset($this->route[$current])) continue;

                foreach($this->route[$current] as $next => $cost){
                    $costFromRoot = $this->cheapest[$current]+$cost;
                    if($this->cheapest[$next] > $costFromRoot){
                        $this->cheapest[$next] = $costFromRoot;
                        $heap->insert([$costFromRoot, $next]);
                    }
                }
            }
        }
    }
