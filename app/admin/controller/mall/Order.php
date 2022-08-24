<?php

namespace app\admin\controller\mall;

use app\admin\model\WuliuRecords;
use app\common\controller\Backend;
use think\facade\Db;

/**
 * 订单列管理
 *
 */
class Order extends Backend
{
    /**
     * Order模型对象
     * @var \app\admin\model\Order
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'id,desc';
	protected $withJoinTable = ['user','address'];
	protected $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Order;
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
        $res->visible(['user' => ['username']]);
        foreach ($res->items() as &$v) {
            $v->status_text         = $v->status_text;
            $v->pay_status_text     = $v->pay_status_text;
            $v->pay_type_text       = $v->pay_type_text;
            $v->order_type_text     = $v->order_type_text;
            $v->express_type_text   = $v->express_type_text;
            $v->express_status_text = $v->express_status_text;
        }

        $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }

    public function show() {
        $id = $this->request->param('id');
        if ( empty($id) ) $this->error('未获取到订单ID号');
        $info = $this->model->find($id);
        if ( empty($info)) $this->error('该订单不存在！');
        $info->status_text         = $info->status_text;
        $info->pay_status_text     = $info->pay_status_text;
        $info->pay_type_text       = $info->pay_type_text;
        $info->order_type_text     = $info->order_type_text;
        $info->express_type_text   = $info->express_type_text;
        $info->express_status_text = $info->express_status_text;
        $info->user;
        $info->visible(['user' => ['username']]);
        $info->address;
        $this->success('ok', $info);
    }


    /**
     * 发货
     */
    public function fahuo($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('该订单不不存在');
        if ($row['status'] == 2 ) $this->error('该订单已经发货');
        $row->status = 2;
        $res = $row->save();
        if ($res) $this->success('发货成功');
        $this->error('发货失败');
    }


    /**
     * 线下支付
     */
    public function offline_pay($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('该订单不不存在');
        if($row['pay_status'] == 1 ) $this->error('该订单已支付');
//        if ($this->request->isAjax()) {
            $row->status = 1;
            $row->pay_type = 4;
            $row->pay_time = time();
            $row->pay_status = 1;
            $save = $row->save();
            $save ? $this->success('下线支付成功') : $this->error('支付失败');
//        }
    }


    /**
     * @NodeAnotation(title="退款")
     */
    public function refund(){
        $param = $this->request->param();
        $row = $this->model->find($param['id']);
        empty($row) && $this->error('该订单不存在');
        if($row['status'] == 20 ) $this->error('该订单已退款');
        $row->status = 20;
        Db::startTrans();
        try {
            $save = $row->save();
            $this->model->revokeStokc($param['id']);
            Db::commit();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            Db::rollback();
        }

        $save ? $this->success('退款成功') : $this->error('退款失败');
    }


    /**
     * 完成
     */
    public function finish() {
        $post = $this->request->param();
        $row = $this->model->find($post['id']);
        empty($row) && $this->error('该订单不存在');
        if($row['status'] == 3 ) $this->errorMsg('该订单已完成');
        $save = $row->status = 3;
        $save ? $this->success('完成成功') : $this->error('完成失败');
    }


    /**
     * 根据订单ID查看物流信息
     * @param $id 订单ID
     */
    public function wuliu($id){
        $id = $this->request->param('id');
        if (empty($id)) $this->error('没有获取订单ID');
        $info = WuliuRecords::where('order_id', $id)->field('json,wl_sn, type')->find();
        if (empty($info)) $this->error('没有物流信息', [], 401);
        $this->success('ok', $info);
    }


    public function addWuliu() {
        if ( !$this->request->isPost() ) $this->error('请求方式错误');
        $param = $this->request->param();
        if ( empty($param['id']) ) $this->error('没有获取到订单ID号');
        if ( empty($param['express_no']) || empty($param['express_type'])) $this->error('快递单号或者快递不能为空');
        $info = $this->model->find($param['id']);
        if ( empty($info) ) $this->error('该订单不存在！');
        if ($info['express_status'] == 1) $this->error('该订单已经发货');
        $info->status           = 2;
        $info->express_status   =1;
        $info->express_no       = $param['express_no'];
        $info->express_type     = $param['express_type'];
        Db::startTrans();
        try {
            $res = $info->save();
            //添加物流记录
            (new WuliuRecords())->addData($param['id'], $info['user_id'], $param['express_type'], $param['express_no']);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ( $res ) $this->error('发货成功');
        $this->error('发货失败');

    }
}