import createAxios from '/@/utils/axios'
import { getAdminToken } from '/@/utils/common'

const controllerUrl = '/index.php/admin/index/'



export function index() {
    return createAxios({
        url: controllerUrl + 'index',
        method: 'get',
    })
}

export function login(method: 'get' | 'post', params: object = {}): ApiPromise {
    return createAxios({
        url: controllerUrl + 'login',
        data: params,
        method: method,
    }) as ApiPromise
}

export function logout() {
    return createAxios({
        url: controllerUrl + 'logout',
        method: 'POST',
        data: {
            refresh_token: getAdminToken('refresh'),
        },
    })
}

// 访问量
export function getview() {
    return createAxios({
        url: '/index.php/api/index/view',
        method: 'get',
    })as ApiPromise
}
// 获取登录背景图
export function getBgImage() {
    return createAxios({
        url: '/index.php/admin/index/getBgImage',
        method: 'get',
    })as ApiPromise
}
// 发货
export function addWuliu(params:object) {
    return createAxios({
        url: '/index.php/admin/mall.order/addWuliu',
        method: 'post',
        data:params
    })as ApiPromise
}
// 查询订单详情
export function ordershow(params:object) {
    return createAxios({
        url: '/index.php/admin/mall.order/show?id='+params,
        method: 'get',
        data:params
    })as ApiPromise
}
// 查询物流
export function wuliu(params:object) {
    return createAxios({
        url: '/index.php/admin/mall.order/wuliu?id='+params,
        method: 'get',
        data:params
    })as ApiPromise
}
// 获取子订单接口
export function orderItem(params: Number) { 
    return createAxios({
        url: '/index.php/admin/mall.orderItem/index?id='+params,
        method: 'get', 
    })as ApiPromise
}
// 线下支付
export function offline_pay(params: Number) { 
    return createAxios({
        url: '/index.php/admin/mall.order/offline_pay?id='+params,
        method: 'get', 
    })as ApiPromise
}
// 完成操作
export function finish(params: Number) { 
    return createAxios({
        url: '/index.php/admin/mall.order/finish?id='+params,
        method: 'get', 
    })as ApiPromise
}
// 退款
export function refund(params: Number) { 
    return createAxios({
        url: '/index.php/admin/mall.order/refund?id='+params,
        method: 'get', 
    })as ApiPromise
}