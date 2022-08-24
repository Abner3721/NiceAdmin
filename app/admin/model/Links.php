<?php

namespace app\admin\model;

use think\Model;

/**
 * 友情链接数据层
 * @controllerUrl 'linksIndex'
 */
class Links extends Model
{
    // 表名
    protected $name = 'links';

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