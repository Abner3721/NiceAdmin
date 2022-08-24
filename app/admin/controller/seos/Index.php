<?php

namespace app\admin\controller\seos;

use app\common\controller\Backend;

/**
 * seo管理
 *
 */
class Index extends Backend
{
    /**
     * Seos模型对象
     * @var \app\admin\model\Seos
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'weigh,desc';
	protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Seos;
    }

}