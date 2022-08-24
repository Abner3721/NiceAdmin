<?php

namespace app\admin\model;

use think\Model;

/**
 * Comments
 * @controllerUrl 'articleComments'
 */
class Comments extends Model
{
    // 表名
    protected $name = 'comments';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

	protected $type = [
		'delete_time' => 'timestamp:Y-m-d H:i:s',
	];


    public function getArticlesIdAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getUserIdAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getContentAttr($value, $row)
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }

    public function articlearticles()
    {
        return $this->belongsTo(ArticleArticles::class, 'articles_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}