<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('mall.goods.quick Search Fields') })"
            @action="baTable.onTableHeaderAction"
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" @action="baTable.onTableAction" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { mallGoods } from '/@/api/controllerUrls'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(["weigh-sort","edit","delete"])
const baTable = new baTableClass(
    new baTableApi(mallGoods),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('mall.goods.id'), prop: 'id', align: 'center', width: 70, sortable: 'custom', operator: 'RANGE' },
            { label: t('mall.goods.title'), prop: 'title', align: 'center' },
            { label: t('mall.goods.mallmenus__title'), prop: 'mallmenus.title', align: 'center' },
            { label: t('mall.goods.market_price'), prop: 'market_price', align: 'center', operator: 'RANGE' },
            { label: t('mall.goods.price'), prop: 'price', align: 'center', operator: 'RANGE' },
            { label: t('mall.goods.stock'), prop: 'stock', align: 'center', operator: 'RANGE' },
            { label: t('mall.goods.imgurl'), prop: 'imgurl', align: 'center', render: 'image' },
            { label: t('state'), prop: 'status', align: 'center', width: '80', render: 'switch' },
            { label: t('operate'), align: 'center', width: 140, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined, ],
		defaultOrder: { prop: 'weigh', order: 'desc' },
    },
    {
        defaultItems: {"market_price":"0.00","price":"0.00","freight":"0.00","weight":"0","unit":"g","stock":"0","total_stock":"0","weigh":"100","status":"1"},
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<script lang="ts">
    import { defineComponent } from 'vue'
    export default defineComponent({
        name: 'mall/goods',
    })
</script>

<style scoped lang="scss"></style>
