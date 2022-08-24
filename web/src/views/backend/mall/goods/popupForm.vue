<template>
    <!-- 对话框表单 -->
    <el-dialog
        custom-class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.operate ? true : false"
        :before-close="closeprop"
        :fullscreen="true"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem
                        :label="t('mall.goods.menu_id')"
                        type="remoteSelect"
                        v-model="baTable.form.items!.menu_id"
                        prop="menu_id"
                        :input-attr="{
                            params: { isTree: true },
                            field: 'title',
                            'remote-url':  mallMenus + 'index',
                            placeholder: t('Please select field', { field: t('mall.goods.menu_id') })
                    }"
                    />
<!--				<FormItem :label="t('mall.goods.menu_id')" type="remoteSelect" v-model="baTable.form.items!.menu_id" prop="menu_id" :input-attr="{ field: 'name', 'remote-url': 'menu', pk: 'menu.id', placeholder: t('Please select field', { field: t('mall.goods.menu_id') }) }" />-->
				<FormItem :label="t('mall.goods.title')" type="string" v-model="baTable.form.items!.title" prop="title" :input-attr="{ placeholder: t('Please input field', { field: t('mall.goods.title') }) }" />
				<FormItem :label="t('mall.goods.keyword')" type="string" v-model="baTable.form.items!.keyword" prop="keyword" :input-attr="{ placeholder: t('Please input field', { field: t('mall.goods.keyword') }) }" />
				<FormItem :label="t('mall.goods.description')" type="textarea" v-model="baTable.form.items!.description" prop="description" :input-attr="{ rows: 4, placeholder: t('Please input field', { field: t('mall.goods.description') }) }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" />
				<FormItem :label="t('mall.goods.market_price')" type="number" prop="market_price" v-model.number="baTable.form.items!.market_price" :input-attr="{ step: '0.01', placeholder: t('Please input field', { field: t('mall.goods.market_price') }) }" />
				<FormItem :label="t('mall.goods.price')" type="number" prop="price" v-model.number="baTable.form.items!.price" :input-attr="{ step: '0.01', placeholder: t('Please input field', { field: t('mall.goods.price') }) }" />
				<FormItem :label="t('mall.goods.freight')" type="number" prop="freight" v-model.number="baTable.form.items!.freight" :input-attr="{ step: '0.01', placeholder: t('Please input field', { field: t('mall.goods.freight') }) }" />
				<FormItem :label="t('mall.goods.weight')" type="string" v-model="baTable.form.items!.weight" prop="weight" :input-attr="{ placeholder: t('Please input field', { field: t('mall.goods.weight') }) }" />
				<FormItem :label="t('mall.goods.unit')" type="radio" v-model="baTable.form.items!.unit" prop="unit" :data="{ content: { kg: t('mall.goods.unit kg'), g: t('mall.goods.unit g') } }" :input-attr="{ placeholder: t('Please select field', { field: t('mall.goods.unit') }) }" />
				<FormItem :label="t('mall.goods.stock')" type="number" prop="stock" v-model.number="baTable.form.items!.stock" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('mall.goods.stock') }) }" />
				<FormItem :label="t('mall.goods.total_stock')" type="number" prop="total_stock" v-model.number="baTable.form.items!.total_stock" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('mall.goods.total_stock') }) }" />
				<FormItem :label="t('mall.goods.weigh')" type="number" prop="weigh" v-model.number="baTable.form.items!.weigh" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('mall.goods.weigh') }) }" />
				<FormItem :label="t('mall.goods.status')" type="radio" v-model="baTable.form.items!.status" prop="status" :data="{ content: { 0: t('mall.goods.status 0'), 1: t('mall.goods.status 1') } }" :input-attr="{ placeholder: t('Please select field', { field: t('mall.goods.status') }) }" />
				<FormItem :label="t('mall.goods.imgurl')" type="image" v-model="baTable.form.items!.imgurl" prop="imgurl" :input-attr="{ placeholder: t('Please select field', { field: t('mall.goods.imgurl') }) }" />
				<FormItem :label="t('mall.goods.pics')" type="images" v-model="baTable.form.items!.pics" prop="pics" :input-attr="{ placeholder: t('Please select field', { field: t('mall.goods.pics') }) }" />
                <FormItem :label="t('mall.goods.content')" type="editor" v-model="baTable.form.items!.content" prop="content" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :input-attr="{ placeholder: t('Please input field', { field: t('mall.goods.content') }) }" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="closeprop()">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, inject } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import {ElMessageBox, ElForm, FormItemRule } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'
import { mallMenus } from '/@/api/controllerUrls'


const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
	menu_id: [buildValidatorData('required', t('mall.goods.menu_id'))],
	title: [buildValidatorData('required', t('mall.goods.title'))],
	market_price: [buildValidatorData('required', t('mall.goods.market_price'))],
	price: [buildValidatorData('required', t('mall.goods.price'))],
	stock: [buildValidatorData('required', t('mall.goods.stock'))],
	total_stock: [buildValidatorData('required', t('mall.goods.total_stock'))],
	content: [buildValidatorData('editorRequired', t('mall.goods.content'))],
	imgurl: [buildValidatorData('required', t('mall.goods.imgurl'))],
	pics: [buildValidatorData('required', t('mall.goods.pics'))],
})

const closeprop = (done) => {  
    ElMessageBox.confirm('请确认您的内容是否已提交', '',
        {
        confirmButtonText: '退出',
        cancelButtonText: '继续编辑',
        type: 'warning',
        }
    ) 
    .then(() => {  
        baTable.form.operate=false 
        
        done()
    })
    .catch(() => { 
    }) 
}
</script>

<style scoped lang="scss"></style>
