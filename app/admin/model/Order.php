<?php

namespace app\admin\model;

use think\Exception;
use think\Model;

/**
 * 订单详情数据层
 * @controllerUrl 'mallOrder'
 */
class Order extends Model
{
    // 表名
    protected $name = 'order';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

	protected $type = [
		'pay_time'      => 'timestamp:Y-m-d H:i:s',
		'validity_time' => 'timestamp:Y-m-d H:i:s',
		'cancel_time'   => 'timestamp:Y-m-d H:i:s',
		'cli_del_time'  => 'timestamp:Y-m-d H:i:s',
	];


    public function getUserIdAttr($value, $row)
    {
        return !$value ? '' : $value;
    }

    public function getInvoiceIdAttr($value, $row)
    {
        return !$value ? '' : $value;
    }


    public function getStatusTextAttr($value, $data) {
        $arr = ['20'=>'退款', '10'=>'取消', '0'=>'待支付', '1'=>'已支付', '2'=>'待收货', '3'=>'完成'];
        return $arr[$data['status']];
    }

    public function getPayStatusTextAttr($value, $data) {
        $arr = [ '10'=>'支付失败', '0'=>'待支付', '1'=>'支付成功'];
        return $arr[$data['pay_status']];
    }

    public function getPayTypeTextAttr($value, $data) {
        $arr = ['0'=>'-','1'=>'微信','2'=>'支付宝','3'=>'积分','4'=>'线下'];
        return $arr[$data['pay_type']];
    }

    public function getOrderTypeTextAttr($value, $data) {
        $arr = ['1'=>'商品','2'=>'积分','3'=>'下线'];
        return $arr[$data['order_type']];
    }

    public function getExpressTypeTextAttr($value, $data) {
        if ( empty($data['express_type']) ) return '-';
        $arr = ['JD'=>'京东','YUNDA56'=>'韵达','SFEXPRESS'=>'顺丰','YTO'=>'圆通','STO'=>'申通','ZT0'=>'中通','EMS'=>'EMS'];
        return $arr[$data['express_type']];
    }

    public function getExpressStatusText($value,$data) {
        if ( empty($data['express_status']) ) return '-';
        $arr = ['0'=>'待发货','1'=>'已发货','2'=>'运输中','3'=>'已签收'];
        return $arr[$data['express_status']];
    }




    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(OrderAddress::class, 'id', 'order_id');
    }


    /**
     * 撤销库存
     * @param $id
     */
    public function revokeStokc($id) {
        $res = (new OrderItem())->where('order_id', $id)->field('goods_id, num')->select();
        if (empty($res)) throw  new Exception('没有子订单');
        $goodsObj = new MallGoods();
        foreach($res as $v) {
            $res = $goodsObj->where('id', $v['goods_id'])->inc('stock', $v['num'])->update();
        }
        return true;

    }
}