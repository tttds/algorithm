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
    $route[5][1] = 1;
    $route[7][1] = 1;

    $d = new Dijkstra($route, 7);
    $d->calc(4);
    var_dump($d->cheapest);


    class Dijkstra {
        public $route;
        public $cheapest;
        public $n;

        // $route 以下の形式で渡すこと
        //    $route[$i][$j] = $cost;
        //    $iから$jまでのコストが$cost
        // $n 件数
        function __construct(&$route, $n){
            $this->route = $route;
            $this->n = $n;
        }
        
        // $iから各点の距離を計算
        // $root ルートノード
        // $rootCost ルートノードに到達するまでのコスト
        //           基本的に0だが、自分に戻ってくる問題などはPHP_INT_MAXにしておく
        function calc($root){
            $this->cheapest = [];
            for($i=1;$i<=$this->n;$i++){
                $this->cheapest[$i]=PHP_INT_MAX;
            }
            $heap = new SplMinHeap();
            $heap->insert([0,$root]);
            $this->cheapest[$root]=0;
            $first=true;
            while(!$heap->isEmpty()){
                [$currentCost,$current]=$heap->extract();
                if(!isset($this->route[$current])) continue;

                foreach($this->route[$current] as $next => $nextCost){
                    $nextCostFromRoot = $currentCost + $nextCost;
                    if($this->cheapest[$next] > $nextCostFromRoot){
                        $this->cheapest[$next] = $nextCostFromRoot;
                        $heap->insert([$nextCostFromRoot, $next]);
                    }
                }
                if($first){
                    // ROOTからROOTに戻ってくる場合も算出するため
                    $this->cheapest[$root] = PHP_INT_MAX;
                    $first=false;
                }
            }
        }
    }
