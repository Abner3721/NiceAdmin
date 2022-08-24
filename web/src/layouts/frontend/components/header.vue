<template>
    <el-header class="header"> 
        <div :class="userInfo.id > 0 ? 'hidden-sm-and-down' : ''" @click="router.push({ name: '/' })" class="header-logo">
            <!-- <img :src="state.logininfo.login_logo"  />  -->
        </div>
                <!-- 

                    <el-sub-menu v-blur index="switch-language">
                        <template #title>{{ $t('index.language') }}</template>
                        <el-menu-item
                            @click="editDefaultLang(item.name)"
                            v-for="item in config.lang.langArray"
                            :key="item.name"
                            :index="'switch-language-' + item.value"
                            >{{ item.value }}</el-menu-item
                        >
                    </el-sub-menu>
                </el-menu> --> 
    </el-header>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { useRoute } from 'vue-router'
import { useRouter } from 'vue-router'
import { useUserInfo } from '/@/stores/userInfo'
import { useSiteConfig } from '/@/stores/siteConfig'
import { useConfig } from '/@/stores/config'
import { useMemberCenter } from '/@/stores/memberCenter'
import { editDefaultLang } from '/@/lang/index'
import { index } from '/@/api/frontend/index'
import 'element-plus/theme-chalk/display.css'
import Aside from '/@/layouts/frontend/components/aside.vue'
import 'element-plus/theme-chalk/display.css'
import { getBgImage } from '/@/api/backend'
 
const state = reactive({
    activeMenu: '',  
}) 
const route = useRoute()
const userInfo = useUserInfo()
const router = useRouter()
const config = useConfig()
const siteConfig = useSiteConfig()
const memberCenter = useMemberCenter()
// console.log(reactive)
switch (route.name) {
    case '/':
        state.activeMenu = ''
        break
    case 'userLogin':
        state.activeMenu = 'user'
        break
}
 
index()
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
@media only screen and (max-width: 768px) { 
    .user-menus-expand {
        padding-left: 20px;
    }
    .header-logo  img {
        height: 25px; 
    } 
} 
</style>
