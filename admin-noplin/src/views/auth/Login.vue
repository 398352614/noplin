<template>
  <div class="login">
    <div class="tittle">
      <div class="tittle-main">Noplin</div>
      <div>{{ $t('login.text.secondTitle') }}</div>
    </div>
    <div class="form">
      <el-form ref="loginFormRef" :model="loginForm" :rules="loginRules">
        <el-form-item prop="username">
          <el-input v-model="loginForm.username" :placeholder="$t('login.form.enterEmail')" prefix-icon="Avatar">
          </el-input>
        </el-form-item>
        <el-form-item prop="password">
          <el-input v-model="loginForm.password" :placeholder="$t('login.form.enterPassword')" prefix-icon="Lock"
                    show-password type="password"
          >
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button :loading="loading" class="button" type="success"
                     @keyup.enter.native="handleLogin(loginFormRef)" @click.native="handleLogin(loginFormRef)">
            {{ $t('login.button.login') }}
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>

</template>

<script lang="ts" setup>
import {reactive, ref} from 'vue'
import {login} from "@/api/auth";
import {validPassword, validUsername} from "@/utils/validator";
import {tokenStore} from "@/stores/token-store";
import {FormInstance} from "element-plus";
import router from "@/router/index";
import {LoginApi} from "@/interface/auth-interface";
import i18n from "@/utils/i18n";

const {t} = i18n.global;
// 响应式状态
const loginFormRef = ref<FormInstance>()
const loginForm = reactive({
  username: "",
  password: ""
})
const loading = ref(false)
//验证用户名
const validateUsername = (rule: any, value: any, callback: any) => {
  if (!validUsername(value)) {
    callback(new Error(t('login.error.emptyEmail')))
  } else {
    callback()
  }
}
//验证密码
const validatePassword = (rule: any, value: string, callback: any) => {
  if (!validPassword(value)) {
    callback(new Error(t('login.error.emptyEmail')))
  } else {
    callback()
  }
  if (value.length < 6) {
    callback(new Error(t('login.error.shortPassword')))
  } else {
    callback()
  }
}

//表单验证
const loginRules = reactive({
  username: [{required: true, trigger: 'blur', validator: validateUsername}],
  password: [{required: true, trigger: 'blur', validator: validatePassword}]
})

//处理登录
const handleLogin = (ElForm: FormInstance) => {
  ElForm.validate((valid) => {
        if (valid) {
          loading.value = true
          login(loginForm).then(
              (res: LoginApi | undefined) => {
                if (res != undefined) {
                  res.access_token != undefined && tokenStore().set(res.access_token);
                  router.push({name: "home"})
                }
              }
          ).finally(
              () => {
                loading.value = false
              }
          )
        } else {
          return false
        }
      }
  )

}
</script>

<style lang="less">
.login {
  width: 50vw;

  .tittle {
    position: absolute;
    top: 25%;
    right: 15%;
    font-size: 25px;
    font-weight: 600;
    text-align: left;
    width: 20vw;

    .tittle-main {
      color: var(--el-color-primary);
    }
  }

  .form {
    position: absolute;
    top: 40%;
    right: 15%;
    margin: auto;
    display: flex;

    .el-form-item {
      position: relative;
      width: 20vw;
    }

    .button {
      position: relative;
      width: 20vw;
    }
  }


}
</style>