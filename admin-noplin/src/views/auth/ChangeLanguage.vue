<template>
  <div class="change-language">
    <el-radio-group v-model="radio" @change="changeLanguage">
      <el-radio label="1" size="large">中文</el-radio>
      <el-radio label="2" size="large">english</el-radio>
    </el-radio-group>
  </div>
</template>

<script lang="ts" setup>
import {ref} from 'vue';
import {configStore} from "@/stores/config-store";
import i18n from "@/utils/i18n";

const radio = ref('1');

function changeLanguage() {
  let language: "cn" | "en" = 'cn'
  switch (radio.value) {
    case '1':
      language = 'cn';
      break
    case '2':
      language = 'en';
      break
  }
  i18n.global.locale = language
  configStore().language = language;
}
</script>

<style lang="scss">
.change-language {
  margin: auto;
}
</style>