<template>
  <div>
    Welcome to Purchase Pro
  </div>
</template>

<script setup>
import { onMounted } from 'vue';

onMounted( () => {
  axios.get('/api/test').then((res) => {
    console.log( res )
    console.log( typeof res.status, res.status, res.data.message )
  })
} );
</script>

<style lang="scss" scoped>

</style>