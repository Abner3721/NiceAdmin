<template> 
    <el-dialog
        custom-class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.fahuo=='fahuo' ? true : false"
        @close="closefahuo()" 
        width="40%"
        top="30vh"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">填写物流</div>
        </template>  
            <div class="ba-operate-form"  :class="'ba-' + baTable.form.operate + '-form'">
                <el-form :model="baTable.form.submitexpress"
                :rules="rules"> 
                        <el-form-item prop="express_no" label="物流单号"> 
                              <el-input v-model="baTable.form.submitexpress.express_no" placeholder="请输入物流单号" /> 
                        </el-form-item>  
                        <el-form-item prop="express_type" label="物流公司">  
                             <el-select v-model="baTable.form.submitexpress.express_type" placeholder="请选择物流公司">
                                <el-option  label="京东" value="JD" />
                                <el-option  label="韵达" value="YUNDA56" />
                                <el-option  label="顺丰" value="SFEXPRESS" />
                                <el-option  label="圆通" value="YTO" />
                                <el-option  label="中通" value="ZTO" />
                                <el-option  label="申通" value="STO" />
                                <el-option  label="EMS" value="EMS" />
                            </el-select>
                        </el-form-item>  
                </el-form> 
            </div>   
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="closefahuo()">{{ t('Cancel') }}</el-button>
                <el-button v-blur  @click="onSubmit()" type="primary">
                    {{ t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { inject,reactive } from 'vue'
import { useI18n } from 'vue-i18n'
import type BaTable from '/@/utils/baTable'
import { timeFormat } from '/@/components/table'
import { addWuliu,ordershow} from '/@/api/backend'  
import { ElNotification } from 'element-plus'

const baTable = inject('baTable') as BaTable 
const { t } = useI18n()   
 
const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    express_no: [
        {
            required: true, 
            trigger: 'blur',
            message:"请填写物流单号"
        },
    ], 
    express_type: [
        {
            required: true, 
            trigger: 'blur',
            message:"请选择物流公司"
        },
    ], 
})

const closefahuo = () =>{ 
    baTable.form.fahuo = ''  
}
const onSubmit = () =>{ 
    if(!baTable.form.submitexpress.express_no){
        ElNotification({message:'请填写物流单号',type: 'error'})
        return
    } 
    if(!baTable.form.submitexpress.express_type){
        ElNotification({message:'请选择物流公司',type: 'error'})
        return
    }  
    addWuliu(baTable.form.submitexpress).then((res)=>{ 
         ElNotification({
            message: '发货成功',
            type: 'success',
        })
        baTable.getIndex() 
        ordershow(baTable.form.extend!.info.id).then((res)=>{
            baTable.form.extend!['info'] = res.data 
        })
        closefahuo()
    })
}
</script>

<style scoped lang="scss">
.table-el-tree {
    :deep(.el-tree-node) {
        white-space: unset;
    }
    :deep(.el-tree-node__content) {
        display: block;
        align-items: unset;
        height: unset;
    }
}  
 
</style>
