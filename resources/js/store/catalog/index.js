import axios from 'axios'
import { defineStore } from 'pinia'
import { getAllCatalogs } from '@/js/api/catalog'

export const catalogStore = defineStore( 'catalog-store', {
  actions: {
    async getAllCatalogs() {

      const { data } = await getAllCatalogs()

      if ( data ) {
        return data
      } else {
        return []
      }
    }
  }
} )