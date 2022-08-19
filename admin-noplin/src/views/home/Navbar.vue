<template>
  <div class="navbar">
    <el-icon class="fold" :size="30">
      <CaretLeft/>
    </el-icon>
    header
  </div>
</template>

<style type="scss">
.navbar{
  display: flex;
  align-items: center;
  justify-content: left;
}

.fold{
  color: var(--el-color-white);
  position: relative;
  left: 0;
}
</style>