<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'unfold']"
            :quick-search-placeholder="t('quick Search Placeholder', { fields: t('auth.menu.title') })"
            @action="baTable.onTableHeaderAction"
        />
        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" :pagination="false" @action="baTable.onTableAction" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, provide } from 'vue'
import { mallMenus } from '/@/api/controllerUrls'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'
import { defaultOptButtons } from '/@/components/table'
import { useI18n } from 'vue-i18n'
import { baTableApi } from '/@/api/common'
import baTableClass from '/@/utils/baTable'

const { t } = useI18n()
const tableRef = ref()
const baTable = new baTableClass(
    new baTableApi(mallMenus),
    {
        expandAll: false,
        dblClickNotEditColumn: [undefined, 'keepalive', 'status'],
        column: [
            { type: 'selection', align: 'center' },
            { label: t('mall.menus.title'), prop: 'title', align: 'left' },
            { label: t('mall.menus.imgurl'), prop: 'imgurl', align: 'center', render: 'image' },
            { label: t('state'), prop: 'status', align: 'center', width: '80', render: 'switch' },
            { label: t('createtime'), prop: 'createtime', align: 'center', width: '180', render: 'datetime' },
            {
                label: t('operate'),
                align: 'center',
                width: '130',
                render: 'buttons',
                buttons: defaultOptButtons(),
            },
        ],
        dragSortLimitField: 'pid',
    },
    {
        defaultItems: {
            type: 'menu',
            menu_type: 'tab',
            extend: 'none',
            keepalive: 0,
            status: '1',
            icon: 'el-icon-Minus',
        },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.dragSort()
    })
})
</script>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'mall/menus',
})
</script>

<style scoped lang="scss"></style>
