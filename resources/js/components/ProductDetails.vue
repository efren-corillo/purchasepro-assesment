<template>
  <div>
    <main class="mx-auto max-w-7xl sm:px-6 sm:pt-16 lg:px-8">
      <div class="mx-auto max-w-2xl lg:max-w-none">
        <!-- Product -->
        <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
          <!-- Image gallery -->
          <TabGroup as="div" class="flex flex-col-reverse">
            <TabPanels class="aspect-h-1 aspect-w-1 w-full">
              <TabPanel>
                <img :src="product.image" :alt="product.title"
                     class="h-full w-full object-cover object-center sm:rounded-lg"/>
              </TabPanel>
            </TabPanels>
          </TabGroup>

          <!-- Product info -->
          <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
            <h1 class="text-3xl text-left capitalize font-bold tracking-tight text-gray-900">{{ product.title }}</h1>

            <div class="mt-3 text-left">
              <h2 class="sr-only">Product information</h2>
              <p class="text-3xl tracking-tight text-gray-900">$ {{ product.price }}</p>
            </div>

            <!-- Reviews -->
            <div v-if="typeof product.rating === 'object'" class="mt-3 text-gray-500">
              <h3 class="sr-only">Rating: </h3>
              <div class="flex items-center">
                <div class="flex items-center">
                  <h3 class="text-left">Rating: </h3>
                  <StarIcon v-for="rating in [0, 1, 2, 3, 4]" :key="rating"
                            :class="[product.rating.rate > rating ? 'text-indigo-500' : 'text-gray-300', 'h-5 w-5 flex-shrink-0']"
                            aria-hidden="true"/>
                </div>
                <p class="sr-only">{{ product.rating.rate }} out of 5 stars</p>
              </div>
              <div class="flex items-center">
                Reviews: {{ product.rating.count }}
              </div>
            </div>
            <div v-else class="mt-3 text-gray-500">
              <h3 class="sr-only">Rating: </h3>
              <div class="flex items-center">
                <div class="flex items-center">
                  <h3 class="text-left">Rating: </h3>
                  <StarIcon v-for="rating in [0, 1, 2, 3, 4]" :key="rating"
                            :class="[obj.rate > rating ? 'text-indigo-500' : 'text-gray-300', 'h-5 w-5 flex-shrink-0']"
                            aria-hidden="true"/>
                </div>
                <p class="sr-only">{{ obj.rate }} out of 5 stars</p>
              </div>
              <div class="flex items-center">
                Reviews: {{ obj.count }}
              </div>
            </div>

            <div class="mt-6">
              <h3 class="sr-only">Description</h3>

              <div class="space-y-6 text-base text-left text-gray-700" v-html="product.description"/>
            </div>

            <form class="mt-6">

              <div class="mt-10 flex">
                <button
                  type="button"
                  class="flex max-w-0 flex-1 items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full"
                  @click="addToCart(product.id)"
                >
                  <ShoppingCartIcon class="h-6 w-6 flex-shrink-0" aria-hidden="true"/>
                </button>

                <button type="button"
                        class="ml-4 flex items-center justify-center rounded-md px-3 py-3 text-gray-400 hover:bg-gray-100 hover:text-gray-500">
                  <HeartIcon class="h-6 w-6 flex-shrink-0" aria-hidden="true"/>
                  <span class="sr-only">Add to favorites</span>
                </button>
              </div>
            </form>

            <section aria-labelledby="details-heading" class="mt-12">
              <h2 id="details-heading" class="sr-only">Additional details</h2>

              <div class="divide-y divide-gray-200 border-t">
                <Disclosure as="div" v-for="detail in product.details" :key="detail.name" v-slot="{ open }">
                  <h3>
                    <DisclosureButton class="group relative flex w-full items-center justify-between py-6 text-left">
                      <span :class="[open ? 'text-indigo-600' : 'text-gray-900', 'text-sm font-medium']">{{
                          detail.name
                        }}</span>
                      <span class="ml-6 flex items-center">
                        <PlusIcon v-if="!open" class="block h-6 w-6 text-gray-400 group-hover:text-gray-500"
                                  aria-hidden="true"/>
                        <MinusIcon v-else class="block h-6 w-6 text-indigo-400 group-hover:text-indigo-500"
                                   aria-hidden="true"/>
                      </span>
                    </DisclosureButton>
                  </h3>
                  <DisclosurePanel as="div" class="prose prose-sm pb-6">
                    <ul role="list">
                      <li v-for="item in detail.items" :key="item">{{ item }}</li>
                    </ul>
                  </DisclosurePanel>
                </Disclosure>
              </div>
            </section>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
  import { ref, onBeforeMount } from 'vue'
  import { shopStore } from '@/js/store/shop'

  import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    TabGroup,
    TabPanel,
    TabPanels
  } from '@headlessui/vue'
  import { StarIcon } from '@heroicons/vue/20/solid'
  import { HeartIcon, MinusIcon, PlusIcon } from '@heroicons/vue/24/outline'
  import { ShoppingCartIcon } from '@heroicons/vue/24/solid'

  const store = shopStore()

  const props = defineProps( [ 'product' ] )

  const emit = defineEmits( [ 'close' ] )

  const obj = ref( {
    rating: 0,
    count: 0
  } )

  const addToCart = async ( id ) => {
    await store.addToCart( id, 1 )
    emit( 'close' )
  }

  onBeforeMount(() => {
    if( typeof props.product.rating !== 'object' )
      obj.value = JSON.parse(props.product.rating);
  })

</script>
