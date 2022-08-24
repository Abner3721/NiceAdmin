<template>
    <div> 
    <el-header class="header"> 
        <div :class="userInfo.id > 0 ? 'hidden-sm-and-down' : ''" @click="router.push({ name: '/' })" class="header-logo">
            <img :src="state.logininfo.login_logo"  /> 
        </div> 
    </el-header>
        <el-container class="container">
            <el-main class="main">
                <div class="main-container">
                    <div class="main-left">
                        <p>NiceAdmin</p>
                        <div class="main-title">{{state.logininfo.login_title}}</div>
                        <hr>
                        <div class="main-content">
                            {{ $t('index.Steve Jobs') }}
                        </div>
                        <el-button @click="$router.push('/admin')" color="#2D4EF5" class="btn" plain size="large">进入管理系统</el-button>
                    </div>
                    <div class="main-right">
                        <img :src="indexCover" alt="" />
                    </div>
                </div>
            </el-main>
        </el-container> 
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref, nextTick } from 'vue'
import indexCover from '/@/assets/index/index-cover.png'
import { useSiteConfig } from '/@/stores/siteConfig'
import { useMemberCenter } from '/@/stores/memberCenter'
import Header from '/@/layouts/frontend/components/header.vue'
import Footer from '/@/layouts/frontend/components/footer.vue'
import { getBgImage,getview } from '/@/api/backend'


import { useRoute } from 'vue-router'
import { useRouter } from 'vue-router'
import { useUserInfo } from '/@/stores/userInfo' 
import { useConfig } from '/@/stores/config'  
import { index } from '/@/api/frontend/index'
import 'element-plus/theme-chalk/display.css'  
const state = reactive({ 
    logininfo:{}, 
    activeMenu: '',  
})
const siteConfig = useSiteConfig()
const memberCenter = useMemberCenter()

onMounted(() => {  
  getview()
    .then((res) => {  
        console.log(res)
    })
    .catch((err) => {
        console.log(err)
    })
  getBgImage()
    .then((res) => { 
        if(res.data){ 
            state.logininfo=res.data 
        }
    })
    .catch((err) => {
        console.log(err)
    })
})

const route = useRoute()
const userInfo = useUserInfo()
const router = useRouter()
const config = useConfig() 
switch (route.name) {
    case '/':
        state.activeMenu = ''
        break 
}
 
// index()
</script>

<style scoped lang="scss">

.header {
    background-color: #fff;
    box-shadow: 0px 0px 8px rgba(0 0 0 / 8%);  
} 
.header-logo {
    display: flex; 
    align-items: center;
    cursor: pointer;
    img {
        height: 37px; 
    } 
} 
.container {
    width: 100vw;
    height: 100vh;
    background: url(/@/assets/bg.jpg) top right  no-repeat;
    background-size: cover;
    color:#000;
    .main {
        height: calc(100vh);
        padding: 0;
        .main-container {
            display: flex;
            height: 100%;
            width: 72%;
            margin: 0 auto;
            align-items: center;
            justify-content: space-between;
            .main-left {
                padding-right: 50px;
                p{
                    font-size: 20px;    
                    font-weight: 200;
                    color: rgba(0, 0, 0, 0.4);
                    line-height: 30px;
                    letter-spacing: 5px;
                }
                .main-title {
                   font-size: 40px; 
                    font-weight: 500;
                    color: #000000;
                    line-height: 45px;
                    margin: 20px 0;
                }
                hr{
                    width: 26px;
                    height: 3px;
                    border: 0;
                    background: #000000;
                    display: block;
                    margin: 46px 0 27px;
                }
                .main-content {
                    font-size: 24px; 
                    font-weight: 200;
                    color: #000000;
                    line-height: 24px;
                    letter-spacing: 4px;
                }
                .btn{
                    background-color: transparent;
                    margin-top: 90px;
                }
                .btn:hover{
                    background-color: var(--el-button-bg-color);
                    color:var(--el-button-text-color);
                }
            }
            .main-right {
                img {
                    width: 38.7vw;
                    display: block;
                }
            }
        }
    }
}
.header {
    background-color: transparent !important;
    box-shadow: none !important;
    position: fixed;
    width: 100%;
    box-sizing: border-box;
    padding: 50px 14% 0;
    :deep(.header-logo) {
        span {
            padding-left: 4px;
            color: var(--color-basic-white);
        }
    } 
}
.footer {
    color: var(--color-secondary);
    background-color: transparent !important;
    position: fixed;
    bottom: 0;
}
 
    @media screen and (max-width:900px) {  
            .user-menus-expand {
                padding-left: 20px;
            }
            .header-logo  img {
                height: 25px; 
            } 
         .container .main   .main-container {
        width: 90% !important;
        flex-wrap: wrap;
        align-content: center;
        justify-content: flex-start !important;
         .main-left { 
             p{
                    font-size: 14px;  
                }
                .main-title {
                   font-size: 25px;  
                    margin: 10px 0;
                    line-height: 30px;
                } 
                .main-content {
                    font-size: 16px;  
                } 
                hr{
                    margin: 20px 0 10px;
                }
        .btn{
            margin-top: 30px;
        }
        }
        .main-right {
            padding-top: 50px;
        }
    } 
    .header { 
    padding:20px 5%;
    }
    .main-right img {
        width: 300px !important;
    }
}
</style>
