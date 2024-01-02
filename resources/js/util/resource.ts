import axios from 'axios'

const CancelToken = axios.CancelToken
import request from './request'

/**
 * Simple REST-ful resource class
 */
class Resource {
  uri: any
  timeout: string | number
  cancelToken: any
  cancelCaller: any

  constructor(uri: string, timeout = '') {
    this.uri = uri
    this.timeout = timeout == '' ? 60000 : timeout
  }

  createCancelToken() {
    this.cancelToken = new CancelToken(cancel => {
      this.cancelCaller = cancel
    })
  }

  cancelRequest() {
    this.cancelCaller()
  }

  list(query: any) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      timeout: Number(this.timeout), // Request timeout
      params: query,
      cancelToken: this.cancelToken,
    })
  }

  get(id: number, resource = '') {
    let payload = {
      url: '/' + this.uri + '/' + id,
      method: 'get',
      timeout: Number(this.timeout), // Request timeout
    }

    if (resource != '') {
      payload['params'] = resource
    }

    return request(payload)
  }

  store<T>(resource: T) {
    return request({
      url: '/' + this.uri,
      method: 'post',
      data: resource,
      timeout: Number(this.timeout), // Request timeout
    })
  }

  update<T>(id: number, resource: T) {
    return request({
      url: '/' + this.uri + '/' + id,
      method: 'put',
      data: resource,
      timeout: Number(this.timeout), // Request timeout
    })
  }

  destroy(id: number, resource = '') {
    let payload: { [key: string]: any } = {
      url: '/' + this.uri + '/' + id,
      method: 'delete',
      timeout: Number(this.timeout), // Request timeout
    }

    if (resource != '') {
      payload.data = resource
    }

    return request(payload)
  }
}

export {Resource as default}
