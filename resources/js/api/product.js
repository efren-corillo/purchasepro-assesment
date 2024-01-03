import request from '@/js/util/request'

export function listProductByCatalogId( id ) {
  return request( {
    url: `/api/list-products-by-catalog/${id}`,
    method: 'get'
  } )
}


export function addProductToCart( query ) {
  return request( {
    url: '/api/add-product-to-cart',
    method: 'post',
    params: query
  } )
}

export function getAllCartItems( ) {
  return request( {
    url: '/api/cart-items',
    method: 'get'
  } )
}
