<?php

$test = new BinaryTree();
$test->insert(1);
$test->insert(2);
$test->insert(3);
$test->insert(4);
$i=0;
echo ++$i;
echo $test->max() === 4 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 1 ? "OK" : "NG";

$test->delete(1);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 4 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(4);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 3 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->insert(5);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 5 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(5);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 3 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->insert(1);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 3 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 1 ? "OK" : "NG";

$test->delete(1);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 3 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(3);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 2 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->insert(2);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 2 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(2);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 2 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(2);
echo PHP_EOL;
echo ++$i;
echo $test->max() === -1 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === -1 ? "OK" : "NG";

echo PHP_EOL;
echo PHP_EOL;

$test = new BinaryTree();
$test->insert(0);
$test->insert(5);
$test->insert(2);
$test->insert(4);
$i=0;
echo ++$i;
echo $test->max() === 5 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 0 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->find(5) === true ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->find(0) === true ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->find(2) === true ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->find(4) === true ? "OK" : "NG";

$test->delete(0);
echo PHP_EOL;
echo ++$i;
echo $test->max() === 5 ? "OK" : "NG";
echo PHP_EOL;
echo ++$i;
echo $test->min() === 2 ? "OK" : "NG";


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
                public $depth;
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
                //echo $leftDepth." ".$rightDepth.PHP_EOL;
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
                    if($p->parent !== null){
                        $p->parent->depth = $this->depth($p->parent);
                        $p =& $p->parent->parent;    
                    }
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
                    if($p->parent !== null){
                        $p->parent->depth = $this->depth($p->parent);
                        $p =& $p->parent->parent;
                    }
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
