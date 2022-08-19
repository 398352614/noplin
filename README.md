# noplin
![logo](./admin-noplin/src/assets/logo.png)

[comment]: <> (![Version]&#40;https://img.shields.io/github/v/tag/398352614/noplin&#41;)

[comment]: <> (![Docker Pulls]&#40;https://img.shields.io/docker/pulls/&#41;)

<a href="https://github.com/398352614/noplin/blob/main/LICENSE"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>

## 1 简介
Noplin 是一个免费开源的看板和待办事项网页应用，包含服务端和网页端。由单人开发。

### 1.1 技术栈

- 环境：docker-compose ( 可在 windows, mac, linux 等多平台部署 )
- 前端：语言HTML+CSS+JavaScript, vue3.0框架, element-plus组件库
- 后端：语言php+mysql+nginx+redis, laravel9.0框架

### 1.2 特性
- 开源免费，能够私有部署
- 类似 [`notion`](https://www.notion.so/desktop) 的多视图切换（日历，看板，表格，画廊），及可视化数据库
- 可邮件通知
- 网页端适配手机浏览器
- 支持中文和英文

## 1.3 路线图

### 第一步 前期准备
预计耗时：2周
1. 构建docker-compose文件。
2. 设计logo，编写文档。
3. 编写开发计划，及需求大纲。

### 第二步 骨架构建
预计耗时：4周
1. 前端骨架构建，完成基础功能。登录页面，主页面，与后端通讯等。
2. 后端骨架构建，完成基础功能。用户认证模块，文件上传模块，与前端通讯等。

### 第三步 核心功能构建
预计耗时：8周
1. 制定最小可行性方案，编写需求文档，流程图，状态图。
2. 后端设计数据结构及逻辑，初步完成主流程功能：多视图切换，邮件通知，可视化数据库。
3. 前端完成主流程页面。
4. 前端适配手机浏览器。

### 第四步 后续开发及优化
预计耗时：1年
1. 增加对Markdown的支持。
2. 增加类似 [`notion`](https://www.notion.so/desktop) 的block结构，并对其进行改造。
3. 协同办公支持。利用websocket实现及时响应。
4. 自定义主题。
5. 数据导入导出。

## 部署
`todo`

## 使用方法
`todo`