<?php

namespace app\admin\model;

use think\Model;

/**
 * 广告管理数据层
 * @controllerUrl 'adAds'
 */
class AdAds extends Model
{
    // 表名
    protected $name = 'ad_ads';

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

    public function getPositionsIdAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getDescriptionAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function adpositions()
    {
        return $this->belongsTo(AdPositions::class, 'positions_id', 'id');
    }
}