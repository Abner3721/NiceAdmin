<?php
namespace app\admin\controller\ad;
use app\common\controller\Backend;
/**
 * banner位置管理
 *
 */
class Positions extends Backend
{
    /**
     * AdPositins模型对象
     * @var \app\admin\model\AdPositins
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'weigh,desc';
	protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\AdPositins;
    }
}