<?php

/**
 * グラフのサイクルに関するクラス
 */
class CycleDetection {

    private $route = null;
    private $n = 0;
    private $end = [];
    private $fin = [];

    function __construct($route, $n) {
        $this->route =& $route;
        $this->n = $n;
    }

    /**
     * グラフがサイクルかどうかを判定する
     * @return boolean true：サイクル、false：サイクルではない
     */
    public function isCycle() {
        for($i=1;$i<=$this->n;++$i){
            if($this->dfsCheckCycle($i)){
                return true;
            }
        }
        return false;
    }

    private function dfsCheckCycle($now){

        $this->end[$now]=true;
        if(isset($this->route[$now])) {
            foreach($this->route[$now] as $next => $value){
                if(isset($this->fin[$next])) continue;
                if(isset($this->end[$next]) && !isset($this->fin[$next])) {
                    return true;
                }
                if($this->dfsCheckCycle($next)) return true;
            }
        }
        $this->fin[$now]=true;
        return false;
    }
}
