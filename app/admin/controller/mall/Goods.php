<?php

namespace app\admin\controller\mall;

use app\common\controller\Backend;

/**
 * 商品列管理
 *
 */
class Goods extends Backend
{
    /**
     * MallGoods模型对象
     * @var \app\admin\model\MallGoods
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'weigh,desc';
	protected $withJoinTable = ['mallmenus'];
	protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\MallGoods;
    }

    public function add()
    {
        $this->request->filter('trim,htmlspecialchars');
        parent::add();
    }

    public function edit($id = null)
    {
        $this->request->filter('trim,htmlspecialchars');
        $param = $this->request->param();
        if ( isset($param['total_stock']) && ($param['total_stock'] < 0 || $param['stock'] < 0) ) $this->error('库存不能小于0');
        parent::edit($id);
    }

    /**
     * 查看
     */
    public function index()
    {
        // 设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        // 如果是select则转发到select方法,若select未重写,其实还是继续执行index
        if ($this->request->param('select')) {
            $this->select();
        }
        list($where, $alias, $limit, $order) = $this->queryBuilder();
        $res = $this->model
            ->withJoin($this->withJoinTable, $this->withJoinType)
            ->alias($alias)
            ->where($where)
            ->order($order)
            ->paginate($limit);
        $res->visible(['mallmenus' => ['title']]);
        $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }
}