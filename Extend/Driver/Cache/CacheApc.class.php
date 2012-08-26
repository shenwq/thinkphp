<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * Apc缓存驱动类
 */
class CacheApc extends Cache {

    /**
     * 架构函数
     * @access public
     */
    public function __construct($options='') {
        if(!function_exists('apc_cache_info')) {
            throw_exception(L('_NOT_SUPPERT_').':Apc');
        }
        if(!empty($options)) {
            $this->options =  $options;
        }
        $this->options['expire'] = isset($options['expire'])?$options['expire']:C('DATA_CACHE_TIME');
        $this->options['length']  =  isset($options['length'])?$options['length']:0;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
     public function get($name) {
        N('cache_read',1);
         return apc_fetch($name);
     }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param integer $expire  有效时间（秒）
     * @return boolen
     */
     public function set($name, $value, $expire = null) {
        N('cache_write',1);
        if(is_null($expire)) {
            $expire  =  $this->options['expire'];
        }
        if($result = apc_store($name, $value, $expire)) {
            if($this->options['length']>0) {
                // 记录缓存队列
                $this->queue($name);
            }
        }
        return $result;
     }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolen
     */
     public function rm($name) {
         return apc_delete($name);
     }

    /**
     * 清除缓存
     * @access public
     * @return boolen
     */
    public function clear() {
        return apc_clear_cache();
    }

}