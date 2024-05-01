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

/**
 * ダイクストラ
 */
class Dijkstra {

    public $route;
    public $cheapest;
    public $n;

    /**
     * コンストラクタ
     * @param array $route
     *     以下の形式で渡すこと
     *     $route[$i][$j] = $cost;
     *     $iから$jまでのコストが$cost
     * @param int $n 件数
     */
    function __construct(&$route, $n){
        $this->route =& $route;
        $this->n = $n;
    }

    /**
     * ルートノードから各ノードの距離を計算
     * @param int $root ルートノード
     * @param boolean $isRootReturn ルートに戻る計算が必要かどうか
     */
    function calc($root, $isRootReturn = false){
        $cheapest = array_fill(1, $this->n, PHP_INT_MAX);
        $end = array_fill(1, $this->n, false);
        $route =& $this->route;
        // ROOT→次へ
        if(isset($this->route[$root])) {
            $pq = new SplPriorityQueue();
            foreach($route[$root] as $next => $cost){
                $cheapest[$next] = $cost;
                $pq->insert($next, -$cost);
            }
            // ROOTからROOTに戻ってくる場合はPHP_INT_MAX
            // 戻る必要がない場合は0
            if(!$isRootReturn){
                $cheapest[$root] = 0;
            }
            while($pq->count() > 0){
                $now = $pq->extract();
                if($end[$now]) continue;
                $end[$now] = true;
                if(isset($route[$now])) {
                    $cheapnow = $cheapest[$now];
                    foreach($route[$now] as $next => $cost){
                        $nextcost = $cheapnow + $cost;
                        if($cheapest[$next] > $nextcost){
                            $cheapest[$next] = $nextcost;
                            $pq->insert($next, -$nextcost);
                        }
                    }
                }
            }
        }
        $this->cheapest =& $cheapest;
    }
}
