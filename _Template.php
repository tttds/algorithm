<?php

//[$n,$m] = array_map('intval', explode(' ', trim(fgets(STDIN))));
//$a = array_map('intval', explode(' ', trim(fgets(STDIN))));
//[$s,$t] = explode(' ', trim(fgets(STDIN)));
//$s = explode(' ', trim(fgets(STDIN)));
//function decr($v){return --$v;}
//$alpha='abcdefghijklmnopqrstuvwxyz';
//$mod=1000000009;
//$mod=1000000007;
//$mod=998244353;
//$a=[];
//$b=[];
//for($i=0;$i<$n;$i++){
//}

/**
 * 二分探索木
 */
class BinaryTree {
 
    private $root;

    //------------------
    // データを登録する
    //------------------
    function insert($data){
        // 登録する場所を決めるからrootから探索する
        $p =& $this->root;
        $exists = false;
        $parent = null;
        // 探索ノードがなくなったらそこが登録する位置となる
        while($p != null){
            $parent =& $p;            
            // 現在のノードより
            // →小さい場合は左のノードを探索
            // →大きい場合は右のノードを探索
            // →同じ場合は現在のノードのカウントをインクリメントする
            if($p->data > $data){
                $p =& $p->leftNode;
            }else if($p->data < $data){
                $p =& $p->rightNode;
            }else{
                $p->count++;
                $exists = true;
                break;
            }
        }
        if(!$exists) {
            $p = new class($data) {
                public $data;
                public $leftNode;
                public $rightNode;
                public $parent;
                public $count;
                public $leftDepth;
                public $rightDepth;
                function __construct($data){
                    $this->data = $data;
                    $this->leftNode = null;
                    $this->rightNode = null;
                    $this->parent = null;
                    $this->count = 1;
                    $this->depth = 0;
                }
            };
            $p->parent =& $parent;
            // 親の深さを更新し、右ノードと左ノードの深さの差が1になると回転させる
            $p =& $p->parent;
            while($p != null){
                $rightDepth = $p->rightNode !== null ? $p->rightNode->depth : 0;
                $leftDepth = $p->leftNode !== null ? $p->leftNode->depth : 0;
                echo $leftDepth." ".$rightDepth.PHP_EOL;
                if($p->rightNode !== null && $rightDepth - $leftDepth >= 2){
                    // 左回転
                    $p->rightNode->parent =& $p->parent;
                    // 親がない場合はroot
                    if($p->parent === null){
                        $this->root =& $p->rightNode;
                    }else{
                        if($p->parent->data > $p->rightNode->data){
                            $p->parent->leftNode =& $p->rightNode;
                        }else{
                            $p->parent->rightNode =& $p->rightNode;
                        }
                    }
                    $p->parent =& $p->rightNode;
                    if($p->parent->leftNode !== null){
                        $p->parent->leftNode->parent =& $p;
                    }
                    $p->rightNode =& $p->parent->leftNode;
                    $p->parent->leftNode =& $p;

                    $p->depth = $this->depth($p);
                    $p->parent->depth = $this->depth($p->parent);
                    $p =& $p->parent->parent;
                }else if($p->leftNode != null && $leftDepth - $rightDepth >= 2){
                    // 右回転
                    $p->leftNode->parent =& $p->parent;
                    // 親がない場合はroot
                    if($p->parent === null){
                        $this->root =& $p->leftNode;
                    }else{
                        if($p->parent->data > $p->leftNode->data){
                            $p->parent->leftNode =& $p->leftNode;
                        }else{
                            $p->parent->rightNode =& $p->leftNode;
                        }
                    }
                    $p->parent =& $p->leftNode;
                    if($p->parent->rightNode !== null){
                        $p->parent->rightNode->parent =& $p;
                    }
                    $p->leftNode =& $p->parent->rightNode;
                    $p->parent->rightNode =& $p;

                    $p->depth = $this->depth($p);
                    $p->parent->depth = $this->depth($p->parent);
                    $p =& $p->parent->parent;
                }else{
                    // 子の深さ（深い方）＋１が自分の深さ
                    $p->depth = $this->depth($p);
                    $p =& $p->parent;
                }
            }
        }
    }

    //------------------
    // データを削除する
    //------------------
    function delete($data){
        $p =& $this->root;
        $parent = null;
        while($p != null){
            // データ発見
            if($p->data == $data){
                if($p->count > 1) {
                    $p->count--;
                    return true;
                }
                // 子が左右両方に存在する場合、右の最小のノードを検索
                if($p->leftNode != null && $p->rightNode != null){
                    $p_min =& $p->rightNode;
                    while($p_min->leftNode != null){
                        $p_min =& $p_min->leftNode;
                    }
                    // 右の最小ノードの親の左ノードはnull
                    $p_min->parent->leftNode = null;
                    // 右の最小ノードのデータを削除したノードのデータにする
                    $p->data =& $p_min->data;
                }        
                // 子が左だけ存在する場合、左のノードを削除した位置に持ってくる
                else if($p->leftNode != null){
                    if($p->parent != null){
                        // 削除ノードより削除ノードの親の方が大きい場合、
                        // 削除ノードの親の左ノードを更新
                        if($p->parent->data > $p->data){
                            $p->parent->leftNode =& $p->leftNode;
                        }
                        // 削除ノードより削除ノードの親の方が小さい場合、
                        // 削除ノードの親の右ノードを更新
                        else{
                            $p->parent->rightNode =& $p->leftNode;
                        }
                        $p->leftNode->parent =& $p->parent;  
                      }else{
                        // 削除対象がrootだった場合
                        $this->root =& $p->leftNode;
                    }
                }
                // 子が右だけ存在する場合、右のノードを削除した位置に持ってくる
                else if($p->rightNode != null){
                    if($p->parent != null){
                        // 削除ノードより削除ノードの親の方が大きい場合、
                        // 削除ノードの親の左ノードを更新
                        if($p->parent->data > $p->data){
                            $p->parent->leftNode =& $p->rightNode;
                        }
                        // 削除ノードより削除ノードの親の方が小さい場合、
                        // 削除ノードの親の右ノードを更新
                        else{
                            $p->parent->rightNode =& $p->rightNode;
                        }
                        $p->rightNode->parent =& $p->parent;
                      }else{
                        // 削除対象がrootだった場合
                        $this->root =& $p->rightNode;
                    }
                }
                // 子がない場合、親からの参照を外す
                else {
                    if($p->parent != null){
                      if($p->parent->data < $data){
                        $p->parent->rightNode = null;
                      }else {
                        $p->parent->leftNode = null;
                      }
                    }else{
                      // 削除対象がrootだった場合
                      $this->root = null;
                    }
                }
                return true;
            }

            // データが見つからなかった場合
            $parent =& $p;
            // 左を検索
            if($p->data > $data) $p =& $p->leftNode;
            // 右を検索
            else $p =& $p->rightNode;
        }
        return false;
    }

    //-----------------
    // データを検索する
    //-----------------
    function find($data){
        $p =& $this->root;
        while($p != null){
            // 見つかった
            if($p->data == $data) return true;
            // 小さい場合は左を探す
            if($p->data > $data) $p =& $p->leftNode;
            // 大きい場合は右を探す
            else if($p->data < $data) $p =& $p->rightNode;
        }
        // 見つからなかった
        return false;
    }
 
    //-----------------
    // 最小値を検索する
    // 1件もデータが入っていない場合は-1を返す
    //-----------------
    function min(){
        $p =& $this->root;
        if($p == null){
            return -1;
        }
        // 左のノードを最後まで探す
        while($p->leftNode != null){
            $p =& $p->leftNode;
        }
        return $p->data;
    }

    //-----------------
    // 最大値を検索する
    // 1件もデータが入っていない場合は-1を返す
    //-----------------
    function max(){
        $p =& $this->root;
        if($p == null){
            return -1;
        }
        // 右のノードを最後まで探す
        while($p->rightNode != null){
            $p =& $p->rightNode;
        }
        return $p->data;
    }

    //-----------------
    // 深さを計算する
    //-----------------
    function depth(&$p){
        $depth = 0;
        if($p->leftNode != null) $depth = $p->leftNode->depth + 1;
        if($p->rightNode != null) $depth = max($depth, $p->rightNode->depth + 1);
        return $depth;
    }
 
}


/**
 * 二項分布の関連のクラス
 */
class Binomial {

    /**
     * 二項係数の元を求める
     * 
     * @param Int $n 二項係数を求める上限（最大5x10^6程度）
     * @param Int $mod 余り
     *------------------------------
     * @return Array [$factorial, $ifactorial]
     * $factorial・・階乗
     * $ifactorial・・階乗の逆元 
     *
     */
    public function getBinomialInit_mod($n, $mod){
        $factorial = [1,1];
        $ifactorial = [1,1];
        $inv = [1,1];
        for($i=2;$i<=$n;$i++){
            $factorial[$i] = $factorial[$i-1] * $i % $mod;
            $inv[$i] = $mod - $inv[$mod%$i] * intdiv($mod, $i) % $mod;
            $ifactorial[$i] = $ifactorial[$i-1] * $inv[$i] % $mod;
        }
        return [$factorial, $ifactorial];
    }

    /**
     * nCkを求める
     * @param Array $fact 階乗
     * @param Array $ifact 階乗の逆元
     * @param Int $n nCkのn
     * @param Int $k nCkのk
     * @param Int $mod 余り
     */
    public function getBiomial_mod($fact, $ifact, $n, $k, $mod){
        if ($n < $k) return 0;
        if ($n < 0 || $k < 0) return 0;
        return $fact[$n] * ($ifact[$k] * $ifact[$n - $k] % $mod) % $mod;
    }
}

/**
 * Binary Indexed Tree
 */
class BIT{
    
    public $n;
    public $bit;
    
    function __construct($n){
        $this->n = $n;
        $this->bit = array_fill(0, $n+1, 0);
    }

    /**
     * $iに$xを追加する
     * $iは1～$nまでを取りうる
     * @param int $i
     * @param int $x
     */
    public function add($i, $x) {
        for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
            $this->bit[$idx] += $x;
        }
    }
    /**
     * 1～$iまでの合計を返す
     * @param int $i
     * @return int 1～$iまでの合計
     */
    public function sum($i) {
        $s=0;
        for ($idx = $i; $idx > 0; $idx -= ($idx & $idx*-1)) {
            $s += $this->bit[$idx];
        }
        return $s;
    }
}


class BoundSearch {
    // $arrayの中で$xより小さい値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosLT(&$a, $x){
        $ok=-1;
        $ng=count($a);
        if($ng === 0) return -1;
        while($ng-$ok > 1){
            $m=($ng+$ok)>>1;
            if($a[$m] < $x) $ok = $m;
            else $ng = $m;
        }
        return $ok;
    }

    // $arrayの中で$xより小さい値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは降順にソートされている前提
    function searchPosLT_DESC(&$a, $x){
        $ok=count($a);
        $ng=-1;
        if($ok === 0) return -1;
        while($ok-$ng > 1){
            $m=($ng+$ok)>>1;
            if($x > $a[$m]) $ok = $m;
            else $ng = $m;
        }
        if($ok == count($a)){
            return -1;
        }
        return $ok;
    }

    // $arrayの中で$x以下の値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosLE(&$a, $x){
        $ok=-1;
        $ng=count($a);
        if($ng === 0) return -1;
        while($ng-$ok > 1){
            $m=($ng+$ok)>>1;
            //echo "m:".$m." ok:".$ok." ng:".$ng." a:".$a[$m]." x:".$x.PHP_EOL;
            if($a[$m] <= $x) $ok = $m;
            else $ng = $m;
        }
        return $ok;
    }
    // $arrayの中で$xより大きい値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosGT(&$a, $x){
        $ok=count($a);
        $ng=-1;
        if($ng === 0) return -1;
        while($ok-$ng > 1){
            $m=($ng+$ok)>>1;
            //echo "m:".$m." ok:".$ok." ng:".$ng." a:".$a[$m]." x:".$x.PHP_EOL;
            if($a[$m] > $x) $ok = $m;
            else $ng = $m;
        }
        if($ok === count($a)) return -1;
        return $ok;
    }

    // $arrayの中で$x以上の値を持つ最初の位置を返す
    // 存在しない場合は-1
    // $arrayは昇順にソートされている前提
    function searchPosGE(&$a, $x){
        $ok=count($a);
        $ng=-1;
        if($ng === 0) return -1;
        while($ok-$ng > 1){
          $m=($ng+$ok)>>1;
          //echo "m:".$m." ok:".$ok." ng:".$ng." a:".$a[$m]." x:".$x.PHP_EOL;
          if($a[$m] >= $x) $ok = $m;
          else $ng = $m;
        }
        if($ok === count($a)) return -1;
        return $ok;
    }
}


class Combination {
    private $ptn = [];
    /**
     * @param Int $n nCmのnの部分
     * @param Int $m nCmのmの部分
     */
    function __construct(){
    }

    //-------------------------------------------------------------------------
    // 組み合わせのパターンを作成する（nCm）
    //-------------------------------------------------------------------------
    // 例1
    // $n = 4, $m = 2
    // $ret = [[0, 1], [0, 2], [0, 3], [1, 2], [1, 3], [2, 3]]
    // ＜使い方＞
    // $cp = new Combination();
    // $ptn = $cp->getCombinationPattern(4, 2);
    // ＜性能＞
    // 7×10^6件（28C9=6906900件）作成に1500ms, メモリ1GB程度
    // 4×10^6件（33C7=4272048件）作成に637ms, メモリ0.4GB程度
    // 8×10^6件（120C4=8214570件）作成に755ms, メモリ0.2GB程度
    //-------------------------------------------------------------------------
    public function getCombinationPattern($n, $m){
        if($m === 0) return [];
        $this->ptn = [];
        $nm = $n-$m;
        for($i=0; $i<=$nm; ++$i){
            $arr = [$i];
            $this->dfs($i+1, $n, $arr, $m-1);
        }
        return $this->ptn;
    }
    private function dfs($s, $e, &$arr, $x){
        if($x === 0) {
            $this->ptn[] = $arr;
            return;
        }
        $ex = $e-$x;
        for($i=$s; $i<=$ex; ++$i){
            $arr2 = $arr;
            $arr2[] = $i;
            $this->dfs($i+1, $e, $arr2, $x-1);
        }
    }
    
    //-------------------------------------------------------------------------
    // $n人を1つの組を最大$p人で$m組に分ける場合のパターンを作成する
    // ※人は区別しない、組は区別する
    //-------------------------------------------------------------------------
    // 例１
    // $n = 4, $m = 3, $n = 3
    // $ret = [[0,1,3],[0,2,2],[0,3,1],[1,0,3],[1,1,2],[1,2,1],[1,3,0],[2,0,2],[2,1,1],[2,2,0],[3,0,1],[3,1,0]]
    // ＜使い方＞
    // $cp = new Combination();
    // $ptn = $cp->getCombinationPattern(4, 2);
    // ＜性能＞
    // 3×10^6件（$n=20,$m=9,$n=20 3108105件）作成に1600ms, メモリ1.3GB程度
    // 3×10^6件（$n=100,$m=5,$n=50 3134001件）作成に1000ms, メモリ0.9GB程度
    //-------------------------------------------------------------------------
    public function getCombinationPatternSum($n, $m, $p){
        if($m === 0) return [];
        if($n === 0) return [array_fill(0, $m, 0)];
        $this->ptn = [];
        $arr = [];
        $this->dfs2(0, $p, $arr, $m, $n);
        return $this->ptn;
    }
    private function dfs2($s, $e, &$arr, $x, $sum) {
        if($x === 0) {
            $this->ptn[] = $arr;
            return;
        }
        $min = max(0, $sum-$e*($x-1));
        $max = min($e, $sum);
        for($i=$min; $i<=$max; $i++){
            $arr2 = $arr;
            $arr2[] = $i;
            $this->dfs2($min, $e, $arr2, $x-1, $sum-$i);
        }
    }
}


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

class Mod {
    /**
     * mod Pの世界で逆元を求める
     * フェルマーの小定理を使う
     * 
     *   a^(P-1) ≡ 1   (mod P) より
     *   a^(P-2) ≡ 1/a (mod P)
     * 
     * @param $value 逆元を求めたい値
     * @param $mod   素数
     */
    public function inverse($value, $mod) {
        return intval(gmp_powm($value,$mod-2,$mod));
    }

}

class Permutation {

    private $permu = [];

    /**
     * 順列の組み合わせを返す
     * @param Int $start 開始の数.0～9までを設定する。
     * @param Int $end 終了の数.$start以上で9までを設定する。
     * @return Array 全パターンの順列の配列。配列のサイズは($end-$start+1)!となる。
     * $start = 0, $end = 2の場合
     * $return = ["012","021","102","120","201","210"];
     * 
     * ＜使用例＞
     * permutationAll(0,2);
     * 
     * ＜処理性能＞
     * $start = 0, $end = 9の約362万の作成に550ms程度かかる
     * 
     */
    function permutationAll($start, $end){
        if($end < $start) return [];
        if($start == $end) return ["".$start];
        $n=[];
        for($i=$start;$i<=$end;$i++){
            $n[$i]=true;
        }
        $this->appendCount($n, "");

        return $this->permu;
    }

    private function appendCount(&$n, $str){
        $permu =& $this->permu;
        $a = [];
        foreach($n as $k => $v){
            $a[] = $k;
        }
        if(count($n)==4){
            $permu[] = $str.$a[0].$a[1].$a[2].$a[3];
            $permu[] = $str.$a[0].$a[1].$a[3].$a[2];
            $permu[] = $str.$a[0].$a[2].$a[1].$a[3];
            $permu[] = $str.$a[0].$a[2].$a[3].$a[1];
            $permu[] = $str.$a[0].$a[3].$a[1].$a[2];
            $permu[] = $str.$a[0].$a[3].$a[2].$a[1];
            $permu[] = $str.$a[1].$a[0].$a[2].$a[3];
            $permu[] = $str.$a[1].$a[0].$a[3].$a[2];
            $permu[] = $str.$a[1].$a[2].$a[0].$a[3];
            $permu[] = $str.$a[1].$a[2].$a[3].$a[0];
            $permu[] = $str.$a[1].$a[3].$a[0].$a[2];
            $permu[] = $str.$a[1].$a[3].$a[2].$a[0];
            $permu[] = $str.$a[2].$a[0].$a[1].$a[3];
            $permu[] = $str.$a[2].$a[0].$a[3].$a[1];
            $permu[] = $str.$a[2].$a[1].$a[0].$a[3];
            $permu[] = $str.$a[2].$a[1].$a[3].$a[0];
            $permu[] = $str.$a[2].$a[3].$a[0].$a[1];
            $permu[] = $str.$a[2].$a[3].$a[1].$a[0];
            $permu[] = $str.$a[3].$a[0].$a[1].$a[2];
            $permu[] = $str.$a[3].$a[0].$a[2].$a[1];
            $permu[] = $str.$a[3].$a[1].$a[0].$a[2];
            $permu[] = $str.$a[3].$a[1].$a[2].$a[0];
            $permu[] = $str.$a[3].$a[2].$a[0].$a[1];
            $permu[] = $str.$a[3].$a[2].$a[1].$a[0];
            return;
        }else if(count($a)==3){
            $permu[] = $str.$a[0].$a[1].$a[2];
            $permu[] = $str.$a[0].$a[2].$a[1];
            $permu[] = $str.$a[1].$a[0].$a[2];
            $permu[] = $str.$a[1].$a[2].$a[0];
            $permu[] = $str.$a[2].$a[0].$a[1];
            $permu[] = $str.$a[2].$a[1].$a[0];
            return;
        }else if(count($a)==2){
            $permu[] = $str.$a[0].$a[1];
            $permu[] = $str.$a[1].$a[0];
            return;
        }else if(count($a)==1){
            $permu[] = $str.key($a);
            return;
        }

        foreach($n as $key => $value){
            unset($n[$key]);
            $this->appendCount($n, $str.$key);
            $n[$key]=true;
        }
        return;
    }
}

//----------------------------------
// 座標圧縮
//----------------------------------
// 同じ値があった場合、圧縮後も同じ値になる
// （例１）
// パラメータ $a = [12, 6, 25, 38, 29, 46];
// 返り値    $ret = [2, 1, 3, 5, 4, 6];
// （例２）
// パラメータ $a = [6, 6, 23, 23, 4, 4]];
// 返り値    $ret = [2, 2, 3, 3, 1, 1];
//-----------------------------------
function press(&$a){
    // キーを保持したまま昇順にソート
    asort($a);
    $an = [];
    $first = true;
    $x = 1;
    foreach($a as $k => $v){
        if($first) {
            $an[$k] = $x;
            $before = $v;
            $first = false;
            continue;
        }
        if($v != $before){
            $x++;
        }
        $an[$k] = $x;
        $before = $v;
    }
    // キーで昇順にソート
    ksort($an);
    return $an;
}


/**
 * 素数についてのクラス
 */
class PrimeNumber {

    /**
     * $n について素因数分解をする
     * --例1--------------------------
     * $n = 16
     * $return = [2 => 4]
     * --例2--------------------------
     * $n = 24
     * $return = [2 => 3, 3 => 1]
     * 
     * 性能：nが16桁の素数（9007199254740997）で800ms程度
     * 
     * @param int $n 素因数分解したい値
     * @return array キーが素数、値が個数の連想配列
     */
    function factorize($n){
        $res = [];
        for($i=2; $i*$i<=$n; ++$i){
            if($n % $i != 0) continue;
            $res[$i]=0;
            while($n % $i == 0){
                ++$res[$i];
                $n /= $i;
            }
        }
        if($n != 1){
            $res[$n]=1;
        }
        return $res;
    }
    
    
    /**
     * $nまでの素数を調べる
     *
     * --例1--------------------------
     * $n = 16
     * $return = [2,3,5,7,11,13]
     * --例2--------------------------
     * $n = 24
     * $return = [2,3,5,7,11,13,17,18,23]
     * --------------------------------
     * 
     * 性能：
     * $n=1000000 60ms
     * $n=10000000 800ms
     * 
     * @param int $n
     * @return array 素数の配列
     */
    function createPrimeNumber($n){
        $sqrt = floor(sqrt($n));
        $lists = array_fill(2, $n-1, true);
        $prime = [];
        for ($i=2; $i<=$sqrt; ++$i) {
            if (isset($lists[$i])) {
                for ($j=$i*2; $j<=$n; $j+=$i) {
                    unset($lists[$j]);
                }
            }
        }
        $prime = array_keys($lists);
        return $prime;
    }
    
    /**
     * $n について素因数分解をする
     * ※事前に素数一覧を作成し、$primeとして渡す
     * ※$primeは√$nまでの素数を用意すること
     * 何回も素因数分解をする場合、factorizeより少し速い（多分）
     * --例1--------------------------
     * $n = 16
     * $return = [2 => 4]
     * --例2--------------------------
     * $n = 24
     * $return = [2 => 3, 3 => 1]
     * 
     * @param int $n 素因数分解したい値
     * @param array $prime 素因数の配列
     * @return array キーが素数、値が個数の連想配列
     */
    function factorizeUsePrime($n, &$prime){
        if($n === 1) return [];
        $prime_count = count($prime);
        $ret=[];
        $sqrt=floor(sqrt($n));
        for($i=0;$i<$prime_count;++$i){
            // 平方根を超えた場合は残った値が素数
            if($sqrt < $prime[$i]){
                $ret[$n]=1;
                return $ret;
            }
            // 割り切れる間続ける
            while($n % $prime[$i]==0){
                if(!isset($ret[$prime[$i]])){
                    $ret[$prime[$i]]=0;
                }
                $ret[$prime[$i]]++;
                $n=intdiv($n, $prime[$i]);
                if($n==1){
                    return $ret;
                }
            }
        }
    }
}


/**
 * ローリングハッシュ
 */
class RollingHash {

    private $b;
    private $mod;
    private $s = [];
    private $n;
    private $power = [];
    private $hash = [];

    /**
     * コンストラクタ
     * @param $s 対象の文字列（英小文字）
     * @param $b 基数
     * @param $mod 法
     */
    public function __construct(&$s, $b=18743, $mod=212341513){
        $this->b = $b;
        $this->mod = $mod;
        $this->s =& $s;
        $this->n = $n = strlen($s);
        // power
        $power =& $this->power;
        $power[0] = 1;
        for($i=0;$i<$n;++$i){
            $power[$i+1] = ($power[$i] * $b) % $mod;
        }
        // hash
        $hash =& $this->hash;
        $hash[0] = 0;
        for($i=0;$i<$n;++$i){
            $hash[$i+1] = ($hash[$i] * $b + ord($s[$i])) % $mod;
        }
    }

    /**
     * 文字列Sの$l～$r-1のハッシュ値を求める
     * ※ 0-indexed
     * 例：
     * S="abcedf"の場合、
     * get(0,2) = "ab"のハッシュ値
     * get(3,6) = "ced"のハッシュ値
     * 
     * @param $l 
     * @param $r 
     * @return Int ハッシュ値
     */
    public function get($l,$r){
        $hash = $this->hash[$r] - ($this->hash[$l] * $this->power[$r - $l] % $this->mod);
        if ($hash < 0) $hash += $this->mod;
        return $hash;
    }
}


class SegTree {

    private $N = 1;
    private $tree = null;
    private $e = 0;
    private $hierarchy = 0;
    private $op = null;

    function op($x, $y){
        return call_user_func($this->op, $x, $y);
    }

    function __construct($N, $op, $e = 0) {
        $hierarchy = 0;
        while ($this->N < $N) {
            $this->N *= 2;
            ++$hierarchy;
        }
        $this->tree = array_fill(0, $this->N*2, $e);
        $this->e = $e;
        $this->op = $op;
        $this->hierarchy = $hierarchy;
    }

    // $x番目の値を$valueで更新する
    // $xは0から始まる
    function update($x, $value){
        $x += $this->N;
        $tree =& $this->tree;
        $tree[$x] = $value;
        while($x){
            $xnext = $x>>1;
            $tree[$xnext] = $this->op($tree[$x], $tree[$x^1]);
            $x>>=1;
        }
    }
    // $l番目から$r-1番目までの和を取得する
    // $lは0から始まる
    function query($l, $r){
        $l+=$this->N;
        $r+=$this->N;
        $ans = $this->e;
        $tree =& $this->tree;
        while($l < $r){
            if($l & 1){
                $ans = $this->op($ans, $tree[$l]);
                ++$l;
            }
            if($r & 1){
                $ans = $this->op($ans, $tree[$r-1]);
            }
            $l>>=1;
            $r>>=1;
        }
        return $ans;
    }
}


/**
 * Binary Indexed Treeを使ったC++のstd::setもどき
 */
class Set{
    
    public $n;
    public $bit;
    public $exp2;
    public $count;
    
    /**
     * 1～$nまでを扱うため、$nを超える値が入る場合は座圧すること
     */
    function __construct($n){
        $this->n = $n;
        $this->exp2 = 1;
        $this->count = 0;
        while($this->exp2 * 2 <= $n){
            $this->exp2 *= 2;
        }
        $this->bit = array_fill(0, $n+1, 0);
    }

    /**
     * $iを追加する
     * $iは1～$nまでを取りうる
     */
    public function insert($i) {
        $x = 1;
        ++$this->count;
        for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
            $this->bit[$idx] += $x;
        }
    }

    /**
     * $iを削除する
     * $iは1～$nまでを取りうる
     */
    public function erase($i) {
        --$this->count;
        $x = -1;
        for ($idx = $i; $idx <= $this->n; $idx += ($idx & $idx*-1)) {
            $this->bit[$idx] += $x;
        }
    }

    /**
     * $iが存在するか
     * true: 存在する、false：存在しない
     */
    public function exists($i) {
        return ($this->sum($i) - $this->sum($i-1) > 0);
    }

    /**
     * $x番目の要素
     * 存在しない場合は -1を返す
     */
    public function get($x) {
        if($x <= 0 || $x > $this->count){
            return -1;
        }
        $sum = 0;
        $pos = 0;
        for($i = $this->exp2; $i > 0; $i = $i >> 1){
            $k = $pos + $i;
            //echo $i." ".$k.PHP_EOL;
            if($k <= $this->n && $sum + $this->bit[$k] < $x){
                $sum +=  $this->bit[$k];
                $pos += $i;
            }
        }
        if($pos == $this->n){
            return -1;
        }
        return $pos + 1;
    }
    
    /**
     * $x以上の最小の要素
     * 存在しない場合は -1を返す
     */
    public function ge_min_val($x) {
        return $this->get($this->sum($x-1)+1);
    }

    /**
     * $x以下の最大の要素
     * 存在しない場合は -1を返す
     */
    public function le_max_val($x) {
        $ret = $this->get($this->sum($x));
        if($ret == 1){
            if($this->exists(1)){
                return 1;
            }else{
                return -1;
            }
        } 
        return $ret;
    }

    /**
     * 1～$iまでの合計を返す
     */
    public function sum($i) {
        $s=0;
        for ($idx = $i; $idx > 0; $idx -= ($idx & $idx*-1)) {
            $s += $this->bit[$idx];
        }
        return $s;
    }

    /**
     * 要素が$x以上になる最小の添え字
     */
    public function lower_bound($x) {
        return $this->sum($x-1)+1;
    }
    /**
     * 要素が$x以下になる最大の添え字
     */
    public function upper_bound($x) {
        return $this->sum($x);
    }
}


class UnionFind {
    private $d = [];
    public function __construct($n){
        $d =& $this->d;
        for($i=1;$i<=$n;++$i){
            $d[$i] = -1;
        }
    }
    public function unite($x, $y){
        $rx = $this->root($x);
        $ry = $this->root($y);
        if($rx == $ry) return false;
        $d =& $this->d;
        if($d[$rx] < $d[$ry]){
            $d[$rx] += $d[$ry];
            $d[$ry] = $rx;
        }else{
            $d[$ry] += $d[$rx];
            $d[$rx] = $ry;        
        }
        return true;
    }
    public function root($x){
        if($this->d[$x] < 0) return $x;
        return $this->d[$x] = $this->root($this->d[$x]);
    }
    public function size($x){
        return $this->d[$this->root($x)]*-1;
    }
    public function isSame($x, $y){
        return $this->root($x) == $this->root($y);        
    }
}
