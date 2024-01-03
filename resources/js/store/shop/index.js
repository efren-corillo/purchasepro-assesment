import axios from 'axios'
import { defineStore } from 'pinia'
import { getAllCatalogs } from '@/js/api/catalog'
import { addProductToCart, getAllCartItems } from '@/js/api/product'

export const shopStore = defineStore( 'catalog-store', {
  state: () => ({
    cartItems: [],
  }),
  getters: {
    cart_items : state => state.cartItems,
  },
  actions: {
    async getAllCatalogs() {

      const { data } = await getAllCatalogs()

      if ( data ) {
        return data
      } else {
        return []
      }
    },
    addToCart(id, quantity) {
      addProductToCart( { 'id': id, 'quantity': 1 } )
        .then( res => {
          if(res) {
            console.log(res)
            this.getAllCartItems()
          }
        } )
    },
    async getAllCartItems() {
      const { data } = await getAllCartItems()

      if (data !== null) {
        const { cartItems } = data

        this.cartItems = cartItems

        return cartItems;
      }


    }
  }
} )