<?php

class Trie {
    /**
     * @var object Trieのルートノード
     */
    private $root;

    /**
     * @var int Trieに挿入された単語の総数
     */
    private $totalWordCount;

    /**
     * Trieを初期化するコンストラクタ
     */
    public function __construct() {
        $this->root = $this->createNode();
        $this->totalWordCount = 0;
    }

    /**
     * 新しいTrieNodeを作成するメソッド
     *
     * @return object 新しく作成されたTrieNode
     */
    private function createNode() {
        return new class {
            /**
             * @var array TrieNodeの子ノード
             */
            public $children = [];

            /**
             * @var int このTrieNodeで終わる単語の数
             */
            public $count = 0;

            /**
             * TrieNodeを初期化するコンストラクタ
             */
            public function __construct() {
                $this->children = [];
                $this->count = 0;
            }
        };
    }

    /**
     * 単語をTrieに挿入するメソッド
     *
     * @param string $word 挿入する単語
     */
    public function insert($word) {
        $node = $this->root;
        $length = strlen($word);
        for ($i = 0; $i < $length; $i++) {
            $char = $word[$i];
            if (!isset($node->children[$char])) {
                $node->children[$char] = $this->createNode();
            }
            $node = $node->children[$char];
        }
        $node->count++;
        $this->totalWordCount++;
    }

    /**
     * 単語をTrieから1件削除するメソッド
     *
     * @param string $word 削除する単語
     * @return bool 単語が存在して削除できた場合はtrue、存在しない場合はfalse
     */
    public function delete($word) {
        $node = $this->root;
        $length = strlen($word);

        // 削除対象の単語までの経路を保存する
        $path = [];

        for ($i = 0; $i < $length; $i++) {
            $char = $word[$i];

            if (!isset($node->children[$char])) {
                return false;
            }

            // 現在のノードと文字を保存
            $path[] = [$node, $char];

            $node = $node->children[$char];
        }

        // その単語が存在しない場合
        if ($node->count <= 0) {
            return false;
        }

        // 単語の出現回数を1減らす
        $node->count--;
        $this->totalWordCount--;

        /*
        * 不要になったノードを後ろから削除する。
        *
        * 例えば
        *   cat
        *   car
        *
        * が登録されていて cat を削除した場合、
        * c -> a -> t
        * の t だけ削除する。
        */
        for ($i = count($path) - 1; $i >= 0; $i--) {
            [$parent, $char] = $path[$i];
            $child = $parent->children[$char];

            // 子ノードに単語がなく、さらに子ノードもない場合は不要
            if ($child->count === 0 && empty($child->children)) {
                unset($parent->children[$char]);
            } else {
                // これ以上上のノードを削除する必要はない
                break;
            }
        }

        return true;
    }

    /**
     * 単語がTrieに存在するか検索するメソッド
     *
     * @param string $word 検索する単語
     * @return bool 単語が存在すればtrue、そうでなければfalse
     */
    public function search($word) {
        $node = $this->root;
        $length = strlen($word);
        for ($i = 0; $i < $length; $i++) {
            $char = $word[$i];
            if (!isset($node->children[$char])) {
                return false;
            }
            $node = $node->children[$char];
        }
        return $node != null && $node->count > 0;
    }

    /**
     * 指定されたプレフィックスで始まる単語がTrieに存在するかをチェックするメソッド
     *
     * @param string $prefix 検索するプレフィックス
     * @return bool プレフィックスで始まる単語が存在すればtrue、そうでなければfalse
     */
    public function startsWith($prefix) {
        $node = $this->root;
        $length = strlen($prefix);
        for ($i = 0; $i < $length; $i++) {
            $char = $prefix[$i];
            if (!isset($node->children[$char])) {
                return false;
            }
            $node = $node->children[$char];
        }
        return true;
    }

    /**
     * Trieに挿入された全単語の総数を返すメソッド
     *
     * @return int 全単語の総数
     */
    public function getTotalWordCount() {
        return $this->totalWordCount;
    }

    /**
     * 特定の単語の出現回数を返すメソッド
     *
     * @param string $word 出現回数を取得する単語
     * @return int 単語の出現回数
     */
    public function getWordFrequency($word) {
        $node = $this->root;
        $length = strlen($word);
        for ($i = 0; $i < $length; $i++) {
            $char = $word[$i];
            if (!isset($node->children[$char])) {
                return 0;
            }
            $node = $node->children[$char];
        }
        return $node->count;
    }
}
