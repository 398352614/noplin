<script setup type="ts">
import Navbar from "./Navbar.vue"</script>

<template>
  <div class="home-layout">
    <el-container class="layout" direction="horizontal">
      <el-aside>
        left side
      </el-aside>
      <el-container>
        <el-container>
          <el-header>
            <Navbar/>
          </el-header>
          <el-main>
            main
          </el-main>
          <el-footer>
            footer
          </el-footer>
        </el-container>
<!--        <el-aside>-->
<!--          right side-->
<!--        </el-aside>-->
      </el-container>

    </el-container>
  </div>
</template>


<style type="scss">
.layout {
  height: 100vh;
}

.el-main{
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2)inset, 0 6px 20px 0 rgba(0, 0, 0, 0.19) inset;
}

.el-header {
  height: 2rem;
  background-color: var(--el-color-second);
}

.el-aside {
  height: 100vh;
  width: 20vw;
  background-color: var(--el-color-primary);
}

.el-footer {
  height: 2rem;
  background-color:  var(--el-border-color-darker);
}

</style>

