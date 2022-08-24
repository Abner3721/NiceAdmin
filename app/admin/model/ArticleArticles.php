<?php

namespace app\admin\model;

use think\Model;

/**
 * ArticleArticles
 * @controllerUrl 'articleArticles'
 */
class ArticleArticles extends Model
{
    // 表名
    protected $name = 'article_articles';

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

    public function getMenuIdAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getDescriptionAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getContentAttr($value, $row)
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }

    public function getImgurlsAttr($value, $row)
    {
        if ($value == '') {
            return [];
        }
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setImgurlsAttr($value, $row)
    {
        if ($value && is_array($value)) {
            return implode(',', $value);
        }
        return $value ? $value : '';
    }

    public function articlemenus()
    {
        return $this->belongsTo(ArticleMenus::class, 'menu_id', 'id');
    }
}