import request from '@/js/util/request'

export function getAllCatalogs() {
  return request( {
    url: '/api/catalog',
    method: 'get'
  } )
}
export function getCatalog(id) {
  return request( {
    url: `/api/catalog/${id}`,
    method: 'get'
  } )
}