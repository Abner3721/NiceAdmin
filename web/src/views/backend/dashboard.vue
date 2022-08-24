<template>
    <div class="default-main">
        <div class="hellobox">
            <h3>{{state.currenttime.ampm}}好，<h2>{{adminInfo.$state.nickname}}</h2></h3>
            <p>{{state.currenttime.year}}.{{state.currenttime.month}}.{{state.currenttime.date}} {{state.currenttime.week}}</p>
        </div>
        <el-row :gutter="20">
            <el-col :xs="24" :sm="10">
                <el-card class="dashcard linkbox"  shadow="hover" header="快捷入口">  
                    <el-row :gutter="20">
                        <el-col :xs="6" :sm="8" :md="8" :lg="6" v-for="item in state.dashboard.quick">
                            <div class="link" @click="torouter(item.path)">
                                <Icon  class="icon" color="#333333"   size="20" :name="item.icon" />  
                                <p>{{item.title}}</p>
                            </div>
                        </el-col>
                        
                    </el-row>
                </el-card>
            </el-col>
            <el-col :xs="24" :sm="14">
                <div class="small-panel-box">
                    <el-row :gutter="20">
                        <el-col :span="12">
                            <div class="small-panel suspension">
                                <div class="small-panel-title">总浏览量</div>
                                <div class="small-panel-content">
                                    <div class="content-left"> 
                                        <span id="goods_num">{{state.dashboard.view.total}}</span>
                                    </div>
                                    <div class="content-right" :class="state.dashboard.view.symbol=='-'?'down':'up'"> 
                                        <span id="goods_num">{{state.dashboard.view.diff}}</span>
                                        <img src="../../assets/dashboard/up.png" class="iconup"   alt="">
                                        <img src="../../assets/dashboard/down.png" class="icondown"  alt="">
                                    </div>
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="12">
                            <div class="small-panel suspension">
                                <div class="small-panel-title">会员总数</div>
                                <div class="small-panel-content">
                                    <div class="content-left"> 
                                         <span id="goods_num">{{state.dashboard.user.total}}</span>
                                    </div>
                                    <div class="content-right" :class="state.dashboard.user.symbol=='-'?'down':'up'"> 
                                        <span id="goods_num">{{state.dashboard.user.diff}}</span>
                                        <img src="../../assets/dashboard/up.png" class="iconup"   alt="">
                                        <img src="../../assets/dashboard/down.png" class="icondown"  alt="">
                                    </div>
                                </div>
                            </div>
                        </el-col> 


                        <el-col :span="12">
                            <div class="small-panel suspension">
                                <div class="small-panel-title">总收入</div>
                                <div class="small-panel-content">
                                    <div class="content-left"> 
                                       <span id="goods_num">{{state.dashboard.revenue.total}}元</span>
                                    </div>
                                    <div class="content-right" :class="state.dashboard.revenue.symbol=='-'?'down':'up'"> 
                                        <span id="goods_num">{{state.dashboard.revenue.diff}}元</span>
                                        <img src="../../assets/dashboard/up.png" class="iconup"   alt="">
                                        <img src="../../assets/dashboard/down.png" class="icondown"  alt="">
                                    </div>
                                </div>
                            </div>
                        </el-col> 
                        <el-col :span="12">
                            <div class="small-panel suspension">
                                <div class="small-panel-title">总订单数</div>
                                <div class="small-panel-content">
                                    <!-- <Icon color="#8595F4" size="20" name="el-icon-ShoppingCart" /> -->
                                    <div class="content-left">
                                         <span id="goods_num">{{state.dashboard.order.total}}</span>
                                    </div>
                                    <div class="content-right" :class="state.dashboard.order.symbol=='-'?'down':'up'"> 
                                        <span id="goods_num">{{state.dashboard.order.diff}}</span>
                                        <img src="../../assets/dashboard/up.png" class="iconup"   alt="">
                                        <img src="../../assets/dashboard/down.png" class="icondown"  alt="">
                                    </div>
                                </div>
                            </div>
                        </el-col>
                      
                    </el-row>
                </div>
            </el-col>
        </el-row>
        <div class="growth-chart">
            <el-row :gutter="20">
                <el-col class="lg-mb-20" :xs="24" :sm="24" :md="24" :lg="24">
                    <el-card class="dashcard " shadow="hover" header="浏览量趋势变化图">
                        <div class="btnbox">
                             <el-button  :color="state.chartstype==7?'#2D4EF5':''" @click="chartset(7)">近一周</el-button>
                             <el-button  :color="state.chartstype==30?'#2D4EF5':''" @click="chartset(30)">近一月</el-button>
                             <el-button  :color="state.chartstype==90?'#2D4EF5':''" @click="chartset(90)">近一季</el-button>
                             <el-button  :color="state.chartstype==182?'#2D4EF5':''" @click="chartset(182)">近半年</el-button>
                             <el-button  :color="state.chartstype==365?'#2D4EF5':''" @click="chartset(365)" class="btnmar">近一年</el-button>
                             <div  class="timerpicker">
                                <el-date-picker
                                    v-model="state.chartstime"
                                    type="daterange"  
                                    value-format="YYYY-MM-DD"
                                    format="YYYY-MM-DD" 
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期" 
                                />
                             </div>
                             <el-button   color="#2D4EF5" plain :disabled="state.chartstime?false:true" @click="chartset(state.chartstime)">搜索</el-button>
                        </div>
                        <div class="user-growth-chart" id="chartRef" v-loading="state.chartsloading" ></div>
                    </el-card>
                </el-col> 
            </el-row>
        </div>

    </div>
</template>
<script setup lang="ts">
import { onMounted, onUnmounted, reactive, nextTick, onActivated, watch, onBeforeMount } from 'vue' 
import * as echarts from 'echarts'
import { useNavTabs } from '/@/stores/navTabs' 
import { dashboard,visits } from '/@/api/backend/dashboard'
import { useI18n } from 'vue-i18n' 
import { useAdminInfo } from '/@/stores/adminInfo' 
import 'element-plus/theme-chalk/display.css'
import { routePush } from '/@/utils/router'
var workTimer: NodeJS.Timer

const date = new Date()
const { t } = useI18n()
const navTabs = useNavTabs() 
const adminInfo = useAdminInfo() 

const state: {
    charts: object
    remark: string
    workingTimeFormat: string
    pauseWork: boolean,
    currenttime:object,
    dashboard:object,
} = reactive({
    charts: {},
    remark: 'dashboard.Loading',
    workingTimeFormat: '',
    pauseWork: false,
    currenttime:{ },
    dashboard:{
        view:{},
        revenue:{},
        user:{},
        order:{},
        quick:{},
    },
    chartstype:7,
    chartsloading:true,
   
})  


const initUserGrowthChart = (week) => {  
    let xAxisdata=[]
    let yAxisdata=[]
    for(let i=0;i<week.length;i++){
        xAxisdata.push(week[i].createtime)
        yAxisdata.push(week[i].view)
    }
    state.chartsloading=false
    const userGrowthChart = echarts.init(document.getElementById('chartRef'))
    const option = {
        grid: {
            top: 30,
            right:50,
            left: 50, 
        }, 
        tooltip:{
            show:true,
            trigger:'axis',
            backgroundColor: '#2D4EF5',
            textStyle:{
                color:"#fff"
            }
        },
        animation:true,
        xAxis: {
            data:xAxisdata,
            splitLine:{
                show:true
            },
            axisLine:{
                show:false
            },
            axisTick:{
                show:false
            },  
            boundaryGap:false
        },
        yAxis: { 
            splitLine:{
                show:false
            }
        },
        legend: {
            data:['访问量'],
            top:'bottom',
            icon:'rect',  
            itemWidth:13,
            itemHeight:2,
            textStyle:{
                color:'#2D4EF5',
            },
        },
        series: [
            {
                name:'访问量',
                data:  yAxisdata ,
                type: 'line',
                smooth: false, 
                // symbol:"circle",
                symbolSize:[1,1],
                markLine: {
                    data: [{ type: 'average', name: 'Avg' }], 
                    label:{
                        show:true     //该线上的值去掉
                    }, 
                    lineStyle:{        //设置该线样式
                        normal:{ 
                            color:"#F1A153"
                        }
                    },
                }
            },
        ],
    }
    userGrowthChart.setOption(option)
    state.charts=userGrowthChart
}
 
  
const torouter = (path) => { 
   routePush(path,{})
}
const chartset = (num) => {
   echartsinit(num)
}
const echartsResize = () => { 
    nextTick(() => { 
        state.charts.resize() 
    })
}
  
const echartsinit = (type) => { 
    state.chartsloading=true
    let start_time
    let end_time
    if(type==7||type==30||type==90||type==182||type==365){
        state.chartstype=type
        state.chartstime=''
    }else{
        state.chartstype=''
        start_time=type[0]
        end_time=type[1] 
    }
    visits({day:state.chartstype,start_time:start_time,end_time:end_time} ).then((res) => { 
        initUserGrowthChart(res.data)
        window.addEventListener('resize', echartsResize)
    }) 
}
  
onActivated(() => {
    echartsResize()
})

onMounted(() => {  
    state.currenttime.year= date.getFullYear()
    state.currenttime.month= date.getMonth()+1
    state.currenttime.date= date.getDate()
    if(date.getDay()==0){
        state.currenttime.week= '周日'
    }else if(date.getDay()==1){
        state.currenttime.week= '周一'
    }else if(date.getDay()==2){
        state.currenttime.week= '周二'
    }else if(date.getDay()==3){
        state.currenttime.week= '周三'
    }else if(date.getDay()==4){
        state.currenttime.week= '周四'
    }else if(date.getDay()==5){
        state.currenttime.week= '周五'
    }else if(date.getDay()==6){
        state.currenttime.week= '周六'
    }
    if(date.getHours()>12){
        state.currenttime.ampm='下午'
    }else{
        state.currenttime.ampm='上午'
    }  
    dashboard().then((res) => { 
        state.dashboard = res.data  
    }) 
    echartsinit(state.chartstype)
})
  
watch(
    () => navTabs.state.tabFullScreen,
    () => {
        echartsResize()
    }
)
</script>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'dashboard',
})
</script> 


<style scoped lang="scss">
.hellobox{
    margin: 30px 0;
    h3{
        font-size: 18px;
        font-weight: 500;
        color: #494A66;
        h2{
            color: #494A66;
            font-weight: 700;
            font-size: 18px;
            display: inline-block;
        }
    }
    p{
        font-size: 12px;
        color: #999;
        font-weight: 300;
        margin-top: 10px;
    }
}
.linkbox {
    margin-bottom: 20px;
    .link{
        text-align: center;
        color: #333333;
        font-weight: 400px;
        cursor: pointer;
        font-size: 14px;
        margin-bottom: 17px;
        .icon{
            width: 50px;
            height: 50px;
            background-color: #FAFAFA;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    } 
}
     .link:hover   .icon{
         background-color: #f3f3f3;
     }
.small-panel:hover {
    box-shadow: var(--el-box-shadow-light);
}
.small-panel {
    background-color: #fff;
    border-radius: 4px;
    transition: all 0.3s ease;
    padding: 24px;
    margin-bottom: 20px;
    .small-panel-title {
  color: #444452;
font-weight: 500;
font-size: 16px;
    }
    .small-panel-content {
        display: flex;
        justify-content:flex-start;
        align-items: flex-end;
        margin-top: 25px; 
        .content-left {
            color: #494A66;
            font-weight: 500;
            font-size: 24px;
            white-space: nowrap;
        } 
        .content-right {
            margin-left: 20px; 
            font-weight: 500;
            font-size: 16px; 
            display: flex;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
            img{ 
                width: 11px;
                margin-left: 5px;
                margin-top: -2px;
                display: none;
            }
        }
        .content-right.up {
            color: #2D4EF5; 
            .iconup{
                display: inline-block;
            } 
        }
        .content-right.down {
            color: #FF6C66;
            .icondown{ 
                display: inline-block;
            } 
        }
    }
}
.growth-chart {
    margin-bottom: 20px;
} 
.btnbox{
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
 .btnbox .el-button{
     margin-bottom: 10px;
 }
 
 .btnbox .btnmar,
 .btnbox .timerpicker {
     margin-right:12px;  
     margin-bottom: 10px;
 }
.user-growth-chart{
    height: 400px;
} 
@media screen and (max-width: 1200px) {
    .lg-mb-20 {
        margin-bottom: 20px;
    }
}
</style>
