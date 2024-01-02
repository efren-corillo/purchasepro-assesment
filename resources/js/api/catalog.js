import request from '@/js/util/request'

export function getAllCatalogs() {
  return request( {
    url: '/api/catalog',
    method: 'get'
  } )
}