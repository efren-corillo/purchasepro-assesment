<template>
  <div
    class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-white lg:aspect-none cursor-pointer group-hover:bg-amber-200 lg:h-32 flex flex-col items-center justify-center p-4"
  >
    <img :src="catalog.icon" :alt="catalog.name" class="h-16 w-16 object-center object-cover lg:h-20 lg:w-20">
    <h3 class="mt-2 text-sm text-gray-700 text-center">
      {{ catalog.name }}
    </h3>
  </div>
</template>


<script setup>
  const props = defineProps( [ 'catalog' ] )

  console.log( props.catalog )
</script>

<style lang="scss" scoped>

</style>