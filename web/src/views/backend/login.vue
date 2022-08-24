<template>
    <div> 
        <div @contextmenu.stop="" id="bubble" class="bubble">
            <canvas id="bubble-canvas" class="bubble-canvas"></canvas>
        </div>
        <div class="login">
            <div class="login-box">
                <div class="head">
                    <img :src="state.logininfo.login_logo" class="loginlogo" alt="" />
                </div>
                <div class="form">
                    <h2 class="title">{{state.logininfo.login_title}}</h2>
                    <div class="content">
                        <el-form ref="formRef"  @keyup.enter="onSubmit(formRef)" :rules="rules" size="large" :model="form">
                            <el-form-item prop="username">
                                <el-input
                                    class="logininput"
                                    ref="usernameRef"
                                    type="text"
                                    clearable
                                    v-model="form.username"
                                    @input="changeinput()" 
                                    :placeholder="t('adminLogin.Please enter an account')"
                                > 
                                </el-input>
                            </el-form-item>
                            <el-form-item prop="password">
                                <el-input
                                    class="logininput"
                                    ref="passwordRef"
                                    v-model="form.password"
                                    @input="changeinput()"
                                    type="password" 
                                    :placeholder="t('adminLogin.Please input a password')"
                                    show-password
                                > 
                                </el-input>
                            </el-form-item>
                            <el-form-item v-if="state.showCaptcha" prop="captcha">
                                <el-row class="w100" :gutter="15">
                                    <el-col :span="16">
                                        <el-input
                                            ref="captchaRef"
                                            type="text"
                                            :placeholder="t('adminLogin.Please enter the verification code')"
                                            v-model="form.captcha"
                                            clearable
                                            autocomplete="off"
                                        >
                                            <template #prefix>
                                                <Icon name="fa fa-ellipsis-h" class="form-item-icon" size="16" color="var(--el-input-icon-color)" />
                                            </template>
                                        </el-input>
                                    </el-col>
                                    <el-col :span="8">
                                        <img
                                            @click="onChangeCaptcha"
                                            class="captcha-img"
                                            :src="buildCaptchaUrl() + '?id=' + state.captchaId"
                                            alt=""
                                        />
                                    </el-col>
                                </el-row>
                            </el-form-item>
                            <el-checkbox v-model="form.keep" :label="t('adminLogin.Hold session')" size="default"></el-checkbox>
                            <el-form-item> 
                                <el-button :loading="form.loading" class="submit-button"  :type="state.issubmit?'info':'primary'" :disabled="state.issubmit"  size="large" @click="onSubmit(formRef)">
                                    {{ t('adminLogin.Sign in') }}
                                </el-button>
                            </el-form-item>
                        </el-form>
                        <p class="copyright">{{state.logininfo.login_copyright}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, onBeforeUnmount, reactive, ref, nextTick } from 'vue'
import * as pageBubble from '/@/utils/pageBubble'
import type { ElForm, ElInput } from 'element-plus'
import { ElNotification } from 'element-plus'
import { useI18n } from 'vue-i18n'
import { editDefaultLang } from '/@/lang/index'
import { useConfig } from '/@/stores/config'
import { useAdminInfo } from '/@/stores/adminInfo'
import { login,getBgImage } from '/@/api/backend'
import { buildCaptchaUrl } from '/@/api/common'
import { uuid } from '../../utils/random'
import { validatorPassword, validatorAccount } from '/@/utils/validate'
import router from '/@/router'
var timer: NodeJS.Timer

const config = useConfig()
const adminInfo = useAdminInfo()

const state = reactive({
    showCaptcha: false,
    captchaId: uuid(),
    logininfo:{},
    issubmit:true
})

const changeinput = () => { 
    if(!form.username||!form.password){
        state.issubmit=true
    }else{
        state.issubmit=false
    }
}
const onChangeCaptcha = () => {
    form.captcha = ''
    state.captchaId = uuid()
}

const formRef = ref<InstanceType<typeof ElForm>>()
const usernameRef = ref<InstanceType<typeof ElInput>>()
const passwordRef = ref<InstanceType<typeof ElInput>>()
const captchaRef = ref<InstanceType<typeof ElInput>>()
const form = reactive({
    username: '',
    password: '',
    captcha: '',
    keep: false,
    loading: false,
    captcha_id: '',
})

const { t } = useI18n()

// 表单验证规则
const rules = reactive({
    username: [
        {
            required: true,
            message: t('adminLogin.Please enter an account'),
            trigger: 'blur',
        },
        {
            validator: validatorAccount,
            trigger: 'blur',
        },
    ],
    password: [
        {
            required: true,
            message: t('adminLogin.Please input a password'),
            trigger: 'blur',
        },
        {
            validator: validatorPassword,
            trigger: 'blur',
        },
    ],
    captcha: [
        {
            required: true,
            message: t('adminLogin.Please enter the verification code'),
            trigger: 'blur',
        },
        {
            min: 4,
            max: 6,
            message: t('adminLogin.The verification code length must be between 4 and 6 bits'),
            trigger: 'blur',
        },
    ],
})
 
const focusInput = () => {
    if (form.username === '') {
        usernameRef.value!.focus()
    } else if (form.password === '') {
        passwordRef.value!.focus()
    } else if (form.captcha === '') {
        captchaRef.value!.focus()
    }
} 
onMounted(() => {   
    timer = setTimeout(() => {
        pageBubble.init()
    }, 1000) 
     if(!form.username||!form.password){
        state.issubmit=true
    }else{
        state.issubmit=false
    }
    login('get')
        .then((res) => {
            state.showCaptcha = res.data.captcha
            nextTick(() => {
                focusInput()
            })
        })
        .catch((err) => {
            console.log(err)
        })
        getBgImage()
            .then((res) => { 
                if(res.data){
                    document.getElementsByClassName('bubble')[0].style.background='url('+res.data.background_image+') center / cover no-repeat'
                    state.logininfo=res.data 
                }
            })
            .catch((err) => {
                console.log(err)
            })
       
})

onBeforeUnmount(() => {
    clearTimeout(timer)
    pageBubble.removeListeners()
}) 
const onSubmit = (formEl: InstanceType<typeof ElForm> | undefined) => { 
    if (!formEl) return
    formEl.validate((valid) => {
        if (valid) {
            form.loading = true
            form.captcha_id = state.captchaId
            login('post', form)
                .then((res) => {
                    form.loading = false
                    adminInfo.$state = res.data.userinfo
                    ElNotification({
                        message: res.msg,
                        type: 'success',
                    })
                    router.push({ path: res.data.routePath })
                })
                .catch((err) => {
                    onChangeCaptcha()
                    form.loading = false
                })
        } else {
            onChangeCaptcha()
            return false
        }
    })
}
</script>

<style scoped lang="scss">
.switch-language {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1;
}
.bubble {
    overflow: hidden;
    background: url(/@/assets/bg.jpg) repeat;
}
.form-item-icon {
    height: auto;
}
.login {
    position: absolute;
    top: 0;
    display: flex;
    width: 100vw;
    height: 100vh;
    align-items: center;
    justify-content: center;
    .login-box { 
        width: 378px;
        padding: 0;
        margin-bottom: 60px;
    }
    .head { 
        padding-bottom: 40px;
        .loginlogo {
            display: block;
            width: 184px;
            margin: 0 auto;
            user-select: none;
        }
    }
    .form {
        background: #fff; 
        position: relative;box-shadow: 0px 4px 16px 0px rgba(20,22,26,0.08);
border-radius: 4px;
        .title {
           text-align: center;
            user-select: none;
            font-size: 20px; 
            font-weight: bold;
            color: #14161A;
            line-height: 30px;
            padding: 40px 0;
        }
        .content {
            padding:0  40px  40px;
        }
        .copyright{
            font-size: 12px;  
            color: #909297;
            line-height: 18px;
            text-align: center;
        }
        .submit-button {
            width: 100%;
            letter-spacing: 2px;
            font-weight: 500;
            margin: 20px 0 15px;

        }  
    }
}

@media screen and (max-width: 720px) {
    .login {
        display: flex;
        align-items: center;
        justify-content: center;
        .login-box {
            width: 340px; 
        }
    }
}
.chang-lang :deep(.el-dropdown-menu__item) {
    justify-content: center;
}
.content :deep(.el-input__prefix) {
    display: flex;
    align-items: center;
}
.captcha-img {
    width: 100%;
}
</style>
