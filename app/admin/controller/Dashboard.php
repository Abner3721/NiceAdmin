<?php
namespace app\admin\controller;
use app\admin\model\ArticleArticles;
use app\admin\model\MallGoods;
use app\admin\model\Order;
use app\admin\model\User;
use app\common\controller\Backend;
use app\common\model\Attachment;
use think\facade\Db;

class Dashboard extends Backend
{
    public function dashboard()
    {
        $data = [
            'quick' => $this->quick(),
            'view'  => $this->view(),
            'user'  => $this->user(),
            'order' => $this->order(),
            'revenue' => $this->revenue(),
        ];
        $this->success('',  $data);
    }

    /**
     * 访问量
     */
    public function visits()
    {
        $param = $this->request->param();
        $day       = isset($param['day']) ? $param['day'] : 7;
        $endTime   = date('Y-m-d H:i:s');
        $startTime = date('Y-m-d', strtotime("-{$day} day"));
        if ( !empty($param['day']) ) {
            $endTime   = date('Y-m-d H:i:s');
            $startTime = date('Y-m-d', strtotime("-{$day} day"));
        }
        if ( !empty($param['start_time']) ) $startTime = $param['start_time'];
        if ( !empty($param['end_time']) )   $endTime   = date('Y-m-d', strtotime("{$param['end_time']} +1 day"));
        $res = Db::name('visits')->whereTime('createtime', '>=', $startTime)->whereTime('createtime', '<=', $endTime)->field('id, view, createtime')->order('createtime', 'asc')->select();
        $row = [];
        foreach ($res as $v) {
            $row[] = [
                'createtime' => date('Y-m-d', $v['createtime']),
                'view'       => $v['view'],
            ];
        }
        $this->success('ok', $row);
    }


    /**
     * 获取快捷入口
     */
    private function quick()
    {
        $where = [
            ['quick', '=', 1],
            ['component', '<>', '' ],
            ['status', '=', 1]
        ];
        $res = Db::name('menu_rule')->field('id, title, icon, name, path')->where($where)->select();
        return $res;
    }

    /**
     * 浏览量
     * @return array
     * @throws \think\db\exception\DbException
     */
    private function view()
    {
        $total        = Db::name('visits')->sum('view');
        $TDBYesterday    = date('Y-m-d',strtotime("-2  day"));
        $yesterdayNum = Db::name('visits')->whereDay('createtime', 'yesterday')->sum('view');
        $TDBYesterdayNum = Db::name('visits')->whereDay('createtime',$TDBYesterday)->sum('view');
        if ($yesterdayNum > $TDBYesterdayNum ) {
            $diff   = $yesterdayNum - $TDBYesterdayNum;
            $symbol = '+';
        } else if ($yesterdayNum < $TDBYesterdayNum ) {
            $diff   = $TDBYesterdayNum - $yesterdayNum;
            $symbol = '-';
        } else {
            $diff = 0;
            $symbol = '0';
        }
        $data = [
            'total' => $total,
            'diff'  => $diff,
            'symbol'=> $symbol,
        ];
        return $data;
    }

    /**
     * 用户数量
     * @return array
     * @throws \think\db\exception\DbException
     */
    private function user() {
        $total = Db::name('user')->count('id');

        $TDBYesterday    = date('Y-m-d',strtotime("-2  day"));
        $yesterdayNum = Db::name('user')->whereDay('createtime', 'yesterday')->count('id');
        $TDBYesterdayNum = Db::name('user')->whereDay('createtime',$TDBYesterday)->count('id');
        if ($yesterdayNum > $TDBYesterdayNum ) {
            $diff   = $yesterdayNum - $TDBYesterdayNum;
            $symbol = '+';
        } else if ($yesterdayNum < $TDBYesterdayNum ) {
            $diff   = $TDBYesterdayNum - $yesterdayNum;
            $symbol = '-';
        } else {
            $diff = 0;
            $symbol = '0';
        }
        $data = [
            'total' => $total,
            'diff'  => $diff,
            'symbol'=> $symbol,
        ];
        return $data;
    }

    /**
     * 订单总数
     * @return array
     * @throws \think\db\exception\DbException
     */
    private function order() {
        $total = Db::name('order')->where('pay_status', 1)->count('id');

        $TDBYesterday    = date('Y-m-d',strtotime("-2  day"));
        $yesterdayNum = Db::name('order')->where('pay_status', 1)->whereDay('createtime', 'yesterday')->count('id');
        $TDBYesterdayNum = Db::name('order')->where('pay_status', 1)->whereDay('createtime',$TDBYesterday)->count('id');
        if ($yesterdayNum > $TDBYesterdayNum ) {
            $diff   = $yesterdayNum - $TDBYesterdayNum;
            $symbol = '+';
        } else if ($yesterdayNum < $TDBYesterdayNum ) {
            $diff   = $TDBYesterdayNum - $yesterdayNum;
            $symbol = '-';
        } else {
            $diff = 0;
            $symbol = '0';
        }
        $data = [
            'total' => $total,
            'diff'  => $diff,
            'symbol'=> $symbol,
        ];
        return $data;
    }

    /**
     * 收入总数
     * @return array
     * @throws \think\db\exception\DbException
     */
    private function revenue() {
        $total = Db::name('order')->where('pay_status', 1)->sum('total_price');
        $TDBYesterday    = date('Y-m-d',strtotime("-2  day"));
        $yesterdayNum = Db::name('order')->where('pay_status', 1)->whereDay('createtime', 'yesterday')->sum('total_price');
        $TDBYesterdayNum = Db::name('order')->where('pay_status', 1)->whereDay('createtime',$TDBYesterday)->sum('total_price');
        if ($yesterdayNum > $TDBYesterdayNum ) {
            $diff   = $yesterdayNum - $TDBYesterdayNum;
            $symbol = '+';
        } else if ($yesterdayNum < $TDBYesterdayNum ) {
            $diff   = $TDBYesterdayNum - $yesterdayNum;
            $symbol = '-';
        } else {
            $diff = 0;
            $symbol = '0';
        }
        $data = [
            'total' => $total,
            'diff'  => $diff,
            'symbol'=> $symbol,
        ];
        return $data;
    }
}