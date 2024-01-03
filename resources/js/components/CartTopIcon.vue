<template>
  <!-- Cart Dialog -->
  <button class="pp_top_nav--right-button flex items-center" type="button">
    <ShoppingBagIcon aria-hidden="true" class="h-6 w-6"/>
    <span class="ml-2">{{ totalItems }}</span>
  </button>
</template>

<script setup>
  import { ref, computed, watch, onMounted } from 'vue'
  import { ShoppingBagIcon } from '@heroicons/vue/24/solid'
  import { shopStore } from '@/js/store/shop'

  const store = shopStore()

  const cartItems = computed( () => {
    return store.cartItems
  } )

  const totalItems = ref( 0 )

  watch( cartItems, ( newCartItems, oldCartItems ) => {
    totalItems.value = 0;
    newCartItems.forEach((item, key) => {
      totalItems.value = totalItems.value + item.quantity
    })
  }, { deep: true } )

  onMounted( () => {
    // get all cartItem
    store.getAllCartItems()
  } )
</script>
