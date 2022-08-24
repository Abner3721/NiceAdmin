<?php

namespace app\admin\model;

use think\Model;

/**
 * SEO数据层
 * @controllerUrl 'seosIndex'
 */
class Seos extends Model
{
    // 表名
    protected $name = 'seos';
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

    public function getTitleAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getKeywordAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getDescriptionAttr($value, $row)
    {
        return !$value ? '' : $value;
    }
}