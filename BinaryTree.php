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
      if($p->data == $data) return false;
      if($p->data > $data){
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
        // 右の最小のノードを検索
        if($p->leftNode != null && $p->rightNode != null){
          $p2 =& $p->rightNode;
          while($p2->leftNode == null){
            $p2 =& $p2->leftNode;
          }
          // データを更新
          $p->data =& $p2->data;
        }        
        // 左だけ
        else if($p->leftNode != null){
          echo "left ".$parent->data ." ".$data.PHP_EOL;
          if($parent->data > $p->data){
            $parent->leftNode =& $p->leftNode;
          }else{
            $parent->rightNode =& $p->leftNode;
          }
        }
        // 右だけ
        else if($p->rightNode != null){
          echo "right ".$parent->data ." ".$data.PHP_EOL;
          if($parent->data > $p->data){
            $parent->leftNode =& $p->rightNode;
          }else{
            $parent->rightNode =& $p->rightNode;
          }
        }
        // 葉だった
        else {
          echo "leaf ".$parent->data ." ".$data.PHP_EOL;
          if($parent->data < $data){
            $parent->rightNode = null;
          }
          else {
            $parent->leftNode = null;
          }
        }
        return true;
      }
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
    while($p->leftNode != null){
      $p =& $p->leftNode;
    }
    return $p->data;
  }

  function max(){
    $p =& $this->root;
    while($p->rightNode != null){
      $p =& $p->rightNode;
    }
    return $p->data;
  }

}
