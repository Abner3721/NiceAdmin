<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'comSearch']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('auth.adminLog.title') })"
            @action="baTable.onTableHeaderAction"
        />
        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table @action="baTable.onTableAction" />

        <Info />


    </div>
</template>

<script setup lang="ts">
import _ from 'lodash'
import { onMounted, provide } from 'vue'
import baTableClass from '/@/utils/baTable'
import { mallOrder } from '/@/api/controllerUrls'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import useCurrentInstance from '/@/utils/useCurrentInstance'
import { useI18n } from 'vue-i18n'
import Info from './info.vue'
import { buildJsonToElTreeData } from '/@/utils/common'
import {i18n} from "/@/lang";
import { faHuo ,orderItem,ordershow} from '/@/api/backend'
import { ElNotification,ElLoading  } from 'element-plus'
const { t } = useI18n()

let optButtons: OptButton[] = [
    {
        render: 'tipButton',
        name: 'info',
        title: '',
        text: '查看详情',
        type: 'primary',
        icon: '',
        class: 'table-row-edit',
        disabledTip: false,
    },

]

optButtons = _.concat(optButtons, defaultOptButtons([]))

const baTable = new baTableClass(
    new baTableApi(mallOrder),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('mall.order.id'), prop: 'id', align: 'center', width: 70, sortable: 'custom', operator: 'RANGE' },
            { label: t('mall.order.user__username'), prop: 'user.username', align: 'center' },
            { label: t('mall.order.order_no'), prop: 'order_no',width:200, align: 'center' },
            { label: t('mall.order.transaction_no'), prop: 'transaction_no', align: 'center' ,show:false},
            { label: t('mall.order.order_type'), prop: 'order_type', align: 'center', render: 'tag', replaceValue: { 1: t('mall.order.order_type 1'), 2: t('mall.order.order_type 2'), 3: t('mall.order.order_type 3') } , width: 100},
            { label: t('mall.order.total_price'), prop: 'total_price', align: 'center', operator: 'RANGE' },
            { label: t('mall.order.pay_type'), prop: 'pay_type', align: 'center', render: 'tag', replaceValue: { 0: t('mall.order.pay_type 0'), 1: t('mall.order.pay_type 1'), 2: t('mall.order.pay_type 2'), 3: t('mall.order.pay_type 3'), 4: t('mall.order.pay_type 4') }, width: 100 },
            { label: t('mall.order.pay_status'), prop: 'pay_status', align: 'center', render: 'tag', replaceValue: { 10: t('mall.order.pay_status 10'), 0: t('mall.order.pay_status 0'), 1: t('mall.order.pay_status 1') } , width: 100},
            { label: t('mall.order.pay_time'), prop: 'pay_time', align: 'center', render: 'datetime', sortable: 'custom', operator: 'RANGE', width: 160 ,show:false},
            { label: t('mall.order.type'), prop: 'type', align: 'center', render: 'tag', replaceValue: { 1: t('mall.order.type 1'), 2: t('mall.order.type 2') }},
            { label: t('mall.order.status'), prop: 'status', align: 'center', render: 'tag', replaceValue: { 20: t('mall.order.status 20'), 10: t('mall.order.status 10'), 0: t('mall.order.status 0'), 1: t('mall.order.status 1'), 2: t('mall.order.status 2'), 3: t('mall.order.status 3') } , width: 100},
            { label: t('mall.order.points'), prop: 'points', align: 'center', operator: 'RANGE' ,show:false},
            { label: t('mall.order.express_no'), prop: 'express_no', align: 'center' ,show:false},
            { label: t('mall.order.createtime'), prop: 'createtime', align: 'center', render: 'datetime', sortable: 'custom', operator: 'RANGE', width: 180 },
            { label: t('operate'), align: 'center', render: 'buttons', buttons: optButtons, operator: false, width: 120  },
        ],
        dblClickNotEditColumn: [undefined, ],
        defaultOrder: { prop: 'id', order: 'desc' },
    },
    {},
    {
        onTableDblclick: ({ row }: { row: TableRow }) => {
            infoButtonClick(row)
            return false
        },
    }
)

baTable.mount()
baTable.getIndex()

provide('baTable', baTable)

/** 点击查看详情按钮响应 */
const infoButtonClick = (row: TableRow) => {
    if (!row) return
       let loading = ElLoading.service({
        lock: true,
        text: 'Loading',
        // background: 'rgba(0, 0, 0, 0.5)',
    })
    // 数据来自表格数据,未重新请求api,深克隆,不然可能会影响表格
    let rowClone = _.cloneDeep(row)
    rowClone.data = rowClone.data ? [{ label: '点击展开', children: buildJsonToElTreeData(JSON.parse(rowClone.data)) }] : []
     ordershow(rowClone.id).then((res)=>{
        baTable.form.extend!['info'] = res.data
        orderItem(rowClone.id).then((res) => {
            baTable.form.extend!['goodsdata'] = res.data
            // baTable.form.extend!['info'] = rowClone
            baTable.form.operate = 'info'
            baTable.form.fahuo = ''
            loading.close()
        })
        })

}
onMounted(() => {
    const { proxy } = useCurrentInstance()
    /**
     * 表格内的按钮响应
     * @param name 按钮name
     * @param row 被操作行数据
     */
    proxy.eventBus.on('onTableButtonClick', (data: { name: string; row: TableRow }) => {
        if (!baTable.activate) return
        if (data.name == 'info') {
            infoButtonClick(data.row)
        }
    })

})
</script>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'mall/order',
})
</script>

<style scoped lang="scss"></style>
