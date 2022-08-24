<?php

namespace app\admin\model;

use think\Model;

/**
 * AdPositins
 * @controllerUrl 'adPositions'
 */
class AdPositins extends Model
{
    // 表名
    protected $name = 'ad_positions';

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

}