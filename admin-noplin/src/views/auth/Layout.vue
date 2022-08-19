<script lang="ts" setup>
import Login from './Login.vue';
import ChangeLanguage from './ChangeLanguage.vue';</script>

<template>
  <div class="auth-layout">
    <el-container>
      <el-aside>
        <img alt="noplin" class="img-box" src="@/assets/logo.png"/>
      </el-aside>
      <el-main>
        <Login/>
      </el-main>
      <el-footer>
        <ChangeLanguage/>
      </el-footer>
    </el-container>
  </div>
</template>

<style type="scss">
.el-aside {
  width: 50vw;
  height: 100vh;
  background-color: var(--el-color-primary);
  display: flex;
  align-items: center;
  justify-content: center;
}

.img-box {
  display: table-cell;
}

.el-main {
  width: 50vw;
  height: 90vh;
  display: flex;
}

.el-footer {
  width: 50vw;
  height: 10vh;
  /*opacity: 0.5;*/
  display: flex;
  align-items: center;
}
</style>
