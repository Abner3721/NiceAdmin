<?php

namespace app\api\controller;

use think\facade\Config;
use app\common\controller\Frontend;
use think\facade\Db;

class Index extends Frontend
{
    protected $noNeedLogin = ['index', 'view'];

    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
          
        $this->success('', [
            'site'               => [
                'site_name'     => get_sys_config('site_name'),
                'record_number' => get_sys_config('record_number'),
                'version'       => get_sys_config('version'),
            ],
            'open_member_center' => Config::get('niceadmin.open_member_center'),
        ]);
    }
    
    
    public function view() {
        $day  = date('Y-m-d');
        $info = Db::name('visits')->whereDay('createtime', $day)->find();

        if ( empty($info ) ) {
            $data = [
                'view'          => 1,
                'createtime'    => time(),
            ];
            $res  = Db::name('visits')->insert($data);
        } else {
            $res = Db::name('visits')->whereDay('createtime', $day)->inc('view')->update();
        }
        if ($res) $this->success('ok');
        $this->error('访问失败');
    }

}