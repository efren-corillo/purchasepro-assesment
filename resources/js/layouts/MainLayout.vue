<template>
  <div class="pp-app">
    <top-navbar/>
    <div
      class="relative pt-16 pb-32"
      style="min-height: 100vh;"
    >
      <router-view v-slot="{ Component }" class="pt-16 pb-10 flex content-center items-center justify-center">
        <transition name="fade">
          <component :is="Component"/>
        </transition>
      </router-view>
    </div>
  </div>

  <ToastNotification />
</template>

<script setup>
  import TopNavbar from '../components/TopNavbar.vue'
  import ToastNotification from '@/js/components/ToastNotification.vue'
</script>