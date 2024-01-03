<template>
  <div class="bg-white">
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-baseline justify-between border-b border-gray-200 pb-6 pt-24">
        <h1 class="text-4xl font-roboto tracking-tight text-gray-900 capitalize">{{ catalog.name }} Products</h1>

        <div class="flex items-center">
          <!--        <Menu as="div" class="relative inline-block text-left">
                    <div>
                      <MenuButton class="group inline-flex justify-center text-sm font-medium text-gray-700 hover:text-gray-900">
                        Sort
                        <ChevronDownIcon class="-mr-1 ml-1 h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                         aria-hidden="true"/>
                      </MenuButton>
                    </div>

                    <transition enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                      <MenuItems
                        class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="py-1">
                          <MenuItem v-for="option in sortOptions" :key="option.name" v-slot="{ active }">
                            <a :href="option.href"
                               :class="[option.current ? 'font-medium text-gray-900' : 'text-gray-500', active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">{{
                                option.name
                              }}</a>
                          </MenuItem>
                        </div>
                      </MenuItems>
                    </transition>
                  </Menu>-->

          <button type="button" class="-m-2 ml-5 p-2 text-gray-400 hover:text-gray-500 sm:ml-7">
            <span class="sr-only">View grid</span>
            <Squares2X2Icon class="h-5 w-5" aria-hidden="true"/>
          </button>
          <button type="button" class="-m-2 ml-4 p-2 text-gray-400 hover:text-gray-500 sm:ml-6 lg:hidden"
                  @click="mobileFiltersOpen = true">
            <span class="sr-only">Filters</span>
            <FunnelIcon class="h-5 w-5" aria-hidden="true"/>
          </button>
        </div>
      </div>

      <section aria-labelledby="products-heading" class="pb-24 pt-6">
        <h2 id="products-heading" class="sr-only">Products</h2>

        <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
          <!-- Product grid -->
          <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:col-span-3 lg:gap-x-8">
            <div v-for="product in products" :key="product.id"
                 class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white">
              <product-card :product="product"/>
            </div>
          </div>
        </div>

      </section>
    </main>
  </div>
</template>

<script setup>
  import { ref, onMounted, onBeforeMount } from 'vue'
  import { useRoute, useRouter } from "vue-router"
  import { ChevronDownIcon, FunnelIcon, Squares2X2Icon } from '@heroicons/vue/20/solid'
  import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'

  import ProductCard from '@/js/components/ProductCard.vue'
  import { listProductByCatalogId } from '@/js/api/product'
  import { getCatalog } from '@/js/api/catalog'

  const route = useRoute()

  const catalogId = ref(Number(route.params.id))
  const products = ref( [] )

  const catalog = ref( [] )

  const
    getProductsByCatalog = ( id ) => {
      listProductByCatalogId( id ).then( res => {
        if ( res.data ) {
          products.value = res.data
        }
      } )
    }

  onBeforeMount( () => {
    getCatalog( catalogId.value )
      .then( res => {
        catalog.value = res.data
      } )
  } )

  onMounted( () => {
    getProductsByCatalog( catalogId.value )
  } )
</script>
