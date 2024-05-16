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
