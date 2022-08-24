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
				<FormItem :label="t('article.comments.articles_id')" type="remoteSelect" v-model="baTable.form.items!.articles_id" prop="articles_id" :input-attr="{ field: 'title', 'remote-url': articleArticles + 'index', pk: 'article_articles.id', placeholder: t('Please select field', { field: t('article.comments.articles_id') }) }" />
				<FormItem :label="t('article.comments.user_id')" type="remoteSelect" v-model="baTable.form.items!.user_id" prop="user_id" :input-attr="{ field: 'nickname', 'remote-url': userUser + 'index', pk: 'user.id', placeholder: t('Please select field', { field: t('article.comments.user_id') }) }" />
                    <FormItem :label="t('article.articles.content')" type="textarea" v-model="baTable.form.items!.content" prop="content" :input-attr="{ rows: 4, placeholder: t('Please input field', { field: t('article.comments.content') }) }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" />
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
import { userUser,articleArticles } from '/@/api/controllerUrls'


const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
	articles_id: [buildValidatorData('required', t('article.comments.articles_id'))],
	user_id: [buildValidatorData('required', t('article.comments.user_id'))],
	content: [buildValidatorData('editorRequired', t('article.comments.content'))],
	delete_time: [buildValidatorData('required', t('article.comments.delete_time')), buildValidatorData('date', '', 'blur', t('Please enter the correct field', { field: t('article.comments.delete_time') }))],
})

</script>

<style scoped lang="scss"></style>
