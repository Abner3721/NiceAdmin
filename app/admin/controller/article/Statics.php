<?php
namespace app\admin\controller\article;
use app\common\controller\Backend;
/**
 * 静态内容
 *
 */
class Statics extends Backend
{
    /**
     * Statics模型对象
     * @var \app\admin\model\Statics
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'id,desc';
	protected $preExcludeFields = ['createtime', 'updatetime'];
    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Statics;
    }

    public function add()
    {
        $this->request->filter('trim,htmlspecialchars');
        parent::add();
    }

    public function edit($id = null)
    {
        $this->request->filter('trim,htmlspecialchars');
        parent::edit($id);
    }
}