<?php

namespace app\admin\model;

use think\Model;

/**
 * 静态内容管理数据层
 * @controllerUrl 'articleStatics'
 */
class Statics extends Model
{
    // 表名
    protected $name = 'statics';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';



    public function getContentAttr($value, $row)
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }
}