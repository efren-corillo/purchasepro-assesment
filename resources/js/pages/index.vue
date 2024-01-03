<template>
  <div class="container relative mx-auto">
    <div class="items-center flex flex-wrap">
      <div class="w-full lg:w-full px-4 ml-auto mr-auto text-left">
        <div class="pr-12">
          <h1 class="text-gray-950 font-semibold text-5xl">
            Empowering your<br> purchasing experience.
          </h1>
          <div
            class="absolute mt-6 bg-yellow-300 z-8 border-r-10 border-x-0 leading-6 p-0 tracking-normal whitespace-nowrap text-sm"
            id="slide-2-layer-13"
            style="transition: none 0s ease 0s; font-weight: 400; min-height: 7px; min-width: 217px; max-height: 7px; max-width: 217px; opacity: 1; transform: translate3d(0px, 0px, 0px); transform-origin: 50% 50% 0px;"></div>
          <p class="mt-14 text-lg text-gray-500">
            Purchasing supplies for your properties has never been this easy. The PurchasePro<br>marketplace simplifies
            your supply procurement process to give you the control <br>and efficiency you need for superb property
            management.
          </p>

          <router-link to="/catalog">
            <p class="inline-block rounded-md border border-transparent bg-indigo-600 mt-14 px-8 py-3 text-center font-medium text-white hover:bg-indigo-700">
              Browse Catalog Now
            </p>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { onMounted } from 'vue'

  onMounted( () => {
    axios.get( '/api/test' ).then( ( res ) => {
      console.log( res )
      console.log( typeof res.status, res.status, res.data.message )
    } )
  } )
</script>

<style lang="scss" scoped>

</style>