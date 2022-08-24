<?php
namespace app\admin\controller\ad;
use app\common\controller\Backend;
/**
 * banner列表管理
 *
 */
class Ads extends Backend
{
    /**
     * AdAds模型对象
     * @var \app\admin\model\AdAds
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'weigh,desc';
	protected $withJoinTable = ['adpositions'];
	protected $preExcludeFields = ['createtime', 'updatetime'];
    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\AdAds;
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
        $res->visible(['adpositions' => ['title']]);
        $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }
}