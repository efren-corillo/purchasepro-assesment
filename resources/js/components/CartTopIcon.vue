<template>
  <!-- Cart Button -->
  <router-link to="/cart">
    <button class="pp_top_nav--right-button flex items-center" type="button">
      <ShoppingBagIcon aria-hidden="true" class="h-6 w-6"/>
      <span class="ml-2">{{ totalItems }}</span>
      <span class="absolute bottom-full mb-2 hidden group-hover:block">
        View Your Cart
    </span>
    </button>
  </router-link>


  <!-- Cart Dialog -->
  <dialog class="backdrop:bg-gray-50">
    <form method="dialog">
      <!-- ... -->
    </form>
  </dialog>
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
    totalItems.value = 0
    newCartItems.forEach( ( item, key ) => {
      totalItems.value = totalItems.value + item.quantity
    } )
  }, { deep: true } )

  onMounted( () => {
    // get all cartItem
    store.getAllCartItems()
  } )
</script>

<style lang="scss">
  .group:hover .group-hover\:block {
    display: block;
  }

  /* Tooltip specific styles */
  .tooltip {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    padding: .5rem 1rem;
    border-radius: .25rem;
    background-color: black;
    color: white;
    font-size: .875rem;
    line-height: 1.25rem;
    z-index: 10;
  }
</style>