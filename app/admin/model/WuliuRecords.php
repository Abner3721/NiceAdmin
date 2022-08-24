<?php

namespace app\admin\model;

use app\common\service\ExpressService;
use think\Exception;
use think\Model;

class WuliuRecords extends Model
{
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function getJsonAttr($value)
    {
        if (empty($value)) return [];
        return json_decode($value,true);
    }

    public function getTypeAttr($value,$data){
        if ( empty($data['type']) ) return '-';
        $arr = ['JD'=>'京东','YUNDA56'=>'韵达','SFEXPRESS'=>'顺丰','YTO'=>'圆通','STO'=>'申通','ZT0'=>'中通','EMS'=>'EMS'];
        return $arr[$data['type']];
    }


    public function getWlSnAttr($value,$data)
    {
        if ( empty($data['wl_sn'])) return '-';
        $arr = explode(':', $data['wl_sn']);
        return $arr[0];
    }

    /**
     * 添加物流记录
     * @param $id 订单ID
     * @param $user_id 订单用户ID
     * @param $type 物流类型
     * @param $no 物流单号
     */
    public function addData($id, $user_id, $type, $sn){
        $res = (new ExpressService())->logistics($type, $sn);
        if (empty($res)) throw new Exception('没有识别出物流信息');
        if (isset($res['status']) &&  $res['status'] != 0 ) throw new Exception($res['msg']);
        if ($res['status'] != 0)   throw new Exception('请输入正确的物流单号');
        $time = time();
        $data = [
            'order_id'      => $id,
            'user_id'       => $user_id,
            'type'          => $type,
            'wl_sn'         => $sn,
            'json'          => json_encode($res['result']['list'], JSON_UNESCAPED_UNICODE),
            'updatetime'    => $time,
            'createtime'    => $time,
        ];
        $res = $this->insert($data);
        if ($res) return true;
        throw new Exception('物流添加异常');
    }


























}