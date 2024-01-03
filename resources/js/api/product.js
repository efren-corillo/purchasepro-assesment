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

export function getAllCartItemsWithProducts( ) {
  return request( {
    url: '/api/cart-items-with-product',
    method: 'get'
  } )
}

export function confirmOrderApi( query ) {
  return request( {
    url: '/api/confirm-order',
    method: 'post',
    params: query
  } )
}

export function updateCartQuantity( query ) {
  return request( {
    url: '/api/update-cart-quantity',
    method: 'post',
    params: query
  } )
}

export function deleteCartItemApi( id ) {
  return request( {
    url: '/api/cart-items/' + Number(id),
    method: 'delete',
  } )
}
