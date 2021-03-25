<?php
$c = <<<CLANG
#include<stdio.h>
 
int n;
long long INF = 1e18;
long long dp[2000000][20];
long long mincost[20][20];
 
void init(long cnt){
    n = cnt;
    long bit = 1<<n;
    for(long i = 0; i < 2000000; i++){
        for(int j = 0; j < 20; j++){
            dp[i][j] = INF;
        }
    }
    for(int i = 0; i < 20; i++){
        for(int j = 0; j < 20; j++){
            mincost[i][j] = INF;
        }
    }
}
 
void setCost(int from, int to, long long cost){
    mincost[from][to] = cost;
}
 
void calc(){
    for(int i = 0; i < n; i++){
        dp[1<<i][i] = 0;
    }
    long bit = 1<<n;
    for(long i = 0; i < bit; i++){
        for(int j = 0; j < n; j++){
            long ij = i|(1<<j);
            // iにj番目のビットが立っていない
            if(~i & (1<<j)) {
                for(int k = 0; k < n; k++){
                    // iにk番目のビットが立っている
                    if(i & (1<<k)){
                        if(dp[ij][j] > dp[i][k] + mincost[k][j]){
                            dp[ij][j] = dp[i][k] + mincost[k][j];
                        }
                    }
                }
            }
        }
    }
}
 
long long getAns(int goal){
    long long ans = INF;
    long all = (1<<n) - 1;
    if(goal == -1){
        for(int i = 0; i < n; i++){
            if(ans > dp[all][i]){
                ans = dp[all][i];
            }
        }
    }else{
        if(ans > dp[all][goal]){
            ans =  dp[all][goal];
        }
    }
    if(ans == INF){
        ans = -1;
    }
    return ans;
}
CLANG;

file_put_contents("test.c", $c);
exec("gcc -fPIC -shared -o test.so test.c");

$ffi = FFI::cdef("
void init(long cnt);
void setCost(int from, int to, long long cost);
void calc();
long long getAns(int goal);
", __DIR__."/test.so");
