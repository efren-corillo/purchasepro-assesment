<template>
  <div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-gray-900">Products</h2>

      <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
        <div v-for="product in products" class="group relative">
          <catalog-product :product="product"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import CatalogProduct from '@/js/components/CatalogProduct.vue'

  const products = ref( [] )

  onMounted( () => {
    // get products from api.
    products.value = [
      {
        name: 'Basic Tee',
        images: {
          src: [
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg',
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-02.jpg',
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-03.jpg'
          ],
          defaultImageIndex: 0,
        },
        reviews: [{
          count: 3,
          overallRating: 5,
          one: 0,
          two: 0,
          three: 0,
          four: 0,
          five: 5,
        }],
        types: 'color',
        variations: [
          'black',
          'white'
        ],
        price: 30000
      },
      {
        name: 'Basic Tee 2',
        images: {
          src: [
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg',
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-02.jpg',
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-03.jpg'
          ],
          defaultImageIndex: 0,
        },
        reviews: [{
          count: 3,
          overallRating: 5,
          one: 0,
          two: 0,
          three: 0,
          four: 0,
          five: 5,
        }],
        types: 'color',
        variations: [
          'white',
          'black'
        ],
        price: 30000
      },
      {
        name: 'Basic Tee 3',
        images: {
          src: [
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg',
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-02.jpg',
            'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-03.jpg'
          ],
          defaultImageIndex: 0,
        },
        reviews: [{
          count: 3,
          overallRating: 5,
          one: 0,
          two: 0,
          three: 0,
          four: 0,
          five: 5,
        }],
        types: 'color',
        variations: [
          'red',
          'black',
          'white'
        ],
        price: 30000
      }
    ]
  } )
</script>
