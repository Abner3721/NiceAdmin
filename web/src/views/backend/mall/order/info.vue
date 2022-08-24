<template> 
    <el-dialog
        custom-class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="baTable.form.operate=='info' ? true : false"
        :before-close="closeprop" 
        :fullscreen="true"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">{{ t('info') }}</div>
        </template> 
            <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
                <div class="ba-operate-form"  :class="'ba-' + baTable.form.operate + '-form'">
                    <el-card class="box-card"> 
                        <template #header>
                            <div class="card-header">
                                <span>基础信息</span> 
                            </div>
                            </template> 
                        <el-form label-position="right" :model="baTable.form.extend!.info"  label-width="100px">
                            <el-row :gutter="20">
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.order_no')"> 
                                            {{baTable.form.extend!.info.order_no}} 
                                        </el-form-item> 
                                </el-col>
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.createtime')"> 
                                            {{ timeFormat(baTable.form.extend!.info.createtime) }} 
                                        </el-form-item> 
                                </el-col>
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.total_price')"> 
                                            {{baTable.form.extend!.info.total_price}} 
                                        </el-form-item> 
                                </el-col> 
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.order_type')"> 
                                            {{baTable.form.extend!.info.order_type_text}} 
                                        </el-form-item> 
                                </el-col>  
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.status')"> 
                                            {{baTable.form.extend!.info.status_text}} 
                                        </el-form-item> 
                                </el-col>
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.user__username')"> 
                                            {{baTable.form.extend!.info.user.username}} 
                                        </el-form-item> 
                                </el-col>
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.pay_status')"> 
                                            {{baTable.form.extend!.info.pay_status_text}}
                                        </el-form-item> 
                                </el-col> 
                                <el-col :sm="12" :lg="6">
                                        <el-form-item :label="t('mall.order.pay_time')"> 
                                            {{baTable.form.extend!.info.pay_time}} 
                                        </el-form-item> 
                                </el-col> 
                            </el-row> 
                        </el-form> 
                    </el-card>
                </div>
                <div class="ba-operate-form"  :class="'ba-' + baTable.form.operate + '-form'">
                    <el-card class="box-card"> 
                        <template #header>
                            <div class="card-header">
                                <span>商品信息</span> 
                            </div>
                        </template>     
                        <el-table :data="baTable.form.extend!.goodsdata" border show-summary  style="width: 100%">
                            <el-table-column prop="goods_id" label="商品ID"/>
                            <el-table-column prop="title" label="商品名称"/>  
                            <el-table-column prop="weight" label="重量"/> 
                            <el-table-column prop="num" label="购买数量"/> 
                            <el-table-column prop="discount_price" label="价格"/> 
                            <el-table-column prop="price" label="小计"/>   
                        </el-table>
                    </el-card>
                </div>
                <div class="ba-operate-form"  :class="'ba-' + baTable.form.operate + '-form'">
                    <el-card class="box-card"> 
                        <template #header>
                            <div class="card-header">
                                <span>收货信息</span> 
                            </div>
                            </template> 
                        <el-form label-position="right" :model="baTable.form.extend!.info"  label-width="100px">
                            <el-row :gutter="20">
                                <el-col :sm="12" :lg="6">
                                        <el-form-item label="收货人"> 
                                            {{baTable.form.extend!.info.address.name}}  
                                        </el-form-item> 
                                </el-col> 
                                <el-col :sm="12" :lg="6">
                                        <el-form-item label="联系电话"> 
                                            {{baTable.form.extend!.info.address.phone}}  
                                        </el-form-item> 
                                </el-col> 
                                <el-col :sm="12" :lg="12">
                                        <el-form-item label="收货地址"> 
                                            {{baTable.form.extend!.info.address.prov}} 
                                            {{baTable.form.extend!.info.address.city}} 
                                            {{baTable.form.extend!.info.address.county}} 
                                            {{baTable.form.extend!.info.address.address}} 
                                        </el-form-item> 
                                </el-col> 
                                <el-col :sm="12" :lg="6">
                                        <el-form-item label="运单号"> 
                                            {{baTable.form.extend!.info.express_no}}  
                                        </el-form-item> 
                                </el-col> 
                                <el-col :sm="12" :lg="6">
                                        <el-form-item label="物流状态"> 
                                            {{baTable.form.extend!.info.express_status_text}}  
                                        </el-form-item> 
                                </el-col> 
                                <el-col :sm="12" :lg="12">
                                        <el-form-item label="">  
                                            <el-button v-if="baTable.form.extend!.info.express_no" type="primary" @click="lookwuliu(baTable.form.extend!.info.id)">查看物流信息</el-button>
                                        </el-form-item> 
                                </el-col>
                            </el-row> 
                        </el-form> 
                    </el-card>
                </div>
                <div class="btnbox">
                    <el-button type="primary"
                    v-if="baTable.form.extend!.info.status==2" 
                    disabled>已发货</el-button> 
                    <el-button type="primary"
                    v-if="baTable.form.extend!.info.status==1" 
                    @click="tofahuo()">发货</el-button> 
                    <el-button type="primary" 
                    v-if="baTable.form.extend!.info.status==0" 
                    @click="offline_payFn()">线下支付</el-button> 
                    <el-button type="primary" 
                    v-if="baTable.form.extend!.info.status==3" 
                    @click="refundFn()">退款</el-button> 
                </div>
            
            </el-scrollbar>
            <el-dialog  
                    custom-class="ba-operate-dialog"
                    :close-on-click-modal="false"
                    :model-value="baTable.form.isexpress ? true : false"
                    @close="closeexpress()"  width="50%"
                > 
                    <template #header>
                        <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">物流信息</div>
                    </template>  
                    <el-scrollbar  style="padding:0 20px;"  v-loading="baTable.form.expressloading" class="ba-table-form-scrollbar">
                    <el-form :model="baTable.form.expressinfo">
                        <el-row :gutter="20">
                            <el-col :lg="8">
                                <el-form-item label="物流公司"> 
                                    {{baTable.form.expressinfo.type}}  
                                </el-form-item> 
                            </el-col> 
                            <el-col :lg="16">
                                <el-form-item label="物流单号"> 
                                    {{baTable.form.expressinfo.wl_sn}}  
                                </el-form-item> 
                            </el-col>  
                        </el-row> 
                    </el-form> 
                    <el-timeline>
                        <el-timeline-item
                        v-for="(activity, index) in  baTable.form.expressinfo.json"
                        :key="index" 
                        type="primary"
                        :hollow="true"
                        :timestamp="activity.time"  
                        > 
                        {{ activity.status }}
                        </el-timeline-item>
                    </el-timeline>
                    </el-scrollbar>
            </el-dialog> 
            
        <Fahuo />
    </el-dialog>
</template>

<script setup lang="ts">
import { inject } from 'vue'
import { useI18n } from 'vue-i18n'
import type BaTable from '/@/utils/baTable'
import { timeFormat } from '/@/components/table'
import { wuliu,offline_pay,refund,ordershow} from '/@/api/backend'  
import Fahuo from './fahuo.vue'
import { ElNotification } from 'element-plus'

const baTable = inject('baTable') as BaTable 
const { t } = useI18n()   
baTable.form.isexpress = false    
baTable.form.expressinfo={}
const lookwuliu = (id) =>{
    baTable.form.expressloading=true
    baTable.form.isexpress=true
     wuliu(id).then((res) => {  
         baTable.form.expressloading=false
         baTable.form.expressinfo=res.data 
    }) 
} 
const closeexpress = () =>{
    baTable.form.isexpress=false
    baTable.form.expressinfo={}
}
const tofahuo = () =>{
    baTable.form.submitexpress={
        id:baTable.form.extend!['info'].id 
    }
    baTable.form.fahuo = 'fahuo'  
}
const offline_payFn = () =>{ 
    offline_pay(baTable.form.extend!.info.id).then((res) => {    
        ElNotification({
            message: res.msg,
            type: 'success',
        })
        ordershow(baTable.form.extend!.info.id).then((res)=>{
            baTable.form.extend!['info'] = res.data             
        }) 
    }) 
} 
const refundFn = () =>{ 
    refund(baTable.form.extend!.info.id).then((res) => {   
        ElNotification({
            message: res.msg,
            type: 'success',
        })
        ordershow(baTable.form.extend!.info.id).then((res)=>{
            baTable.form.extend!['info'] = res.data 
        })
    }) 
} 
const closeprop = (done) =>{
    baTable.getIndex() 
    baTable.toggleForm('')
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
.box-card{
    width: calc(100% - 20px);
    margin: 0 auto;
}

.el-dialog.is-fullscreen>div>.ba-table-form-scrollbar {
   height:calc(100vh - 60px);
max-height:calc(100vh - 60px); 
} 
.btnbox{
    margin: 30px 10px  ;
} 
</style>
