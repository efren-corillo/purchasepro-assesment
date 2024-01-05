<template>
  <router-link :to="`products/${catalog.id}`">
    <div
      class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-md bg-white lg:aspect-none cursor-pointer group-hover:bg-amber-200 lg:h-32 flex flex-col items-center justify-center p-4"
    >
      <img :src="catalog.icon" :alt="catalog.name" class="w-auto object-center object-cover">
      <h3 class="mt-2 text-sm text-gray-700 text-center uppercase">
        {{ catalog.name }}
      </h3>
    </div>
  </router-link>
</template>


<script setup>
  const props = defineProps( [ 'catalog' ] )
</script>
