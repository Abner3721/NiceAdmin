<template>
    <!-- 对话框表单 -->
    <el-dialog
        custom-class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.operate ? true : false"
        @close="baTable.toggleForm"
		width='50%'
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
				<FormItem :label="t('seos.index.name')" type="string" v-model="baTable.form.items!.name" prop="name" :input-attr="{ placeholder: t('Please input field', { field: t('seos.index.name') }) }" />
				<FormItem :label="t('seos.index.method')" type="string" v-model="baTable.form.items!.method" prop="method" :input-attr="{ placeholder: t('Please input field', { field: t('seos.index.method') }) }" />
				<FormItem :label="t('seos.index.title')" type="textarea" v-model="baTable.form.items!.title" prop="title" :input-attr="{ rows: 4, placeholder: t('Please input field', { field: t('seos.index.title') }) }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" />
				<FormItem :label="t('seos.index.keyword')" type="textarea" v-model="baTable.form.items!.keyword" prop="keyword" :input-attr="{ rows: 4, placeholder: t('Please input field', { field: t('seos.index.keyword') }) }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" />
				<FormItem :label="t('seos.index.description')" type="textarea" v-model="baTable.form.items!.description" prop="description" :input-attr="{ rows: 4, placeholder: t('Please input field', { field: t('seos.index.description') }) }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" />
				<FormItem :label="t('seos.index.weigh')" type="number" prop="weigh" v-model.number="baTable.form.items!.weigh" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('seos.index.weigh') }) }" />
				<FormItem :label="t('seos.index.status')" type="radio" v-model="baTable.form.items!.status" prop="status" :data="{ content: { 0: t('seos.index.status 0'), 1: t('seos.index.status 1') } }" :input-attr="{ placeholder: t('Please select field', { field: t('seos.index.status') }) }" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
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
import type { ElForm, FormItemRule } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'


const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
	title: [buildValidatorData('required', t('seos.index.title'))],
	keyword: [buildValidatorData('required', t('seos.index.keyword'))],
	description: [buildValidatorData('required', t('seos.index.description'))],
	method: [buildValidatorData('required', t('seos.index.method'))],
	name: [buildValidatorData('required', t('seos.index.name'))],
})

</script>

<style scoped lang="scss"></style>
