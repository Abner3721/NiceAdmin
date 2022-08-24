<?php
namespace app\admin\controller\article;
use app\common\controller\Backend;

/**
 * 联系我们
 *
 */
class Contacts extends Backend
{
    /**
     * Contacts模型对象
     * @var \app\admin\model\Contacts
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'id,desc';
	protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Contacts;
    }

}