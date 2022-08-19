<template>
  <div class="login">
    <div class="tittle">
      <div class="tittle-main">Noplin</div>
      <div>诺普林笔记</div>
    </div>
    <div class="form">
      <el-form ref="loginFormRef" :model="loginForm" :rules="loginRules">
        <el-form-item prop="username">
          <el-input v-model="loginForm.username" placeholder="请输入用户名" prefix-icon="Avatar">
          </el-input>
        </el-form-item>
        <el-form-item prop="password">
          <el-input v-model="loginForm.password" placeholder="请输入密码" prefix-icon="Lock" show-password type="password"
          >
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button class="button" type="success" @keyup.enter.native="handleLogin(loginFormRef)"
                     @click.native="handleLogin(loginFormRef)" :loading="loading">
            登录
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>

</template>

<script lang="ts" setup>
import {reactive, ref} from 'vue'
import {login} from "@/api/auth";
import {validUsername} from "@/utils/validator";
import {tokenStore} from "@/stores/token-store";
import {FormInstance} from "element-plus";
import router from "@/router";

// 响应式状态
const loginFormRef = ref<FormInstance>()
const loginForm = reactive({
  username: "",
  password: ""
})
const loading=ref(false)

//验证用户名
const validateUsername = (rule: any, value: any, callback: any) => {
  if (!validUsername(value)) {
    callback(new Error('Please enter the correct user name'))
  } else {
    callback()
  }
}
//验证密码
const validatePassword = (rule: any, value: string | any[], callback: any) => {
  if (value.length < 6) {
    callback(new Error('The password can not be less than 6 digits'))
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
                if (res == undefined) return
                tokenStore().set(res.access_token);
                router.push({name: "home"})
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