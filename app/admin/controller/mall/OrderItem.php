<?php

namespace app\admin\controller\mall;

use app\common\controller\Backend;

class OrderItem extends Backend
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->model = new \app\admin\model\OrderItem();
    }

    public function index(){
        $id = $this->request->param('id');
        if (empty($id)) $this->error('没有订单ID号');
        $list = $this->model->where('order_id', $id)->select();
        $this->success('ok', $list);
    }

}