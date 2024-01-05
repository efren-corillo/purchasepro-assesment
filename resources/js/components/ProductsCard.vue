<template>
  <div class="aspect-h-4 aspect-w-3 bg-gray-200 sm:aspect-none group-hover:opacity-75 sm:h-52">
    <img :src="product.image" :alt="product.title"
         class="h-full w-full object-cover object-center sm:h-full sm:w-full"/>
  </div>
  <div class="flex flex-1 flex-col space-y-2 p-4">
    <h3 class="text-sm font-roboto text-gray-900 capitalize">
      <span @click="showProductDetail(product.id)">
        <span aria-hidden="true" class="absolute inset-0"/>
        {{ product.title }}
      </span>
    </h3>
    <p class="text-sm text-gray-500">{{ product.description }}</p>
    <div class="flex flex-row place-content-between">
      <p class="basis-1/2 font-medium text-gray-900">${{ product.price }}</p>
      <div
        @click="addToCart(product.id)"
        class="pp_card__button text-center"
      >
        <ShoppingCartIcon class="size-6 mx-4"/>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { createApp, h, getCurrentInstance } from 'vue'
  import { shopStore } from '@/js/store/shop'
  import { ShoppingCartIcon } from '@heroicons/vue/24/solid'

  import ProductDetails from '@/js/components/ProductDetails.vue'

  const store = shopStore()
  const { proxy } = getCurrentInstance();
  const props = defineProps( [ 'product' ] )

  const addToCart = ( id ) => {
    store.addToCart( id, 1 )
  }

  const showProductDetail = ( id ) => {
    const container = document.createElement('div')

    let app = createApp({
      render: () => h(ProductDetails, {
        product: props.product,
        onClose: () => {
          proxy.$swal.close()
        }
      })
    })

    app.mount(container);

    proxy.$swal.fire({
      html: container,
      showConfirmButton: false,
    }).then(() => {
      app.unmount(container);
    })
  }
</script>
