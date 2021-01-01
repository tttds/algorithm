<?php
 
$test = new BinaryTree();
$test->insert(1);
$test->insert(2);
$test->insert(3);
$test->insert(4);
echo $test->max() === 4 ? "OK" : "NG";
echo $test->min() === 1 ? "OK" : "NG";

$test->delete(1);
echo $test->max() === 4 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(4);
echo $test->max() === 3 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->insert(5);
echo $test->max() === 5 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(5);
echo $test->max() === 3 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->insert(1);
echo $test->max() === 3 ? "OK" : "NG";
echo $test->min() === 1 ? "OK" : "NG";

$test->delete(1);
echo $test->max() === 3 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(3);
echo $test->max() === 2 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->insert(2);
echo $test->max() === 2 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(2);
echo $test->max() === 2 ? "OK" : "NG";
echo $test->min() === 2 ? "OK" : "NG";

$test->delete(2);
echo $test->max() === -1 ? "OK" : "NG";
echo $test->min() === -1 ? "OK" : "NG";

class Node {
  public $data;
  public $leftNode;
  public $rightNode;
  function __construct($data){
    $this->data = $data;
    $this->leftNode = null;
    $this->rightNode = null;
  }
}
 
class BinaryTree {
 
  private $root;
 
  function insert($data){
    $p =& $this->root;
    while($p != null){
      //if($p->data == $data) return false;
      if($p->data >= $data){
        $p =& $p->leftNode;
      }else if($p->data < $data){
        $p =& $p->rightNode;
      }
    }
    $p = new Node($data);
  }
 
  function delete($data){
    $p =& $this->root;
    $parent = null;
    while($p != null){
      // データ発見
      if($p->data == $data){
        // 子が左右両方に存在する場合、右の最小のノードを検索
        if($p->leftNode != null && $p->rightNode != null){
          $parent =& $p;
          $p2 =& $p->rightNode;
          while($p2->leftNode != null){
            $parent =& $p2;
            $p2 =& $p2->leftNode;
          }
          // データを更新
          $p->data =& $p2->data;
          $parent->leftNode = null;
        }        
        // 子が左だけ存在する場合
        else if($p->leftNode != null){
          //echo "left ".$parent->data ." ".$data.PHP_EOL;
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
        // 子が右だけ存在する場合
        else if($p->rightNode != null){
          //echo "right ".$parent->data ." ".$data.PHP_EOL;
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
        // 子なし
        else {
          //echo "leaf ".$parent->data ." ".$data.PHP_EOL;
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
