<template>
    <!-- 对话框表单 -->
    <el-dialog
        custom-class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.operate ? true : false"
        :before-close="closeprop" 
        :fullscreen="true"
    >
        <!-- @close="closeprop()"  -->
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
                        :label="t('article.menus.pid')"
                        type="remoteSelect"
                        v-model="baTable.form.items!.pid"
                        prop="menu_id"
                        :input-attr="{
                            params: { isTree: true },
                            field: 'title',
                            'remote-url':  baTable.api.actionUrl.get('index'),
                            placeholder: t('Please select field', { field: t('article.menus.pid') })
                         }"
                    />

<!--				<FormItem :label="t('article.menus.pid')" type="number" prop="pid" v-model.number="baTable.form.items!.pid" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('article.menus.pid') }) }" />-->
				<FormItem :label="t('article.menus.title')" type="string" v-model="baTable.form.items!.title" prop="title" :input-attr="{ placeholder: t('Please input field', { field: t('article.menus.title') }) }" />
				<FormItem :label="t('article.menus.imgurl')" type="image" v-model="baTable.form.items!.imgurl" prop="imgurl" :input-attr="{ placeholder: t('Please select field', { field: t('article.menus.imgurl') }) }" />
				<FormItem :label="t('article.menus.href')" type="string" v-model="baTable.form.items!.href" prop="href" :input-attr="{ placeholder: t('Please input field', { field: t('article.menus.href') }) }" />
				<FormItem :label="t('article.menus.keywords')" type="string" v-model="baTable.form.items!.keywords" prop="keywords" :input-attr="{ placeholder: t('Please input field', { field: t('article.menus.keywords') }) }" />
				<FormItem :label="t('article.menus.description')" type="textarea" v-model="baTable.form.items!.description" prop="description" :input-attr="{ rows: 4, placeholder: t('Please input field', { field: t('article.menus.description') }) }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" />
				<FormItem :label="t('article.menus.weigh')" type="number" prop="weigh" v-model.number="baTable.form.items!.weigh" :input-attr="{ step: '1', placeholder: t('Please input field', { field: t('article.menus.weigh') }) }" />
				<FormItem :label="t('article.menus.status')" type="radio" v-model="baTable.form.items!.status" prop="status" :data="{ content: { 0: t('article.menus.status 0'), 1: t('article.menus.status 1') } }" :input-attr="{ placeholder: t('Please select field', { field: t('article.menus.status') }) }" />
				<FormItem :label="t('article.menus.content')" type="editor" v-model="baTable.form.items!.content" prop="content" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :input-attr="{ placeholder: t('Please input field', { field: t('article.menus.content') }) }" />
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
import { buildValidatorData } from '/@/utils/validate'
import { ElMessageBox,ElForm, FormItemRule,ElMessage } from 'element-plus'


const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
	pid: [buildValidatorData('required', t('article.menus.pid'))],
	title: [buildValidatorData('required', t('article.menus.title'))],
	status: [buildValidatorData('required', t('article.menus.status'))],
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
