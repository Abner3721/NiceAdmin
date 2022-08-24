import createAxios from '/@/utils/axios'

export function dashboard() {
    return createAxios({
        url: '/index.php/admin/Dashboard/dashboard',
        method: 'get',
    })
}
export function visits(params: object = {}) {
    return createAxios({
        url: '/index.php/admin/Dashboard/visits',
        method: 'post',
        data:params 
    })as ApiPromise
}
