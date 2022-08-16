<template>
    <div class ="change-language">
        <el-radio-group v-model="radio" class="radio">
            <el-radio label="1" size="large">中文</el-radio>
            <el-radio label="2" size="large">english</el-radio>
        </el-radio-group>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
const radio = ref('1');
</script>

<style lang="scss">
.change-language{
    margin: auto;
}
.radio{
    color: blue;
}
</style>