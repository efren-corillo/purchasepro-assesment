<template>
  <main class="mx-auto max-w-2xl px-4 lg:max-w-7xl lg:px-8">
    <div class="border-b border-gray-200 pb-10 pt-24">
      <h1 class="text-4xl font-bold tracking-tight text-gray-900 capitalize">{{ catalogName.name }} products</h1>
    </div>

    <div class="pb-24 pt-12 lg:grid lg:grid-cols-3 lg:gap-x-8 xl:grid-cols-4">
      <aside>
        <h2 class="sr-only">Filters</h2>

        <button type="button" class="inline-flex items-center lg:hidden" @click="mobileFiltersOpen = true">
          <span class="text-sm font-medium text-gray-700">Filters</span>
          <PlusIcon class="ml-1 h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true"/>
        </button>

        <div class="hidden lg:block">
          <div class="space-y-10 divide-y divide-gray-200">
            <div class="space-y-3 pt-6">
              <div v-for="catalog in catalogs" :key="catalog.id" class="flex items-center">
                <div>
                  <div
                    @click="reloadByCatalog(catalog.id)"
                    class="px-4 py-3 text-blue-950 text-sm rounded  font-bold uppercase cursor-pointer">
                    {{ catalog.name }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <section aria-labelledby="product-heading" class="mt-6 lg:col-span-2 lg:mt-0 xl:col-span-3">
        <h2 id="product-heading" class="sr-only">Products</h2>

        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:gap-x-8 xl:grid-cols-3">
          <div v-for="product in products" :key="product.id"
               class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white"
          >
            <product-card :product="product"/>
          </div>
        </div>
      </section>
    </div>
  </main>

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
  </main>
</template>

<script setup>
  import { ref, onMounted, onBeforeMount } from 'vue'
  import { useRoute, useRouter } from "vue-router"
  import { ChevronDownIcon, FunnelIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
  import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'

  import ProductCard from '@/js/components/ProductCard.vue'

  import { shopStore } from '@/js/store/shop'
  import { listProductByCatalogId } from '@/js/api/product'
  import { getCatalog } from '@/js/api/catalog'


  const route = useRoute()

  const catalog = shopStore()
  const catalogId = ref( Number( route.params.id ) )
  const catalogs = ref( [] )
  const catalogName = ref( "" )

  const products = ref( [] )

  const breadcrumbs = [ { id: 1, name: 'Catalog', href: '/catalog' } ]

  const getProductsByCatalog = ( id ) => {
    listProductByCatalogId( id ).then( res => {
      if ( res.data ) {
        products.value = res.data
      }
    } )
  }

  const reloadByCatalog = (id) => {
    getProductsByCatalog(id)
    catalogId.value = id
    getCatalogCurrentName()
  }

  const getCatalogCurrentName = () => {
    catalogName.value = catalogs.value.find( catalog => catalog.id === catalogId.value )
  }

  onBeforeMount( () => {
    catalog.getAllCatalogs()
      .then( res => {
        if ( res.catalogs ) {
          catalogs.value = res.catalogs
          getCatalogCurrentName()
        }
      } )
  } )

  onMounted( () => {
    getProductsByCatalog( catalogId.value )
  } )
</script>
