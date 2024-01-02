import router from '../router'
import axios from 'axios'
import Swal from 'sweetalert2'
import {deleteAllCookies, getTokenCookie} from './cookies'

// Create axios instance
const service = axios.create({
  baseURL: '/',
})

service.defaults.headers.common['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8'
service.defaults.headers.common['Access-Control-Allow-Origin'] = '*'

// Request interceptor
service.interceptors.request.use(
  config => {
    config.headers['Authorization'] = 'Bearer ' + getTokenCookie()
    return config
  },
  error => {
    // Do something with request error
    return Promise.reject(error)
  },
)

// response pre-processing
service.interceptors.response.use(
  response => {
    return response.data
  },
  async error => {

    let message

    if (error.response) {
      let errorCode: number = error.response.status

      if (errorCode === 401) {
        await deleteAllCookies()

        router.push({path: '/'})
      }
    }

    if (error.Error) {
      message = error.Error
    } else if (error.response.data && error.response.data.errors) {
      message = error.response.data.errors
    } else if (error.response.data && error.response.data.error) {
      message = error.response.data.error
    } else if (error.response.data.message) {
      message = error.response.data.message
    } else {
      message = error
    }

    // filter error message if error is that the user login details is revoked or non doesn't exist.
    if (message === 'Unauthenticated.') {
      message = 'You\'re not logged in. Please login to continue.'
    }

    if (message) {
      Swal.fire({
        title: 'Error!',
        text: 'Do you want to continue',
        icon: 'error',
      })

      Swal.fire({
        titleText: 'Error!',
        text: message,
        icon: 'error',
        color: 'red'
      })
    }

    return Promise.reject(error)
  },
)

export default service
