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

class Node {
  public $data;
  public $leftNode;
  public $rightNode;
  public $count;
  function __construct($data){
    $this->data = $data;
    $this->leftNode = null;
    $this->rightNode = null;
    $this->count = 1;
  }
}

//---------------
// 2分探索木
//---------------
class BinaryTree {
 
  private $root;

  //------------------
  // データを登録する
  //------------------
  function insert($data){
    // 登録する場所を決めるからrootから探索する
    $p =& $this->root;
    $isSame = false;
    // 探索ノードがなくなったらそこが登録する位置となる
    while($p != null){
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
        $isSame = true;
        break;
      }
    }
    if(!$isSame) {
      $p = new Node($data);
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
          $parent =& $p;
          $p2 =& $p->rightNode;
          while($p2->leftNode != null){
            $parent =& $p2;
            $p2 =& $p2->leftNode;
          }
          // 右の最小ノードを削除したノードの位置に持ってくる
          $p->data =& $p2->data;
          $parent->leftNode = null;
        }        
        // 子が左だけ存在する場合、左のノードを削除した位置に持ってくる
        else if($p->leftNode != null){
          if($parent != null){
            if($parent->data > $p->data){
              $parent->leftNode =& $p->leftNode;
            }else{
              $parent->rightNode =& $p->leftNode;
            }
          }else{
            // 削除対象がrootだった場合
            $this->root =& $p->leftNode;
          }
        }
        // 子が右だけ存在する場合、右のノードを削除した位置に持ってくる
        else if($p->rightNode != null){
          if($parent != null){
            if($parent->data > $p->data){
              $parent->leftNode =& $p->rightNode;
            }else{
              $parent->rightNode =& $p->rightNode;
            }
          }else{
            // 削除対象がrootだった場合
            $this->root =& $p->rightNode;
          }
        }
        // 子がない場合、親からの参照を外す
        else {
          if($parent != null){
            if($parent->data < $data){
              $parent->rightNode = null;
            }else {
              $parent->leftNode = null;
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
      if($p->data > $data)
      {
        $p =& $p->leftNode;
      }
      // 右を検索
      else if($p->data < $data)
      {
        $p =& $p->rightNode;
      }
    }
    return false;
  }

  //-----------------
  // データを検索する
  //-----------------
  function find($data){
    $p =& $this->root;
    while($p != null){
      if($p->data == $data){
        return true;
      }
      if($p->data > $data){
        $p =& $p->leftNode;
      }else if($p->data < $data){
        $p =& $p->rightNode;
      }
    }
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
    while($p->rightNode != null){
      $p =& $p->rightNode;
    }
    return $p->data;
  }
 
}
