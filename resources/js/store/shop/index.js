import axios from 'axios'
import { defineStore } from 'pinia'
import { getAllCatalogs } from '@/js/api/catalog'
import { addProductToCart, getAllCartItems, getAllCartItemsWithProducts } from '@/js/api/product'

export const shopStore = defineStore( 'catalog-store', {
  state: () => ( {
    cartItems: [],
    notification: {
      message: '',
      secondaryMessage: '',
      status: 'success'
    },
    triggerNotification: false
  } ),
  getters: {
    cart_items: state => state.cartItems
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
    addToCart( id, quantity ) {
      addProductToCart( { 'id': id, 'quantity': 1 } )
        .then( res => {
          if ( res ) {
            this.getAllCartItems().then( res => {
              this.notification = {
                message: 'Successfully Added Product to Cart',
                status: 'success'
              }

              this.triggerNotification = true
            } )
          }
        } )
    },
    setTriggerNotificationFalse() {
      this.triggerNotification = false
    },
    triggerNotificationAction(message, status){
      this.notification = {
        message: message,
        status: status
      }

      this.triggerNotification = true
    },
    async getAllCartItems() {
      const { data } = await getAllCartItems()

      if ( data !== null ) {
        const { cartItems } = data

        this.cartItems = cartItems

        return cartItems
      }
    },
    async getAllCartItemsWithProduct() {
      const { data } = await getAllCartItemsWithProducts()

      if ( data !== null ) {
        const { cartItems } = data

        this.cartItems = cartItems

        return cartItems
      }
    }
  }
} )