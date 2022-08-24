<?php

namespace app\admin\controller\mall;

use app\common\controller\Backend;
use ba\Tree;
/**
 * 商品分类
 *
 */
class Menus extends Backend
{
    /**
     * ArticleMenus模型对象
     * @var \app\admin\model\MallMenus
     */
    protected $tree = null;
    protected $model = null;
    protected $keyword = false;
    protected $quickSearchField = ['id'];
    protected $defaultSortField = 'weigh,desc';
    protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->tree  = Tree::instance();
        $this->model = new \app\admin\model\MallMenus;
    }

    public function index()
    {
        if ($this->request->param('select')) {
            $this->select();
        }
        $this->success('', [
            'list'   => $this->getMenus(),
            'remark' => get_route_remark(),
        ]);
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

    protected function getMenus($where = [])
    {
        $rules = $this->getRuleList($where);
        return $this->tree->assembleChild($rules);
    }

    protected function getRuleList($where = [])
    {
        $ids = $this->auth->getRuleIds();
        // 如果没有 * 则只获取用户拥有的规则
        if (!in_array('*', $ids)) {
            $where[] = ['id', 'in', $ids];
        }
        if ($this->keyword) {
            $keyword = explode(' ', $this->keyword);
            foreach ($keyword as $item) {
                $where[] = [$this->quickSearchField, 'like', '%' . $item . '%'];
            }
        }

        // 读取用户组所有权限规则
        $rules = $this->model
            ->where($where)
            ->order('weigh desc,id asc')
            ->select();
        return $rules;
    }

    /**
     * 重写select方法
     */
    public function select()
    {
        $isTree = $this->request->param('isTree');
        $data   = $this->getMenus([['status', '=', '1']]);
        if ($isTree && !$this->keyword) {
            $data = $this->tree->assembleTree($this->tree->getTreeArray($data, 'title'));
        }
        $this->success('', [
            'options' => $data
        ]);
    }

}