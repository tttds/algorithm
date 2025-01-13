
class AVLNode {
    public $key;
    public $height;
    public $left;
    public $right;
    public $size;

    public function __construct($key) {
        $this->key = $key;
        $this->height = 1;
        $this->size = 1;
        $this->left = null;
        $this->right = null;
    }
}

class OrderedSet {
    private $root;

    public function __construct() {
        $this->root = null;
    }

    private function height($node) {
        return $node ? $node->height : 0;
    }

    private function size($node) {
        return $node ? $node->size : 0;
    }

    private function update($node) {
        $node->height = max($this->height($node->left), $this->height($node->right)) + 1;
        $node->size = $this->size($node->left) + $this->size($node->right) + 1;
    }

    private function rotateRight($y) {
        $x = $y->left;
        $T2 = $x->right;

        $x->right = $y;
        $y->left = $T2;

        $this->update($y);
        $this->update($x);

        return $x;
    }

    private function rotateLeft($x) {
        $y = $x->right;
        $T2 = $y->left;

        $y->left = $x;
        $x->right = $T2;

        $this->update($x);
        $this->update($y);

        return $y;
    }

    private function balanceFactor($node) {
        return $this->height($node->left) - $this->height($node->right);
    }

    private function balance($node) {
        $this->update($node);

        if ($this->balanceFactor($node) > 1) {
            if ($this->balanceFactor($node->left) < 0) {
                $node->left = $this->rotateLeft($node->left);
            }
            return $this->rotateRight($node);
        }

        if ($this->balanceFactor($node) < -1) {
            if ($this->balanceFactor($node->right) > 0) {
                $node->right = $this->rotateRight($node->right);
            }
            return $this->rotateLeft($node);
        }

        return $node;
    }

    private function insertNode($node, $key) {
        if (!$node) {
            return new AVLNode($key);
        }

        if ($key < $node->key) {
            $node->left = $this->insertNode($node->left, $key);
        } elseif ($key > $node->key) {
            $node->right = $this->insertNode($node->right, $key);
        }

        return $this->balance($node);
    }

    private function findMin($node) {
        while ($node->left) {
            $node = $node->left;
        }
        return $node;
    }

    private function eraseNode($node, $key) {
        if (!$node) return null;

        if ($key < $node->key) {
            $node->left = $this->eraseNode($node->left, $key);
        } elseif ($key > $node->key) {
            $node->right = $this->eraseNode($node->right, $key);
        } else {
            if (!$node->left) return $node->right;
            if (!$node->right) return $node->left;

            $minNode = $this->findMin($node->right);
            $node->key = $minNode->key;
            $node->right = $this->eraseNode($node->right, $minNode->key);
        }

        return $this->balance($node);
    }

    private function getNodeByIndex($node, $index) {
        $leftSize = $this->size($node->left);

        if ($index < $leftSize) {
            return $this->getNodeByIndex($node->left, $index);
        } elseif ($index > $leftSize) {
            return $this->getNodeByIndex($node->right, $index - $leftSize - 1);
        } else {
            return $node->key;
        }
    }

    private function lowerBoundNode($node, $key, $currentIndex) {
        if (!$node) return $currentIndex;

        if ($key <= $node->key) {
            return $this->lowerBoundNode($node->left, $key, $currentIndex);
        } else {
            $leftSize = $this->size($node->left);
            return $this->lowerBoundNode($node->right, $key, $currentIndex + $leftSize + 1);
        }
    }

    private function upperBoundNode($node, $key, $currentIndex) {
        if (!$node) return $currentIndex;

        if ($key < $node->key) {
            return $this->upperBoundNode($node->left, $key, $currentIndex);
        } else {
            $leftSize = $this->size($node->left);
            return $this->upperBoundNode($node->right, $key, $currentIndex + $leftSize + 1);
        }
    }

    public function insert($key) {
        $this->root = $this->insertNode($this->root, $key);
    }

    public function erase($key) {
        $this->root = $this->eraseNode($this->root, $key);
    }

    public function get($index) {
        if ($index < 0 || $index >= $this->size($this->root)) {
            throw new OutOfBoundsException("Index out of bounds");
        }
        return $this->getNodeByIndex($this->root, $index);
    }

    public function lower_bound($key) {
        return $this->lowerBoundNode($this->root, $key, 0);
    }

    public function upper_bound($key) {
        return $this->upperBoundNode($this->root, $key, 0);
    }
}
