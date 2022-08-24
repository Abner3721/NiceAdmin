<?php
namespace app\admin\controller\links;
use app\common\controller\Backend;
/**
 * 友情链接管理
 *
 */
class Index extends Backend
{
    /**
     * Links模型对象
     * @var \app\admin\model\Links
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'weigh,desc';
	protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Links;
    }

}