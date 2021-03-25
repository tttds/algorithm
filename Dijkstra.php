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
        // 到達できない場合はPHP_INT_MAXが入っている
        public $cheapest;
        public $n;

        // $route 以下の形式で渡すこと
        //    $route[$i][$j] = $cost;
        //    $iから$jまでのコストが$cost
        // $n 件数
        function __construct(&$route, $n){
            $this->route =& $route;
            $this->n = $n;
        }

        // $iから各点の距離を計算
        // $root ルートノード
        // $rootCost ルートノードに到達するまでのコスト
        //           基本的に0だが、自分に戻ってくる問題などはPHP_INT_MAXにしておく
        function calc($root){

            $cheapest = array_fill(1, $this->n, PHP_INT_MAX);
            $fix = array_fill(1, $this->n, false);

            // ROOT→次へ
            if(isset($this->route[$root])) {
                $heap = new SplPriorityQueue();
                foreach($this->route[$root] as $next => $nextCost){
                    $cheapest[$next] = $nextCost;
                    $heap->insert($next, -$nextCost);
                }
                // ROOTからROOTに戻ってくる場合も算出するため
                $cheapest[$root] = PHP_INT_MAX;

                while($heap->valid()){

                    $current=$heap->extract();

                    if($fix[$current]) continue;

                    $fix[$current] = true;

                    if(isset($this->route[$current])) {
                        foreach($this->route[$current] as $next => $nextCost){
                            $nextCostFromRoot = $cheapest[$current] + $nextCost;
                            if($cheapest[$next] > $nextCostFromRoot){
                                $cheapest[$next] = $nextCostFromRoot;
                                $heap->insert($next, -$nextCostFromRoot);
                            }
                        }
                    }
                }
            }

            $this->cheapest = $cheapest;
        }
    }
