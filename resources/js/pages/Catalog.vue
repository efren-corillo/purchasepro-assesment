<template>
  <div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-gray-900 uppercase">catalogs</h2>

      <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
        <div v-for="catalog in catalogs" class="group relative">
          <catalog-product :catalog="catalog"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onBeforeMount, onMounted } from 'vue'

  import CatalogProduct from '@/js/components/CatalogCard.vue'

  import { shopStore } from '@/js/store/shop'

  const catalog = shopStore();

  const catalogs = ref( [] )

  onBeforeMount( () => {
    catalog.getAllCatalogs().then(res => {
      catalogs.value = res.catalogs
    })
  } )

</script>
