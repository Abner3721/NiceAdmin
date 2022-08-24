<?php

namespace app\admin\model;

use think\Model;

/**
 * 商品分类数据层
 * @controllerUrl 'mallMenus'
 */
class MallMenus extends Model
{
    // 表名
    protected $name = 'mall_menus';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    protected static function onAfterInsert($model)
    {
        if ($model->weigh == 0) {
            $pk = $model->getPk();
            $model->where($pk, $model[$pk])->update(['weigh' => $model[$pk]]);
        }
    }

    public function getDescriptionAttr($value, $row)
    {
        return !$value ? '' : $value;
    }
}