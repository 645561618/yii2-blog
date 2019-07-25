<?php

namespace common\components;

use Yii;
use yii\redis\Connection;

class RedisConnection extends Connection {
    public $redis = 'redis';

    public function exists ($key)
    {
        return  (bool) $this->executeCommand('EXISTS', [$key]);
    }


    /**
     * @inheritdoc
     */
    public function getValue($key)
    {
        return $this->executeCommand('GET', [$key]);
    }

    /**
     * @inheritdoc
     */
    public function getTtl($key)
    {
        return $this->executeCommand('TTL', [$key]);
    }

    /**
     * [sadd description]
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function sAdd($key,$value){
        return $this->executeCommand('SADD',[$key,$value]);
    }

    /**
     * [getSmembers description]
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function getSmembers($key){
        return $this->executeCommand('SMEMBERS',[$key]);
    }

    public function sRem($key,$value){
        return $this->executeCommand('SREM',[$key,$value]);
    }

    public function sIsmember($key,$value){
        return $this->executeCommand('SISMEMBER',[$key,$value]);
    }

    public function sPop($key){
        return $this->executeCommand('SPOP',[$key]);
    }

    public function sCard($key){
        return $this->executeCommand('SCARD',[$key]);
    }

    public function set($key,$value){
        return $this->executeCommand('SET',[$key,$value]);
    }

    /**
     * @inheritdoc,设置到期时间
     */
    public function setValue($key, $value, $expire)
    {
        $this->executeCommand('SET', [$key, $value]);
        return (bool) $this->executeCommand('EXPIRE',[$key,$expire]);
    }

    /**
     * [LPUSH 列表类型--左侧压入]
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function lPush($key,$value){
        return $this->executeCommand('LPUSH',[$key,$value]);
    }
    
    //列表--左侧弹出
    public function lPop($key){
        return $this->executeCommand('LPOP',[$key]);
    }
    //列表类型--右侧压入
    public function rPush($key,$value){
        return $this->executeCommand('RPUSH',[$key,$value]);
    }
    //列表类型--右侧弹出
    public function rPop($key){
        return $this->executeCommand('RPOP',[$key]);
    }
    //列表类型--显示个数
    public function lLen($key){
        return $this->executeCommand('LLEN',[$key]);
    }

    /**
     * [LRANGE 列表类型--显示范围]
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [array]        [description]
     */
    public function lRange($key){
        return $this->executeCommand('LRANGE',[$key,"0","-1"]);
    }



    //哈希类型--获取所有信息
    public function hgetAll($key)
    {
        return $this->executeCommand('HVALS',[$key]);
    }


	





    /**
     * [LREM description]
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function lRem($key,$value){
        return $this->executeCommand('LREM',[$key,"0",$value]);
    }

    /**
     * [DECRBY description]
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function decrby($key,$decrement){
        return $this->executeCommand('DECRBY',[$key,$decrement]);
    }

    /**
     * [INCR description]
     * @param  [type] $key   [description]
     */
    public function incr($key){
        return $this->executeCommand('INCR',[$key]);
    }
}
