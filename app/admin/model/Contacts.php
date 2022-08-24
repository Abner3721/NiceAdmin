<?php

namespace app\admin\model;

use think\Model;

/**
 * Contacts
 * @controllerUrl 'articleContacts'
 */
class Contacts extends Model
{
    // 表名
    protected $name = 'contacts';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';



    public function getMsgAttr($value, $row)
    {
        return !$value ? '' : $value;
    }
}