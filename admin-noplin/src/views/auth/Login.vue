<template>
    <div class="login">
        <div class="tittle">
            <div class="tittle-main">noplin</div>
            <div>诺普林笔记</div>
        </div>
        <div class="form">
            <el-form :model="loginForm">
                <el-form-item prop="username">
                    <el-input v-model="loginForm.username" placeholder="请输入用户名" prefix-icon="Avatar">
                    </el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input v-model="loginForm.password" placeholder="请输入密码" prefix-icon="Lock" type="password" show-password
                        >
                    </el-input>
                </el-form-item>
            </el-form>
        </div>
        <div>
            <el-button type="success" class="button">
                登录
            </el-button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loginForm: {
                username: "",
                password: ""
            }
        }
    }
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
            color: red;
        }

    }

    .form {
        position: absolute;
        top: 40%;
        right: 15%;

        margin: auto;

        display: flex;

        .el-form-item {
            width: 20vw;
        }
    }

    .button {
        position: absolute;
        top: 60%;
        right: 15%;

        width: 20vw;
    }
}
</style>