<?php

namespace app\admin\controller\article;

use app\common\controller\Backend;

/**
 * 评论列管理
 *
 */
class Comments extends Backend
{
    /**
     * Comments模型对象
     * @var \app\admin\model\Comments
     */
    protected $model = null;
	protected $quickSearchField = ['id'];
	protected $defaultSortField = 'id,desc';
	protected $withJoinTable = ['articlearticles', 'user'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new \app\admin\model\Comments;
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
        $res->visible(['articlearticles' => ['title']]);
		$res->visible(['user' => ['username']]);
        $this->success('', [
            'list'   => $res->items(),
            'total'  => $res->total(),
            'remark' => get_route_remark(),
        ]);
    }
}