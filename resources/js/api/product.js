import request from '@/js/util/request'

export function listProductByCatalogId(id) {
  return request( {
    url: `/api/list-products-by-catalog/${id}`,
    method: 'get'
  } )
}