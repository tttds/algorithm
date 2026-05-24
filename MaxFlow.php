<?php

$mf = new MaxFlow();
$mf->addEdge(1, 2, 10);
$mf->addEdge(1, 3, 2);
$mf->addEdge(2, 3, 6);
$mf->addEdge(2, 4, 6);
$mf->addEdge(4, 3, 3);
$mf->addEdge(3, 5, 5);
$mf->addEdge(4, 5, 8);
echo $mf->maxFlow(1, 5);
echo PHP_EOL;

$mf = new MaxFlow();
$mf->addEdge(1, 2, 4);
$mf->addEdge(1, 3, 1);
$mf->addEdge(2, 4, 2);
$mf->addEdge(3, 4, 4);
echo $mf->maxFlow(1, 4);
echo PHP_EOL;

$mf = new MaxFlow();
$mf->addEdge(1, 2, 4);
$mf->addEdge(1, 3, 1);
$mf->addEdge(2, 3, 1);
$mf->addEdge(2, 4, 2);
$mf->addEdge(3, 4, 4);
echo $mf->maxFlow(1, 4);
echo PHP_EOL;


class MaxFlow {
    /**
     * @var array<int, array<int, array>>
     *
     * Edge:
     * [
     *   'to'  => int,
     *   'cap' => int,
     *   'rev' => int,
     * ]
     */
    private array $graph = [];

    /**
     * BFS用レベル
     * @var array<int, int>
     */
    private array $level = [];

    /**
     * DFS探索位置
     * @var array<int, int>
     */
    private array $iter = [];

    /**
     * 辺追加
     */
    public function addEdge(
        int $from,
        int $to,
        int $capacity
    ): void {

        $this->initNode($from);
        $this->initNode($to);

        $forwardIndex = count($this->graph[$from]);
        $reverseIndex = count($this->graph[$to]);

        // 正辺
        $this->graph[$from][] = [
            'to'  => $to,
            'cap' => $capacity,
            'rev' => $reverseIndex,
        ];

        // 逆辺
        $this->graph[$to][] = [
            'to'  => $from,
            'cap' => 0,
            'rev' => $forwardIndex,
        ];
    }

    /**
     * 最大流
     */
    public function maxFlow(
        int $source,
        int $sink
    ): int {

        $flow = 0;

        while (true) {

            // レベルグラフ構築
            $this->bfs($source);

            // sinkに到達不可
            if (!isset($this->level[$sink])) {
                return $flow;
            }

            $this->iter = [];

            while (true) {
                $f = $this->dfs($source, $sink, PHP_INT_MAX);
                if ($f === 0) break;
                $flow += $f;
            }
        }
    }

    /**
     * BFS
     */
    private function bfs(int $source): void
    {
        $this->level = [];

        $queue = new SplQueue();

        $this->level[$source] = 0;

        $queue->enqueue($source);

        while (!$queue->isEmpty()) {

            $v = $queue->dequeue();

            foreach ($this->graph[$v] as $edge) {

                // 容量なし
                if ($edge['cap'] <= 0) {
                    continue;
                }

                // 訪問済み
                if (isset($this->level[$edge['to']])) {
                    continue;
                }

                $this->level[$edge['to']]
                    = $this->level[$v] + 1;

                $queue->enqueue($edge['to']);
            }
        }
    }

    /**
     * DFS
     */
    private function dfs(
        int $v,
        int $sink,
        int $flow
    ): int {

        if ($v === $sink) {
            return $flow;
        }

        if (!isset($this->iter[$v])) {
            $this->iter[$v] = 0;
        }

        $edgeCount = count($this->graph[$v]);

        for (
            ;
            $this->iter[$v] < $edgeCount;
            $this->iter[$v]++
        ) {

            $i = $this->iter[$v];

            $edge = $this->graph[$v][$i];

            // 容量なし
            if ($edge['cap'] <= 0) {
                continue;
            }

            // レベル条件
            if (
                !isset($this->level[$edge['to']]) ||
                $this->level[$v]
                    >= $this->level[$edge['to']]
            ) {
                continue;
            }

            $pushed = $this->dfs(
                $edge['to'],
                $sink,
                min($flow, $edge['cap'])
            );

            if ($pushed <= 0) {
                continue;
            }

            // 正辺更新
            $this->graph[$v][$i]['cap']
                -= $pushed;

            // 逆辺更新
            $rev = $edge['rev'];

            $this->graph[$edge['to']][$rev]['cap']
                += $pushed;

            return $pushed;
        }

        return 0;
    }

    /**
     * ノード初期化
     */
    private function initNode(int $node): void
    {
        if (!isset($this->graph[$node])) {
            $this->graph[$node] = [];
        }
    }
}
