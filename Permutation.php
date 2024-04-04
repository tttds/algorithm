<?php

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
